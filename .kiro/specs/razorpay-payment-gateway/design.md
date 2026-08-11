# Design Document — Razorpay Payment Gateway Integration

## Overview

This document describes the architecture and implementation design for integrating Razorpay into the FarmSea Laravel 11 dashboard. The integration adds a `RazorpayService`, a `RazorpayController`, a `RazorpayPayment` model, a new migration, config file, and two Blade views (payment page, partial for retry message). The COD flow in `CheckoutController` is untouched.

**Detected Language**: PHP 8.2 / Laravel 11 (Blade + Tailwind + vanilla JS)

---

## Architecture

```
Customer Browser
      │
      │  POST /checkout  (payment_method=online|upi)
      ▼
CheckoutController::store()
      │  1. Validate & build order (existing logic)
      │  2. DB::transaction → create FarmSea Order (payment_status=pending)
      │  3. RazorpayService::createOrder(amount_paise, receipt)
      │  4. RazorpayPayment::create(...)
      │  redirect /checkout/pay/{order_id}
      ▼
RazorpayController::show()  [GET /checkout/pay/{order}]
      │  Renders payment.blade.php
      │  Passes razorpay_order_id + KEY_ID + customer data
      ▼
Payment Page (Blade + Razorpay JS)
      │  Auto-opens modal
      │  On success → hidden form POST /checkout/razorpay/callback
      │  On dismiss  → shows retry/cancel buttons
      ▼
RazorpayController::callback()  [POST /checkout/razorpay/callback]
      │  RazorpayService::verifySignature(...)
      │  DB::transaction → update RazorpayPayment + Order
      │  redirect /order-success/{order_id}
      ▼
RazorpayController::cancel()  [GET /checkout/razorpay/cancel/{order}]
      │  Guard: payment_status == pending
      │  Update Order status=cancelled, payment_status=failed
      │  Update RazorpayPayment status=cancelled
      │  redirect /cart
```

---

## Components

### 1. `config/razorpay.php`

```php
<?php

return [
    'key_id'     => env('RAZORPAY_KEY_ID', ''),
    'key_secret' => env('RAZORPAY_KEY_SECRET', ''),
];
```

### 2. `app/Services/RazorpayService.php`

Wraps the `razorpay/razorpay` SDK. Injected via constructor in `RazorpayController`.

```php
<?php

namespace App\Services;

use Razorpay\Api\Api;
use RuntimeException;

class RazorpayService
{
    private Api $api;

    public function __construct()
    {
        $keyId     = config('razorpay.key_id');
        $keySecret = config('razorpay.key_secret');

        if (empty($keyId) || empty($keySecret)) {
            throw new RuntimeException('Razorpay API credentials are not configured.');
        }

        $this->api = new Api($keyId, $keySecret);
    }

    /**
     * Create a Razorpay order.
     *
     * @param  int    $amountPaise  Total in paise (rupees × 100)
     * @param  string $receipt      FarmSea order_number used as receipt identifier
     * @return array{id: string, amount: int, currency: string}
     */
    public function createOrder(int $amountPaise, string $receipt): array
    {
        $order = $this->api->order->create([
            'amount'          => $amountPaise,
            'currency'        => 'INR',
            'receipt'         => $receipt,
            'payment_capture' => 1,
        ]);

        return [
            'id'       => $order->id,
            'amount'   => $order->amount,
            'currency' => $order->currency,
        ];
    }

    /**
     * Verify Razorpay payment signature.
     *
     * Computes HMAC-SHA256(razorpay_order_id|razorpay_payment_id, key_secret)
     * and performs a constant-time comparison against the provided signature.
     */
    public function verifySignature(
        string $razorpayOrderId,
        string $razorpayPaymentId,
        string $razorpaySignature
    ): bool {
        $payload  = $razorpayOrderId . '|' . $razorpayPaymentId;
        $expected = hash_hmac('sha256', $payload, config('razorpay.key_secret'));

        return hash_equals($expected, $razorpaySignature);
    }
}
```

**Key design decisions:**
- `hash_equals` prevents timing attacks during signature comparison.
- `createOrder` converts to `int` at the call site to keep the service pure.
- The key secret is never passed to a logger or returned in any response.

---

