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

                <div class="overflow-hidden rounded-[24px] border border-blue-100 bg-white shadow-[0_18px_45px_rgba(30,64,175,0.08)]">
                    <div class="flex items-center justify-between gap-4 border-b border-blue-100 bg-gradient-to-r from-blue-50 via-white to-emerald-50 px-5 py-5 sm:px-6">
                        <div class="flex items-center gap-3">
                            <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-blue-700 text-sm font-black text-white shadow-lg shadow-blue-200">
                                <i class="fa-solid fa-address-card"></i>
                            </div>
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="text-[10px] font-black uppercase tracking-[0.18em] text-blue-600">Step 1</span>
                                    <span class="h-1 w-1 rounded-full bg-slate-300"></span>
                                    <span class="text-[10px] font-bold uppercase tracking-[0.14em] text-emerald-600">Account details</span>
                                </div>
                                <h2 class="nunito mt-1 text-lg font-extrabold text-slate-900">Contact Information</h2>
                            </div>
                        </div>
                        <div class="hidden h-9 w-9 items-center justify-center rounded-full border border-emerald-100 bg-white text-emerald-600 sm:flex">
                            <i class="fa-solid fa-shield-halved text-sm"></i>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 gap-5 px-5 py-6 sm:grid-cols-2 sm:px-6">
                        <div>
                            <label class="mb-2 block text-xs font-bold text-slate-700">First Name <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <i class="fa-regular fa-user absolute left-4 top-1/2 -translate-y-1/2 text-sm text-slate-400"></i>
                            <input type="text" name="first_name" value="{{ $checkoutDefaults['first_name'] ?? '' }}" required readonly
                                   class="w-full cursor-not-allowed rounded-2xl border border-slate-200 bg-slate-50 py-3.5 pl-11 pr-4 text-sm font-semibold text-slate-600 outline-none">
                            </div>
                        </div>
                        <div>
                            <label class="mb-2 block text-xs font-bold text-slate-700">Last Name</label>
                            <div class="relative">
                                <i class="fa-regular fa-user absolute left-4 top-1/2 -translate-y-1/2 text-sm text-slate-400"></i>
                            <input type="text" name="last_name" value="{{ $checkoutDefaults['last_name'] ?? '' }}" readonly
                                   class="w-full cursor-not-allowed rounded-2xl border border-slate-200 bg-slate-50 py-3.5 pl-11 pr-4 text-sm font-semibold text-slate-600 outline-none">
                            </div>
                        </div>
                        <div class="sm:col-span-2">
                            <label class="mb-2 block text-xs font-bold text-slate-700">Email Address <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <i class="fa-regular fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-sm text-slate-400"></i>
                            <input type="email" name="email" value="{{ $checkoutDefaults['email'] ?? '' }}" required readonly
                                   class="w-full cursor-not-allowed rounded-2xl border border-slate-200 bg-slate-50 py-3.5 pl-11 pr-4 text-sm font-semibold text-slate-600 outline-none">
                            </div>
                        </div>
                        <div class="sm:col-span-2">
                            <label class="mb-2 block text-xs font-bold text-slate-700">Phone Number <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <i class="fa-solid fa-phone absolute left-4 top-1/2 -translate-y-1/2 text-sm text-blue-500"></i>
                            <input type="tel" name="phone" id="checkoutPhoneInput" value="{{ $checkoutDefaults['phone'] ?? '' }}" required
                                   inputmode="numeric" pattern="(?:\d{10}|\d{12})" minlength="10" maxlength="12" autocomplete="off"
                                   title="Phone number must be 10 or 12 digits"
                                   class="w-full rounded-2xl border border-slate-200 bg-white py-3.5 pl-11 pr-4 text-sm font-semibold text-slate-800 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-50">
                            </div>
                            <p class="mt-2 flex items-center gap-1.5 text-[11px] font-medium text-slate-400">
                                <i class="fa-solid fa-circle-info text-blue-400"></i>
                                Delivery updates will be shared on this number.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="overflow-hidden rounded-2xl border bg-white">
                    <div class="flex items-center justify-between border-b px-6 py-4">
                        <div class="flex items-center gap-2">
                            <div class="flex h-7 w-7 items-center justify-center rounded-full bg-blue-700 text-xs font-bold text-white">2</div>
                            <h2 class="nunito text-base font-extrabold text-gray-800">Shipping Address</h2>
                        </div>
                        @if(!empty($pastAddresses))
                            <button type="button" onclick="showNewAddressForm()" id="addNewAddressBtn"
                                    class="text-xs font-bold text-blue-700 hover:text-blue-800 flex items-center gap-1">
                                <i class="fa-solid fa-plus text-[10px]"></i> Add New
                            </button>
                        @endif
                    </div>
                    <div class="px-6 py-5 space-y-4">
                        @if(!empty($pastAddresses))
                            <div id="savedAddressesContainer" class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-4">
                                @foreach($pastAddresses as $index => $addr)
                                    <div class="address-card border rounded-xl p-4 cursor-pointer relative hover:border-blue-500 transition-all duration-200 flex flex-col justify-between"
                                         data-index="{{ $index }}"
                                         onclick="selectAddressCard({{ $index }})">
                                         <div>
                                             <div class="flex justify-between items-start">
                                                 <p class="text-sm font-bold text-gray-800">{{ $addr['name'] ?? '' }}</p>
                                             </div>
                                             <p class="text-xs text-gray-500 mt-1.5 leading-relaxed">{{ $addr['address'] }}</p>
                                             <p class="text-xs text-gray-500 leading-relaxed">{{ $addr['city'] }}, {{ $addr['state'] }} - {{ $addr['pincode'] }}</p>
                                             @if(!empty($addr['phone']))
                                                 <p class="text-xs text-gray-500 mt-1 leading-relaxed"><i class="fa-solid fa-phone text-[9px] mr-1 text-slate-400"></i>{{ $addr['phone'] }}</p>
                                             @endif
                                         </div>
                                         
                                         <div class="mt-4 pt-3 border-t border-slate-50 flex items-center justify-between">
                                             <span class="text-[10px] font-bold text-green-700 select-badge items-center gap-1 hidden">
                                                 <i class="fa-solid fa-circle-check text-[10px]"></i> Selected
                                             </span>
                                             <button type="button" onclick="editAddressCard(event, {{ $index }})" 
                                                     class="text-xs text-blue-600 hover:text-blue-800 font-semibold flex items-center gap-1 ml-auto">
                                                 <i class="fa-regular fa-pen-to-square text-[10px]"></i> Edit
                                             </button>
                                         </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        {{-- Collapsible/Toggleable Address Input Fields --}}
                        <div id="addressFormContainer" class="space-y-4 pt-2">
                            @if(!empty($pastAddresses))
                                <div class="flex items-center justify-between mb-2">
                                    <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider" id="formActionTitle">Add New Address</h3>
                                    <button type="button" onclick="cancelAddressForm()" class="text-xs text-gray-400 hover:text-gray-600 font-medium">
                                        Cancel
                                    </button>
                                </div>
                            @endif

                            <div>
                                <label class="mb-1 block text-xs font-semibold text-gray-600">Street Address *</label>
                                <input type="text" name="address" id="shippingAddressInput" required
                                       class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm outline-none focus:border-blue-500">
                            </div>
                            <div class="grid gap-4 sm:grid-cols-2">
                                <div>
                                    <label class="mb-1 block text-xs font-semibold text-gray-600">City *</label>
                                    <input type="text" name="city" id="shippingCityInput" required
                                           class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm outline-none focus:border-blue-500">
                                </div>
                                <div>
                                    <label class="mb-1 block text-xs font-semibold text-gray-600">State *</label>
                                    <input type="text" name="state" id="shippingStateInput" required
                                           class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm outline-none focus:border-blue-500">
                                </div>
                            </div>
                            <div>
                                <label class="mb-1 block text-xs font-semibold text-gray-600">PIN Code *</label>
                                <input type="text" name="pincode" id="shippingPincodeInput" required
                                       class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm outline-none focus:border-blue-500">
                                </div>
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
                            <div class="mt-1 flex items-center gap-2">
                                <span class="text-[10px] text-gray-400">Qty: {{ $item['quantity'] }}</span>
                                <span class="inline-flex items-center rounded border px-1.5 py-0.5 text-[8px] font-extrabold uppercase tracking-wider {{ ($item['pricing_day'] ?? 'today') === 'tomorrow' ? 'bg-amber-50 text-amber-700 border-amber-100' : 'bg-emerald-50 text-emerald-700 border-emerald-100' }}">
                                    {{ ($item['pricing_day'] ?? 'today') === 'tomorrow' ? 'Tomorrow' : 'Today' }}
                                </span>
                            </div>
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

