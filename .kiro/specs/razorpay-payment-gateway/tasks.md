# Implementation Plan: Razorpay Payment Gateway

## Overview

Install the Razorpay PHP SDK, add configuration and migration, implement `RazorpayService`, update `CheckoutController`, create `RazorpayController`, add the payment Blade view, register routes, and update the dashboard order view. All steps build incrementally toward a working end-to-end payment flow.

## Tasks

- [x] 1. Install SDK, add config, and update environment files
  - Run `composer require razorpay/razorpay:"^2.9"` to add the pinned SDK dependency
  - Create `config/razorpay.php` that reads `RAZORPAY_KEY_ID` and `RAZORPAY_KEY_SECRET` from env with empty-string defaults
  - Add `RAZORPAY_KEY_ID=` and `RAZORPAY_KEY_SECRET=` placeholder lines to `.env.example`
  - Add the actual test-mode key values to `.env` (do not commit to git)
  - _Requirements: 1.1, 1.2, 1.3, 1.5_

- [ ] 2. Create the `razorpay_payments` migration and model
  - [x] 2.1 Write the migration for `razorpay_payments` table
    - Columns: `id`, `order_id` (FK → orders, cascade delete), `razorpay_order_id` (unique string), `razorpay_payment_id` (nullable string), `razorpay_signature` (nullable string), `amount` (unsignedBigInteger, paise), `status` (string), `timestamps`
    - Add index on `razorpay_order_id`
    - _Requirements: 6.1, 6.2_
  - [x] 2.2 Create `app/Models/RazorpayPayment.php`
    - Fillable fields matching all migration columns
    - `order()` belongsTo relationship to `Order`
    - _Requirements: 6.1_
  - [x] 2.3 Add `razorpayPayments()` hasMany relationship to `app/Models/Order.php`
    - _Requirements: 6.1_
  - [ ]* 2.4 Write example test asserting `razorpay_payments` table exists with correct columns
    - _Requirements: 6.1_

- [x] 3. Implement `RazorpayService`
  - [x] 3.1 Create `app/Services/RazorpayService.php`
    - Constructor reads `config('razorpay.key_id')` and `config('razorpay.key_secret')`, throws `RuntimeException` if either is empty
    - `createOrder(int $amountPaise, string $receipt): array` — calls `$api->order->create([...])`
    - `verifySignature(string $rzpOrderId, string $rzpPaymentId, string $rzpSignature): bool` — HMAC-SHA256 with `hash_equals`
    - _Requirements: 1.4, 4.2, 8.1_
  - [ ]* 3.2 Write property test for `verifySignature` correctness (Property 4)
    - **Property 4: HMAC signature verification correctness**
    - For any `(order_id, payment_id, secret)` triple, `verifySignature` returns true iff the signature matches `hash_hmac('sha256', order_id.'|'.payment_id, secret)`
    - **Validates: Requirements 4.2**
  - [ ]* 3.3 Write property test for paise conversion accuracy (Property 1)
    - **Property 1: Paise conversion accuracy**
    - For any positive decimal total, `(int) round($total * 100)` is always a positive integer equal to the expected paise value
    - **Validates: Requirements 2.2**
  - [ ]* 3.4 Write example tests for missing-credentials RuntimeException
    - Test that constructing `RazorpayService` with blank `key_id` throws `RuntimeException`
    - Test that constructing `RazorpayService` with blank `key_secret` throws `RuntimeException`
    - _Requirements: 1.4_

- [x] 4. Checkpoint — Ensure all tests pass
  - Ensure all tests pass, ask the user if questions arise.

- [x] 5. Update `CheckoutController::store()` to branch on payment method
  - [x] 5.1 Refactor COD and online/UPI branches in `store()`
    - Move the cart-clearing and coupon-usage session code out of the shared path — COD executes it immediately; online/upi defer it to the callback
    - After DB transaction creates the order for online/upi: call `RazorpayService::createOrder`, create `RazorpayPayment` with `status=initiated`, redirect to `frontend.razorpay.payment`
    - Wrap Razorpay API call in try/catch: on failure, delete the pending order and redirect back with error message "Payment gateway is unavailable. Please try again."
    - _Requirements: 2.1, 2.2, 2.3, 2.4, 2.5_
  - [ ]* 5.2 Write example test for COD flow still reaching order-success
    - _Requirements: 2.5_
  - [ ]* 5.3 Write example test for online/upi flow redirecting to payment page on success
    - _Requirements: 2.1, 2.2, 2.3_
  - [ ]* 5.4 Write edge-case test for Razorpay API failure deleting order and redirecting back
    - _Requirements: 2.4_