### 3. Migration — `razorpay_payments` table

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('razorpay_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->string('razorpay_order_id')->unique();
            $table->string('razorpay_payment_id')->nullable();
            $table->string('razorpay_signature')->nullable();
            $table->unsignedBigInteger('amount');          // in paise
            $table->string('status');                      // initiated|paid|failed|cancelled
            $table->timestamps();

            $table->index('razorpay_order_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('razorpay_payments');
    }
};
```

---

### 4. `app/Models/RazorpayPayment.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RazorpayPayment extends Model
{
    protected $fillable = [
        'order_id',
        'razorpay_order_id',
        'razorpay_payment_id',
        'razorpay_signature',
        'amount',
        'status',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
```

---

### 5. `CheckoutController` Changes

Only the `store()` method is modified. The change is gated by `payment_method`:

```php
// Inside CheckoutController::store(), after the DB transaction creates $order:

if (in_array($data['payment_method'], ['online', 'upi'], true)) {
    try {
        $razorpayService = app(\App\Services\RazorpayService::class);
        $amountPaise     = (int) round($order->total * 100);
        $rzpOrder        = $razorpayService->createOrder($amountPaise, $order->order_number);

        \App\Models\RazorpayPayment::create([
            'order_id'          => $order->id,
            'razorpay_order_id' => $rzpOrder['id'],
            'amount'            => $rzpOrder['amount'],
            'status'            => 'initiated',
        ]);

        return redirect()->route('frontend.razorpay.payment', $order->id);

    } catch (\Throwable $e) {
        $order->delete();
        return back()->withInput()->with('error', 'Payment gateway is unavailable. Please try again.');
    }
}

// COD path — existing redirect unchanged:
return redirect()->route('frontend.order.success', $order->id);
```

**Important:** The cart clearing and coupon usage code currently runs before the redirect in `store()`. That logic must be moved inside the `if (COD)` branch so it only executes on a confirmed COD order. For online/upi the cart is cleared after successful payment verification in the callback.

---

### 6. `app/Http/Controllers/Frontend/RazorpayController.php`

```php
<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\RazorpayPayment;
use App\Services\RazorpayService;
use App\Services\OrderStatusNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RazorpayController extends Controller
{
    public function __construct(private RazorpayService $razorpayService) {}

    /** GET /checkout/pay/{order} */
    public function show(Order $order)
    {
        $this->authorizeOrder($order);

        // Already paid — send to success page
        if ($order->payment_status === 'paid') {
            return redirect()->route('frontend.order.success', $order->id);
        }

        $razorpayPayment = $order->razorpayPayments()->latest()->firstOrFail();
        $keyId           = config('razorpay.key_id');

        return view('frontend.razorpay-payment', compact('order', 'razorpayPayment', 'keyId'));
    }

    /** POST /checkout/razorpay/callback (CSRF-exempt, rate-limited) */
    public function callback(Request $request)
    {
        $request->validate([
            'razorpay_order_id'   => 'required|string|min:1',
            'razorpay_payment_id' => 'required|string|min:1',
            'razorpay_signature'  => 'required|string|min:1',
        ]);

        $rzpOrderId  = $request->input('razorpay_order_id');
        $rzpPayId    = $request->input('razorpay_payment_id');
        $rzpSig      = $request->input('razorpay_signature');

        $razorpayPayment = RazorpayPayment::where('razorpay_order_id', $rzpOrderId)->firstOrFail();
        $order           = $razorpayPayment->order;

        if ($this->razorpayService->verifySignature($rzpOrderId, $rzpPayId, $rzpSig)) {
            DB::transaction(function () use ($razorpayPayment, $order, $rzpPayId, $rzpSig) {
                $razorpayPayment->update([
                    'razorpay_payment_id' => $rzpPayId,
                    'razorpay_signature'  => $rzpSig,
                    'status'              => 'paid',
                ]);

                $order->update(['payment_status' => 'paid']);

                // Clear cart and coupon now that payment is confirmed
                $this->clearCartForOrder($order);
            });

            OrderStatusNotificationService::notifyStatusChange($order->fresh());

            return redirect()->route('frontend.order.success', $order->id);
        }

        $razorpayPayment->update(['status' => 'failed']);

        return redirect()
            ->route('frontend.razorpay.payment', $order->id)
            ->with('error', 'Payment verification failed. Please try again or contact support.');
    }

    /** GET /checkout/razorpay/cancel/{order} */
    public function cancel(Order $order)
    {
        $this->authorizeOrder($order);

        if ($order->payment_status === 'paid') {
            return redirect()->route('frontend.order.success', $order->id);
        }

        DB::transaction(function () use ($order) {
            $order->update([
                'status'         => 'cancelled',
                'payment_status' => 'failed',
            ]);

            $order->razorpayPayments()->latest()->update(['status' => 'cancelled']);
        });

        return redirect()->route('frontend.cart')->with('success', 'Your order has been cancelled.');
    }

    private function authorizeOrder(Order $order): void
    {
        if ($order->user_id !== auth('web_frontend')->id()) {
            abort(403);
        }
    }

    private function clearCartForOrder(Order $order): void
    {
        // Mirrors the cart-clear logic currently at end of CheckoutController::store()
        $checkoutDay = $order->delivery_day ?? 'today';
        $fullCart    = session('cart', []);

        $remainingCart = array_filter(
            $fullCart,
            fn ($item) => \App\Support\ProductDayPricing::normalizeDay($item['pricing_day'] ?? 'today') !== $checkoutDay
        );

        session(['cart' => $remainingCart]);
        session()->forget('applied_coupon_' . $checkoutDay);
        session()->forget('checkout_delivery_day');
    }
}
```

---

### 7. Routes

```php
// Inside the auth:web_frontend middleware group:
Route::get('/checkout/pay/{order}',           [RazorpayController::class, 'show'])->name('frontend.razorpay.payment');
Route::get('/checkout/razorpay/cancel/{order}', [RazorpayController::class, 'cancel'])->name('frontend.razorpay.cancel');

// Outside middleware group (CSRF-exempt section):
Route::post('/checkout/razorpay/callback',    [RazorpayController::class, 'callback'])
    ->name('frontend.razorpay.callback')
    ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class])
    ->middleware('throttle:20,1');
