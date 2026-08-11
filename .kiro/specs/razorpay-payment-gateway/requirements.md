# Requirements Document

## Introduction

This document defines the requirements for integrating Razorpay as the payment gateway for the FarmSea Laravel e-commerce dashboard. The integration replaces the stub `online` and `upi` payment flows (which currently save orders with `payment_status: pending` and do nothing else) with a fully functional Razorpay checkout flow. The COD payment method remains unchanged. The flow uses a server-side Razorpay order creation step, a redirect to a dedicated payment page that auto-launches the Razorpay JS modal, a server-side signature verification callback, and a separate `razorpay_payments` table for transaction records.

---

## Glossary

- **CheckoutController**: `App\Http\Controllers\Frontend\CheckoutController` — handles the checkout form submission and order creation.
- **RazorpayController**: New controller `App\Http\Controllers\Frontend\RazorpayController` — handles Razorpay order creation, payment page rendering, callback verification, and cancellation.
- **RazorpayService**: New service class `App\Services\RazorpayService` — wraps the Razorpay PHP SDK and provides order creation and signature verification.
- **RazorpayPayment**: New Eloquent model backed by the `razorpay_payments` table — stores per-attempt transaction metadata.
- **Payment Page**: The dedicated Blade view at `/checkout/pay/{order}` that auto-opens the Razorpay modal on load.
- **Razorpay Order**: An order object created via the Razorpay API, identified by a `razorpay_order_id` (e.g. `order_xxxxx`).
- **Razorpay Payment**: A payment attempt identified by `razorpay_payment_id` (e.g. `pay_xxxxx`).
- **Razorpay Signature**: An HMAC-SHA256 signature used to verify that a payment callback originated from Razorpay.
- **FarmSea Order**: A row in the `orders` table created by the existing checkout flow.
- **payment_status**: A string column on `orders` with possible values: `pending`, `paid`, `failed`.
- **Paise**: Razorpay amounts are expressed in the smallest currency unit (paise = 1/100th of a rupee).

---

## Requirements

### Requirement 1 — SDK and Configuration

**User Story:** As a developer, I want the Razorpay PHP SDK installed and configured via environment variables, so that the application can communicate with the Razorpay API without hardcoded credentials.

#### Acceptance Criteria

1. THE System SHALL depend on the `razorpay/razorpay` package at a pinned stable version added to `composer.json`.
2. THE System SHALL read `RAZORPAY_KEY_ID` and `RAZORPAY_KEY_SECRET` from the application environment.
3. THE System SHALL expose a `config/razorpay.php` configuration file that reads both keys from the environment with empty-string defaults.
4. WHEN `RAZORPAY_KEY_ID` or `RAZORPAY_KEY_SECRET` is missing from the environment at runtime, THE RazorpayService SHALL throw a `\RuntimeException` before attempting any Razorpay API call.
5. THE `.env.example` file SHALL include `RAZORPAY_KEY_ID=` and `RAZORPAY_KEY_SECRET=` placeholder entries.

---

### Requirement 2 — Checkout Form Submission (online / upi)

**User Story:** As a customer, I want submitting the checkout form with online or UPI payment to create my order and immediately take me to the payment screen, so that I can complete payment without delays.

#### Acceptance Criteria

1. WHEN a customer submits the checkout form with `payment_method` set to `online` or `upi`, THE CheckoutController SHALL create a FarmSea Order with `payment_status: pending` inside a database transaction before making any Razorpay API call.
2. WHEN the FarmSea Order is created, THE CheckoutController SHALL call RazorpayService to create a Razorpay Order with the amount in paise (total × 100, rounded to nearest integer), currency `INR`, and the FarmSea Order's `order_number` as the receipt field.
3. WHEN the Razorpay Order is created successfully, THE CheckoutController SHALL insert a row into `razorpay_payments` with `order_id`, `razorpay_order_id`, `status: initiated`, and `amount` (in paise) and then redirect the customer to the Payment Page at `/checkout/pay/{farmsea_order_id}`.
4. IF the Razorpay API call fails, THEN THE CheckoutController SHALL delete the pending FarmSea Order and redirect back to the checkout form with a user-visible error message: "Payment gateway is unavailable. Please try again."
5. WHEN `payment_method` is `COD`, THE CheckoutController SHALL follow the existing flow unchanged and redirect to the order-success page.

---

### Requirement 3 — Payment Page

**User Story:** As a customer, I want the payment page to automatically open the Razorpay modal so that I can complete my payment without extra clicks.

#### Acceptance Criteria

1. WHEN a customer navigates to `/checkout/pay/{order}`, THE RazorpayController SHALL verify that the authenticated customer owns the FarmSea Order; IF the order does not belong to the customer THEN THE RazorpayController SHALL abort with a 403 response.
2. WHEN the Payment Page is loaded, THE Payment Page SHALL include the Razorpay Checkout JS script (`https://checkout.razorpay.com/v1/checkout.js`) and auto-open the payment modal on `DOMContentLoaded` using the `razorpay_order_id` from the most recent `razorpay_payments` row for that order.
3. THE Payment Page SHALL pre-fill the modal with the customer's name, email, and phone from `shipping_address` stored on the FarmSea Order.
4. THE Payment Page SHALL set the modal `theme.color` to `#22c55e` (FarmSea brand green) and `name` to "FarmSea".
5. WHEN the Razorpay modal `handler` callback fires (payment successful on the client), THE Payment Page SHALL submit a hidden form via POST to `/checkout/razorpay/callback` containing `razorpay_order_id`, `razorpay_payment_id`, and `razorpay_signature`, plus a CSRF token.
6. WHEN the customer dismisses the Razorpay modal without paying, THE Payment Page SHALL display an inline message "Payment cancelled. You can retry or cancel your order." with a "Retry Payment" button that re-opens the modal and a "Cancel Order" link to `/checkout/razorpay/cancel/{order}`.
7. WHILE `payment_status` on the FarmSea Order is `paid`, THE RazorpayController SHALL redirect the customer to the order-success page instead of showing the Payment Page again.