// Address Selection Logic
const pastAddresses = @json($pastAddresses ?? []);
const hasErrors = @json($errors->has('address') || $errors->has('city') || $errors->has('state') || $errors->has('pincode'));
let selectedAddressIndex = (pastAddresses.length > 0 && !hasErrors) ? 0 : null;

const addressCards = document.querySelectorAll('.address-card');
const formContainer = document.getElementById('addressFormContainer');
const formActionTitle = document.getElementById('formActionTitle');
const addNewBtn = document.getElementById('addNewAddressBtn');

const streetInput = document.getElementById('shippingAddressInput');
const cityInput = document.getElementById('shippingCityInput');
const stateInput = document.getElementById('shippingStateInput');
const pincodeInput = document.getElementById('shippingPincodeInput');

function updateAddressSelectionUI() {
    addressCards.forEach((card, idx) => {
        const isSelected = idx === selectedAddressIndex;
        card.classList.toggle('border-blue-600', isSelected);
        card.classList.toggle('bg-blue-50/20', isSelected);
        card.classList.toggle('border-gray-200', !isSelected);
        
        const badge = card.querySelector('.select-badge');
        if (badge) {
            badge.classList.toggle('hidden', !isSelected);
            badge.classList.toggle('inline-flex', isSelected);
        }
    });

    if (selectedAddressIndex !== null) {
        // A saved address is selected, hide the input form and populate values
        const addr = pastAddresses[selectedAddressIndex];
        streetInput.value = addr.address || '';
        cityInput.value = addr.city || '';
        stateInput.value = addr.state || '';
        pincodeInput.value = addr.pincode || '';
        
        formContainer.classList.add('hidden');
        addNewBtn?.classList.remove('hidden');
    } else {
        // Adding new / custom address, show form
        formContainer.classList.remove('hidden');
        addNewBtn?.classList.add('hidden');
    }
}