```

---

### 8. `resources/views/frontend/razorpay-payment.blade.php`

```blade
@extends('frontend.layouts.app')

@section('title', 'Complete Payment — FarmSea')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4">
    <div class="max-w-md w-full bg-white rounded-2xl shadow-sm p-8 text-center">

        <img src="{{ asset('images/logo.png') }}" alt="FarmSea" class="h-10 mx-auto mb-6">

        <h1 class="text-xl font-semibold text-gray-800 mb-1">Complete your payment</h1>
        <p class="text-gray-500 text-sm mb-6">
            Order #{{ $order->order_number }} &mdash;
            <span class="font-medium text-gray-700">₹{{ number_format($order->total, 2) }}</span>
        </p>

        @if (session('error'))
            <div class="mb-4 rounded-lg bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3">
                {{ session('error') }}
            </div>
        @endif

        {{-- Post-dismiss UI (hidden until JS triggers it) --}}
        <div id="payment-cancelled-msg" class="hidden mb-6 rounded-lg bg-amber-50 border border-amber-200 text-amber-800 text-sm px-4 py-3">
            Payment cancelled. You can retry or cancel your order.
        </div>

        <button id="pay-btn"
            class="w-full bg-green-500 hover:bg-green-600 text-white font-semibold py-3 rounded-xl transition">
            Pay ₹{{ number_format($order->total, 2) }}
        </button>

        <a href="{{ route('frontend.razorpay.cancel', $order->id) }}"
           class="mt-4 block text-sm text-gray-400 hover:text-red-500 transition">
            Cancel order
        </a>

        {{-- Hidden form submitted by Razorpay handler callback --}}
        <form id="rzp-form" action="{{ route('frontend.razorpay.callback') }}" method="POST" class="hidden">
            @csrf
            <input type="hidden" name="razorpay_order_id"   id="rzp_order_id">
            <input type="hidden" name="razorpay_payment_id" id="rzp_payment_id">
            <input type="hidden" name="razorpay_signature"  id="rzp_signature">
        </form>

    </div>
</div>
@endsection

@push('scripts')
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
    const options = {
        key:         '{{ $keyId }}',
        amount:      {{ $razorpayPayment->amount }},
        currency:    'INR',
        name:        'FarmSea',
        description: 'Order #{{ $order->order_number }}',
        order_id:    '{{ $razorpayPayment->razorpay_order_id }}',
        prefill: {
            name:    '{{ addslashes($order->shipping_address["name"] ?? "") }}',
            email:   '{{ addslashes($order->user->email ?? "") }}',
            contact: '{{ addslashes($order->shipping_address["phone"] ?? "") }}',
        },
        theme: { color: '#22c55e' },
        handler: function (response) {
            document.getElementById('rzp_order_id').value   = response.razorpay_order_id;
            document.getElementById('rzp_payment_id').value = response.razorpay_payment_id;
            document.getElementById('rzp_signature').value  = response.razorpay_signature;
            document.getElementById('rzp-form').submit();
        },
        modal: {
            ondismiss: function () {
                document.getElementById('payment-cancelled-msg').classList.remove('hidden');
                document.getElementById('pay-btn').textContent = 'Retry Payment';
            }
        }
    };

    const rzp = new Razorpay(options);

    // Auto-open on page load
    document.addEventListener('DOMContentLoaded', function () {
        rzp.open();
    });

    document.getElementById('pay-btn').addEventListener('click', function () {
        rzp.open();
    });