- [x] 6. Implement `RazorpayController`
  - [x] 6.1 Create `app/Http/Controllers/Frontend/RazorpayController.php`
    - `show(Order $order)` — authorize ownership (abort 403 if mismatch), redirect to success if already paid, otherwise render `frontend.razorpay-payment`
    - `callback(Request $request)` — validate three required fields, find `RazorpayPayment` by `razorpay_order_id` (firstOrFail → 404 if missing), verify signature, on success: DB transaction updates both models + clears cart, calls notification service, redirects to success; on failure: marks `failed`, redirects to payment page with error
    - `cancel(Order $order)` — authorize ownership, guard against paid orders, DB transaction marks order cancelled and payment row cancelled, redirects to cart
    - _Requirements: 3.1, 3.7, 4.1, 4.3, 4.4, 4.5, 4.6, 5.1, 5.2, 5.3_
  - [ ]* 6.2 Write property test for payment page authorization (Property 2)
    - **Property 2: Payment page authorization**
    - For any order, authenticated user whose user_id ≠ order.user_id receives 403; owner receives 200
    - **Validates: Requirements 3.1**
  - [ ]* 6.3 Write property test for paid-order redirect invariant (Property 6)
    - **Property 6: Paid order redirect invariant**
    - For any order with payment_status=paid, accessing the payment page redirects to order-success and does not modify the order
    - **Validates: Requirements 3.7**
  - [ ]* 6.4 Write property test for callback rejecting incomplete payloads (Property 5)
    - **Property 5: Callback rejects incomplete payloads**
    - For any request missing one or more of the three required fields, callback returns 422 and makes no DB changes
    - **Validates: Requirements 8.2**
  - [ ]* 6.5 Write example test for valid callback signature → payment_status updated to paid
    - _Requirements: 4.3, 4.6_
  - [ ]* 6.6 Write edge-case test for invalid signature → payment_status stays pending, redirects to payment page
    - _Requirements: 4.4_
  - [ ]* 6.7 Write edge-case test for cancel on paid order → no modification, redirect to success
    - _Requirements: 5.3_

- [x] 7. Register routes in `routes/web.php`
  - Add `GET /checkout/pay/{order}` → `RazorpayController::show` named `frontend.razorpay.payment` inside `auth:web_frontend` group
  - Add `GET /checkout/razorpay/cancel/{order}` → `RazorpayController::cancel` named `frontend.razorpay.cancel` inside `auth:web_frontend` group
  - Add `POST /checkout/razorpay/callback` → `RazorpayController::callback` named `frontend.razorpay.callback` outside auth group, with `->withoutMiddleware([VerifyCsrfToken::class])->middleware('throttle:20,1')`
  - _Requirements: 3.1, 4.1, 5.1, 8.4_

- [x] 8. Create the payment Blade view
  - [x] 8.1 Create `resources/views/frontend/razorpay-payment.blade.php`
    - Extends `frontend.layouts.app`
    - Shows order number and total
    - Displays `session('error')` flash if present
    - Hidden `#payment-cancelled-msg` div shown by JS on modal dismiss
    - `#pay-btn` button to open/retry modal
    - Cancel order link to `frontend.razorpay.cancel`
    - Hidden form `#rzp-form` POSTing to `frontend.razorpay.callback` with CSRF + three hidden inputs
    - `@push('scripts')`: Razorpay checkout.js script tag + inline JS that builds options object (key from `$keyId`, amount from `$razorpayPayment->amount`, prefill from `$order->shipping_address`), auto-opens on `DOMContentLoaded`, wires `handler` to populate hidden form and submit, wires `ondismiss` to show cancelled message
    - _Requirements: 3.2, 3.3, 3.4, 3.5, 3.6_
  - [ ]* 8.2 Write property test asserting key secret not in rendered page HTML (Property 3)
    - **Property 3: Payment page data isolation — key secret not exposed**
    - For any configured key secret, the rendered payment page HTML does not contain that secret string
    - **Validates: Requirements 8.3**

- [x] 9. Update dashboard order detail view
  - Locate the order show view under `resources/views/dashboard/orders/` (or the relevant partial)
  - Add a `payment_status` badge next to the payment method field using the three-way color match: `paid` → emerald, `failed` → red, `pending` → amber
  - _Requirements: 7.1, 7.2, 7.3_

- [x] 10. Final checkpoint — Ensure all tests pass
  - Run `php artisan migrate` to apply the new migration
  - Run the full test suite and confirm all passing
  - Manually verify: COD checkout still reaches order-success; online checkout opens payment page; test card payment completes; cancellation redirects to cart
  - Ensure all tests pass, ask the user if questions arise.

## Task Dependency Graph

```
1 → 2 → 3 → 4 (checkpoint)
            ↓
            5 → 6 → 7 → 8 → 9 → 10 (final checkpoint)
```

- Task 1 (SDK + config) must come first — all other tasks depend on the Razorpay package and config keys being available
- Task 2 (migration + model) must precede tasks 5, 6 which create/query `razorpay_payments` rows
- Task 3 (`RazorpayService`) must precede tasks 5 and 6 which call it
- Task 4 is the first checkpoint after the foundational service layer is built
- Task 5 (`CheckoutController` changes) depends on tasks 1–3
- Task 6 (`RazorpayController`) depends on tasks 2, 3, and 5 (uses the model, service, and relies on cart logic split done in 5)
- Task 7 (routes) depends on task 6 (controllers must exist before routes reference them)
- Task 8 (Blade view) depends on task 7 (routes must exist for the form action and links)
- Task 9 (dashboard view) is independent of tasks 5–8 and can be done in parallel
- Task 10 is the final checkpoint after everything is wired

## Notes

- Tasks marked with `*` are optional and can be skipped for a faster MVP
- The Razorpay test mode keys should be used during development — never commit real production keys
- The `clearCartForOrder` logic in `RazorpayController` must mirror the cart-clearing code currently at the bottom of `CheckoutController::store()` to avoid double-clearing or leaving cart stale
- PHPUnit is already configured via `composer.json`; property-based tests can use `eris/eris` or equivalent, or be written as data-provider driven tests with a wide range of generated inputs