function selectAddressCard(index) {
    selectedAddressIndex = index;
    updateAddressSelectionUI();
}

function showNewAddressForm() {
    selectedAddressIndex = null;
    
    // Clear inputs (except name/phone which are prefilled in contact info)
    streetInput.value = '';
    cityInput.value = '';
    stateInput.value = '';
    pincodeInput.value = '';
    
    if (formActionTitle) {
        formActionTitle.textContent = 'Add New Address';
    }
    
    updateAddressSelectionUI();
    streetInput.focus();
}

function editAddressCard(event, index) {
    event.stopPropagation(); // Prevent card selection click event from firing
    selectedAddressIndex = null;
    
    // Fill inputs with address details
    const addr = pastAddresses[index];
    streetInput.value = addr.address || '';
    cityInput.value = addr.city || '';
    stateInput.value = addr.state || '';
    pincodeInput.value = addr.pincode || '';
    
    if (formActionTitle) {
        formActionTitle.textContent = 'Edit Address';
    }
    
    updateAddressSelectionUI();
    streetInput.focus();
}

function cancelAddressForm() {
    if (pastAddresses.length > 0) {
        selectedAddressIndex = 0;
        updateAddressSelectionUI();
    }
}

// Initial Call
if (pastAddresses.length > 0) {
    updateAddressSelectionUI();
} else {
    // If no past addresses, load defaults from checkoutDefaults if any
    streetInput.value = @json(old('address', $checkoutDefaults['address'] ?? ''));
    cityInput.value = @json(old('city', $checkoutDefaults['city'] ?? ''));
    stateInput.value = @json(old('state', $checkoutDefaults['state'] ?? ''));
    pincodeInput.value = @json(old('pincode', $checkoutDefaults['pincode'] ?? ''));
}
</script>
@endsection