---

### Requirement 4 — Payment Callback and Verification

**User Story:** As a developer, I want the callback endpoint to cryptographically verify the payment before marking it as paid, so that orders are never fraudulently marked as complete.

#### Acceptance Criteria

1. THE System SHALL expose a POST route `/checkout/razorpay/callback` that is excluded from CSRF middleware verification and is accessible without authentication.
2. WHEN the callback receives `razorpay_order_id`, `razorpay_payment_id`, and `razorpay_signature`, THE RazorpayService SHALL compute `HMAC-SHA256(razorpay_order_id + "|" + razorpay_payment_id, RAZORPAY_KEY_SECRET)` and compare it to `razorpay_signature`.
3. WHEN the signature is valid, THE RazorpayController SHALL update the matching `razorpay_payments` row with `razorpay_payment_id`, `razorpay_signature`, and `status: paid` and update the FarmSea Order's `payment_status` to `paid` inside a database transaction, then redirect the customer to the order-success page.
4. IF the signature is invalid, THEN THE RazorpayController SHALL update the `razorpay_payments` row `status` to `failed` and redirect the customer to the Payment Page with a flash error message "Payment verification failed. Please try again or contact support."
5. IF the `razorpay_order_id` in the callback does not match any row in `razorpay_payments`, THEN THE RazorpayController SHALL return a 404 response.
6. WHEN the callback is processed, THE RazorpayController SHALL call `OrderStatusNotificationService::notifyStatusChange` after successfully updating `payment_status` to `paid`.

---

### Requirement 5 — Order Cancellation

**User Story:** As a customer, I want to be able to cancel a pending payment and have my cart restored, so that I am not stuck with an unresolvable pending order.

#### Acceptance Criteria

1. THE System SHALL expose a GET route `/checkout/razorpay/cancel/{order}` protected by the `auth:web_frontend` middleware.
2. WHEN a customer requests cancellation and the FarmSea Order belongs to the authenticated customer and has `payment_status: pending`, THE RazorpayController SHALL update the FarmSea Order `status` to `cancelled` and `payment_status` to `failed`, update the latest `razorpay_payments` row `status` to `cancelled`, and redirect to the cart page with message "Your order has been cancelled."
3. IF the FarmSea Order `payment_status` is already `paid` at the time of the cancellation request, THEN THE RazorpayController SHALL redirect to the order-success page without modifying the order.
4. WHEN a customer cancels, THE RazorpayController SHALL NOT automatically restore the cart session; restoring the cart is the customer's responsibility by re-adding items.

---

### Requirement 6 — Database Schema

**User Story:** As a developer, I want a dedicated table for Razorpay transaction records, so that multiple payment attempts per order can be tracked independently of the orders table.

#### Acceptance Criteria

1. THE System SHALL create a `razorpay_payments` table via a Laravel migration with columns: `id` (bigint, auto-increment PK), `order_id` (foreign key → `orders.id`, on delete cascade), `razorpay_order_id` (string, unique), `razorpay_payment_id` (string, nullable), `razorpay_signature` (string, nullable), `amount` (unsignedBigInteger, amount in paise), `status` (string, values: `initiated`, `paid`, `failed`, `cancelled`), `created_at`, `updated_at`.
2. THE System SHALL add a database index on `razorpay_payments.razorpay_order_id` for fast lookup during callback processing.
3. THE `orders` table SHALL NOT have any new columns added; all Razorpay-specific data is stored in `razorpay_payments`.

---

### Requirement 7 — Dashboard Visibility

**User Story:** As an admin, I want to see the Razorpay payment status on the order detail page in the dashboard, so that I can confirm payment before processing the order.

#### Acceptance Criteria

1. WHEN an order is viewed in the admin dashboard (`/orders/{order}`), THE Dashboard Order view SHALL display `payment_status` (`pending`, `paid`, or `failed`) alongside the existing payment method field.
2. WHEN `payment_status` is `paid`, THE Dashboard Order view SHALL render the status with a green badge.
3. WHEN `payment_status` is `pending` or `failed`, THE Dashboard Order view SHALL render the status with an amber or red badge respectively.

---

### Requirement 8 — Security

**User Story:** As a developer, I want the integration to follow security best practices so that payment data is handled safely.

#### Acceptance Criteria

1. THE RazorpayService SHALL never log the `RAZORPAY_KEY_SECRET` value.
2. THE callback route SHALL validate that all three fields (`razorpay_order_id`, `razorpay_payment_id`, `razorpay_signature`) are present and non-empty strings before performing any database lookup or signature verification.
3. THE Payment Page SHALL NOT expose `RAZORPAY_KEY_SECRET` to the browser; only `RAZORPAY_KEY_ID` may be passed to the Blade view.
4. THE callback route SHALL be rate-limited to 20 requests per minute per IP to mitigate brute-force signature guessing.