</script>
@endpush
```

---

### 9. Dashboard Order View Changes

In the existing `resources/views/dashboard/orders/show.blade.php` (or equivalent partial), add a payment status badge alongside the existing payment method display:

```blade
{{-- Payment Status Badge --}}
@php
    $badgeClass = match($order->payment_status) {
        'paid'    => 'bg-emerald-100 text-emerald-700',
        'failed'  => 'bg-red-100 text-red-700',
        default   => 'bg-amber-100 text-amber-700',
    };
@endphp
<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $badgeClass }}">
    {{ ucfirst($order->payment_status ?? 'pending') }}
</span>
```

---

## Data Flow — Payment Sequence

```
1. Customer fills checkout form, selects online/UPI, clicks "Place Order"
2. POST /checkout
   → CheckoutController::store()
   → DB transaction: create Order(payment_status=pending)
   → RazorpayService::createOrder(amountPaise, receipt)  [Razorpay API]
   → RazorpayPayment::create(status=initiated)
   → redirect /checkout/pay/{order_id}

3. GET /checkout/pay/{order_id}
   → RazorpayController::show()
   → Renders razorpay-payment.blade.php
   → JS auto-opens Razorpay modal

4a. Customer completes payment in modal
   → handler() fires
   → Hidden form POST /checkout/razorpay/callback

5a. POST /checkout/razorpay/callback
   → RazorpayController::callback()
   → Validate fields present
   → Lookup RazorpayPayment by razorpay_order_id
   → RazorpayService::verifySignature()
   → Signature OK → DB transaction: RazorpayPayment(status=paid), Order(payment_status=paid)
   → Clear cart session
   → OrderStatusNotificationService::notifyStatusChange()
   → redirect /order-success/{order_id}

4b. Customer dismisses modal
   → JS shows "Payment cancelled" message + Retry/Cancel buttons

5b. Customer clicks Cancel Order
   → GET /checkout/razorpay/cancel/{order_id}
   → RazorpayController::cancel()
   → Order(status=cancelled, payment_status=failed), RazorpayPayment(status=cancelled)
   → redirect /cart
```

---

## Error Handling

| Scenario | Response |
|---|---|
| Razorpay API down on order creation | Delete pending FarmSea Order, redirect back to checkout with error |
| Invalid/tampered signature in callback | Mark RazorpayPayment failed, redirect to payment page with error message |
| razorpay_order_id not found in DB | 404 |
| Customer accesses another user's payment page | 403 |
| Customer accesses payment page for paid order | Redirect to order-success |
| Customer attempts to cancel a paid order | Redirect to order-success, no modification |
| Missing credentials at boot | RuntimeException before any API call |

---

## Correctness Properties

*A property is a characteristic or behavior that should hold true across all valid executions of a system — essentially, a formal statement about what the system should do. Properties serve as the bridge between human-readable specifications and machine-verifiable correctness guarantees.*

### Property 1: Paise conversion accuracy

For any valid order total (a positive decimal number of rupees), the paise amount passed to Razorpay SHALL equal `(int) round(total * 100)`, and shall always be a positive integer.

**Validates: Requirements 2.2**

---

### Property 2: Payment page authorization

For any FarmSea Order, only the authenticated customer whose `user_id` matches `order.user_id` SHALL receive a 200 response on the payment page; any other authenticated user SHALL receive a 403.

**Validates: Requirements 3.1**

---

### Property 3: Payment page data isolation — key secret not exposed

For any configured `RAZORPAY_KEY_SECRET` value, the rendered HTML of the payment page SHALL NOT contain that secret string.

**Validates: Requirements 8.3**

---

### Property 4: HMAC signature verification correctness

For any triple `(razorpay_order_id, razorpay_payment_id, key_secret)`, `RazorpayService::verifySignature()` SHALL return `true` if and only if `razorpay_signature` equals `hash_hmac('sha256', razorpay_order_id . '|' . razorpay_payment_id, key_secret)`.

**Validates: Requirements 4.2**

---

### Property 5: Callback rejects incomplete payloads

For any callback request missing one or more of `razorpay_order_id`, `razorpay_payment_id`, or `razorpay_signature`, THE callback endpoint SHALL return a validation error (HTTP 422) and SHALL NOT modify any database record.

**Validates: Requirements 8.2**

---

### Property 6: Paid order redirect invariant

For any FarmSea Order with `payment_status = paid`, accessing the payment page (`/checkout/pay/{order}`) SHALL result in a redirect to the order-success page with no modification to the order's `payment_status`.

**Validates: Requirements 3.7**
