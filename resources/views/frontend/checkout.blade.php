@extends('frontend.layouts.app')
@section('title', 'Checkout')

@section('content')
@php
    $isCodAvailable = ($pricing['subtotal'] ?? 0) <= 2000;
    $paymentOptions = $isCodAvailable
        ? ['COD' => 'Cash on Delivery', 'online' => 'Online Payment', 'upi' => 'UPI']
        : ['online' => 'Online Payment', 'upi' => 'UPI'];
    $selectedPaymentMethod = old('payment_method', $isCodAvailable ? 'COD' : 'online');

    if (! array_key_exists($selectedPaymentMethod, $paymentOptions)) {
        $selectedPaymentMethod = array_key_first($paymentOptions);
    }

    $initialCouponCode = old('coupon_code');
@endphp
<div class="max-w-5xl mx-auto px-4 py-8">
    <h1 class="nunito font-extrabold text-2xl text-gray-800 mb-6">Checkout</h1>

    <div class="flex flex-col gap-6 lg:flex-row">
        <div class="flex-1 space-y-5">
            <form method="POST" action="{{ route('frontend.checkout.store') }}" id="checkoutForm">
                @csrf
                <input type="hidden" name="delivery_slot" value="{{ $selectedDeliverySlot }}">

                @if($errors->any())
                <div class="rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-600">
                    @foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach
                </div>
                @endif

                <div class="overflow-hidden rounded-2xl border bg-white">
                    <div class="flex items-center gap-2 border-b px-6 py-4">
                        <div class="flex h-7 w-7 items-center justify-center rounded-full bg-blue-700 text-xs font-bold text-white">1</div>
                        <h2 class="nunito text-base font-extrabold text-gray-800">Contact Information</h2>
                    </div>
                    <div class="grid grid-cols-1 gap-4 px-6 py-5 sm:grid-cols-2">
                        <div>
                            <label class="mb-1 block text-xs font-semibold text-gray-600">First Name *</label>
                            <input type="text" name="first_name" value="{{ $checkoutDefaults['first_name'] ?? '' }}" required readonly
                                   class="w-full cursor-not-allowed rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-500 outline-none">
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-semibold text-gray-600">Last Name</label>
                            <input type="text" name="last_name" value="{{ $checkoutDefaults['last_name'] ?? '' }}" readonly
                                   class="w-full cursor-not-allowed rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-500 outline-none">
                        </div>
                        <div class="sm:col-span-2">
                            <label class="mb-1 block text-xs font-semibold text-gray-600">Email *</label>
                            <input type="email" name="email" value="{{ $checkoutDefaults['email'] ?? '' }}" required readonly
                                   class="w-full cursor-not-allowed rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-500 outline-none">
                        </div>
                        <div class="sm:col-span-2">
                            <label class="mb-1 block text-xs font-semibold text-gray-600">Phone *</label>
                            <input type="tel" name="phone" id="checkoutPhoneInput" value="{{ $checkoutDefaults['phone'] ?? '' }}" required
                                   inputmode="numeric" pattern="(?:\d{10}|\d{12})" minlength="10" maxlength="12" autocomplete="off"
                                   title="Phone number must be 10 or 12 digits"
                                   class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm outline-none focus:border-blue-500">
                        </div>
                    </div>
                </div>

                <div class="overflow-hidden rounded-2xl border bg-white">
                    <div class="flex items-center gap-2 border-b px-6 py-4">
                        <div class="flex h-7 w-7 items-center justify-center rounded-full bg-blue-700 text-xs font-bold text-white">2</div>
                        <h2 class="nunito text-base font-extrabold text-gray-800">Shipping Address</h2>
                    </div>
                    <div class="space-y-4 px-6 py-5">
                        <div>
                            <label class="mb-1 block text-xs font-semibold text-gray-600">Street Address *</label>
                            <input type="text" name="address" value="{{ old('address', $checkoutDefaults['address'] ?? '') }}" required
                                   class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm outline-none focus:border-blue-500">
                        </div>
                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <label class="mb-1 block text-xs font-semibold text-gray-600">City *</label>
                                <input type="text" name="city" value="{{ old('city', $checkoutDefaults['city'] ?? '') }}" required
                                       class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm outline-none focus:border-blue-500">
                            </div>
                            <div>
                                <label class="mb-1 block text-xs font-semibold text-gray-600">State *</label>
                                <input type="text" name="state" value="{{ old('state', $checkoutDefaults['state'] ?? '') }}" required
                                       class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm outline-none focus:border-blue-500">
                            </div>
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-semibold text-gray-600">PIN Code *</label>
                            <input type="text" name="pincode" value="{{ old('pincode', $checkoutDefaults['pincode'] ?? '') }}" required
                                   class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm outline-none focus:border-blue-500">
                        </div>
                    </div>
                </div>

                <div class="overflow-hidden rounded-2xl border bg-white">
                    <div class="flex items-center gap-2 border-b px-6 py-4">
                        <div class="flex h-7 w-7 items-center justify-center rounded-full bg-blue-700 text-xs font-bold text-white">3</div>
                        <h2 class="nunito text-base font-extrabold text-gray-800">Payment Method</h2>
                    </div>
                    <div class="space-y-3 px-6 py-5">
                        @unless($isCodAvailable)
                            <div class="rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-xs font-semibold text-amber-700">
                                Cash on Delivery is available only for orders up to &#8377;2,000.
                            </div>
                        @endunless
                        @foreach($paymentOptions as $val => $label)
                        <label class="flex cursor-pointer items-center gap-3 rounded-xl border border-gray-200 p-4 transition hover:border-blue-500">
                            <input type="radio" name="payment_method" value="{{ $val }}" {{ $selectedPaymentMethod === $val ? 'checked' : '' }} class="accent-blue-600">
                            <span class="text-sm font-semibold text-gray-700">{{ $label }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <div class="rounded-2xl border bg-white p-5">
                    <label class="mb-2 block text-xs font-semibold text-gray-600">Coupon Code (optional)</label>
                    <div class="flex flex-col gap-2 sm:flex-row">
                        <input type="text" name="coupon_code" id="couponCodeInput" value="{{ $initialCouponCode }}" placeholder="Enter coupon code"
                               class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm uppercase outline-none focus:border-blue-500">
                        <button type="button" id="applyCouponButton"
                                class="{{ $initialCouponCode ? '' : 'hidden' }} rounded-xl bg-green-600 px-5 py-2.5 text-sm font-bold text-white transition hover:bg-green-700">
                            Apply
                        </button>
                    </div>
                    <p id="couponMessage" class="mt-2 hidden text-xs font-semibold"></p>
                    @if(($availableCoupons ?? collect())->isNotEmpty())
                        <div class="mt-4 flex flex-wrap gap-2">
                            @foreach($availableCoupons as $coupon)
                                <button type="button"
                                        data-coupon-code="{{ $coupon->code }}"
                                        class="coupon-chip rounded-full border border-green-200 bg-green-50 px-3 py-1.5 text-[11px] font-bold text-green-700 transition hover:border-green-500 hover:bg-white">
                                    {{ $coupon->code }}
                                </button>
                            @endforeach
                        </div>
                    @endif
                </div>

                <button type="submit" id="placeOrderButton"
                        class="flex w-full items-center justify-center gap-2 rounded-2xl bg-blue-700 py-4 text-sm font-bold text-white transition hover:bg-blue-800">
                    <i class="fa-solid fa-lock text-xs"></i>
                    Place Order - <span id="placeOrderTotal">&#8377;{{ number_format($pricing['total'], 2) }}</span>
                </button>
            </form>
        </div>

        <div class="w-full flex-shrink-0 lg:w-80">
            <div class="sticky top-24 overflow-hidden rounded-2xl border bg-white">
                <div class="h-1 bg-gradient-to-r from-blue-700 to-orange-400"></div>
                <div class="border-b px-5 py-4">
                    <h2 class="nunito text-base font-extrabold text-gray-800">Order Summary</h2>
                </div>
                <div class="max-h-64 space-y-3 overflow-y-auto border-b px-5 py-4">
                    @foreach($items as $item)
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center overflow-hidden rounded-lg bg-gray-100">
                            @if($item['image'])
                                <img src="{{ asset('storage/' . $item['image']) }}" class="h-full w-full object-cover" alt="{{ $item['name'] }}">
                            @else
                                <i class="fa-solid fa-basket-shopping text-sm text-gray-400"></i>
                            @endif
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="truncate text-xs font-semibold text-gray-800">{{ $item['name'] }}</p>
                            <p class="text-[10px] text-gray-400">Qty: {{ $item['quantity'] }}</p>
                        </div>
                        <span class="flex-shrink-0 text-xs font-bold text-gray-800">&#8377;{{ number_format($item['subtotal'], 2) }}</span>
                    </div>
                    @endforeach
                </div>
                <div class="space-y-2 px-5 py-4 text-sm">
                    <div class="flex justify-between"><span class="text-gray-500">Subtotal</span><span>&#8377;{{ number_format($pricing['subtotal'], 2) }}</span></div>
                    @if((float) ($pricing['delivery_charge'] ?? 0) > 0)
                        <div class="flex justify-between"><span class="text-gray-500">Delivery</span><span>&#8377;{{ number_format($pricing['delivery_charge'], 2) }}</span></div>
                    @endif
                    @if((float) ($pricing['service_charge'] ?? 0) > 0)
                    <div class="flex justify-between">
                        <span class="text-gray-500">&#8505;&#65039; Service Charge ({{ rtrim(rtrim(number_format($pricing['service_charge_percent'], 2), '0'), '.') }}%)</span>
                        <span>&#8377;{{ number_format($pricing['service_charge'], 2) }}</span>
                    </div>
                    @endif
                    <div id="checkoutDiscountRow" class="hidden justify-between text-green-600">
                        <span>Discount</span>
                        <span id="checkoutDiscountAmount">-&#8377;0.00</span>
                    </div>
                    <hr class="border-gray-100">
                    <div class="flex justify-between text-base font-bold text-gray-800">
                        <span>Total</span>
                        <span id="checkoutFinalTotal" class="text-blue-700">&#8377;{{ number_format($pricing['total'], 2) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
const checkoutPricing = {
    subtotal: Number(@json((float) ($pricing['subtotal'] ?? 0))),
    total: Number(@json((float) ($pricing['total'] ?? 0))),
};

const couponInput = document.getElementById('couponCodeInput');
const applyCouponButton = document.getElementById('applyCouponButton');
const couponMessage = document.getElementById('couponMessage');
const discountRow = document.getElementById('checkoutDiscountRow');
const discountAmount = document.getElementById('checkoutDiscountAmount');
const finalTotal = document.getElementById('checkoutFinalTotal');
const placeOrderTotal = document.getElementById('placeOrderTotal');
const phoneInput = document.getElementById('checkoutPhoneInput');

function checkoutCurrency(amount) {
    return '₹' + Number(amount || 0).toFixed(2);
}

function setCouponMessage(message, isSuccess = false) {
    couponMessage.textContent = message;
    couponMessage.classList.remove('hidden', 'text-green-600', 'text-red-600');
    couponMessage.classList.add(isSuccess ? 'text-green-600' : 'text-red-600');
}

function updateCouponButton() {
    const hasCode = couponInput.value.trim().length > 0;
    applyCouponButton.classList.toggle('hidden', !hasCode);

    if (!hasCode) {
        couponMessage.classList.add('hidden');
        discountRow.classList.add('hidden');
        discountRow.classList.remove('flex');
        finalTotal.textContent = checkoutCurrency(checkoutPricing.total);
        placeOrderTotal.textContent = checkoutCurrency(checkoutPricing.total);
    }
}

function applyCoupon() {
    const code = couponInput.value.trim().toUpperCase();

    if (!code) {
        updateCouponButton();
        return;
    }

    applyCouponButton.disabled = true;
    applyCouponButton.textContent = 'Applying...';

    fetch('/api/coupons/validate', {
        method: 'POST',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            code,
            order_amount: checkoutPricing.subtotal,
        }),
    })
        .then(async (response) => {
            const data = await response.json();

            if (!response.ok || !data.success) {
                throw new Error(data.message || 'Coupon could not be applied.');
            }

            const discount = Number(data.discount || 0);
            const updatedTotal = Math.max(checkoutPricing.total - discount, 0);

            couponInput.value = data.coupon?.code || code;
            discountAmount.textContent = '-' + checkoutCurrency(discount);
            discountRow.classList.remove('hidden');
            discountRow.classList.add('flex');
            finalTotal.textContent = checkoutCurrency(updatedTotal);
            placeOrderTotal.textContent = checkoutCurrency(updatedTotal);
            setCouponMessage(data.message || 'Coupon applied successfully.', true);
        })
        .catch((error) => {
            discountRow.classList.add('hidden');
            discountRow.classList.remove('flex');
            finalTotal.textContent = checkoutCurrency(checkoutPricing.total);
            placeOrderTotal.textContent = checkoutCurrency(checkoutPricing.total);
            setCouponMessage(error.message || 'Coupon could not be applied.');
        })
        .finally(() => {
            applyCouponButton.disabled = false;
            applyCouponButton.textContent = 'Apply';
        });
}

couponInput?.addEventListener('input', () => {
    couponInput.value = couponInput.value.toUpperCase();
    updateCouponButton();
});

applyCouponButton?.addEventListener('click', applyCoupon);

document.querySelectorAll('.coupon-chip').forEach((button) => {
    button.addEventListener('click', () => {
        couponInput.value = button.dataset.couponCode || '';
        updateCouponButton();
        applyCoupon();
    });
});

updateCouponButton();

function sanitizeCheckoutPhone() {
    if (!phoneInput) {
        return;
    }

    phoneInput.value = phoneInput.value.replace(/\D/g, '').slice(0, 12);
}

phoneInput?.addEventListener('input', sanitizeCheckoutPhone);
sanitizeCheckoutPhone();
</script>
@endsection
