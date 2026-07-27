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

    $initialCouponCode = old('coupon_code', session('applied_coupon_' . session('checkout_delivery_day', 'today')));
@endphp
<div class="max-w-5xl mx-auto px-4 py-8">
    <h1 class="nunito font-extrabold text-2xl text-gray-800 mb-6">Checkout</h1>

    {{-- Noida Delivery Notice Banner --}}
    <div class="mb-6 overflow-hidden rounded-2xl border border-amber-300/60 bg-gradient-to-r from-slate-900 via-blue-950 to-slate-900 p-5 text-white shadow-xl relative">
        <div class="absolute -right-6 -top-6 h-28 w-28 rounded-full bg-amber-400/10 blur-xl"></div>
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 relative z-10">
            <div class="flex items-start gap-3.5">
                <div class="flex h-11 w-11 flex-shrink-0 items-center justify-center rounded-2xl bg-amber-400 text-slate-950 shadow-lg text-lg">
                    <i class="fa-solid fa-truck-fast"></i>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-400/20 px-2.5 py-0.5 text-[10px] font-black uppercase tracking-wider text-amber-300 border border-amber-400/30">
                            <span class="h-2 w-2 rounded-full bg-amber-400 animate-ping"></span>
                            Noida Delivery Only
                        </span>
                    </div>
                    <h3 class="mt-1 text-base font-extrabold text-white">Abhi Delivery Exclusively Noida Me Available Hai</h3>
                    <p class="mt-1 text-xs text-slate-300 leading-relaxed max-w-2xl">
                        Hum filhal <strong>Noida</strong> me hi fresh delivery kar rahe hain. Bohot hi jaldi aapke paas bhi apni nayi branch open karenge! Thank you for choosing FarmSea. 💚
                    </p>
                </div>
            </div>
            <div class="flex-shrink-0 self-end sm:self-center">
                <span class="inline-flex rounded-xl bg-white/10 px-3.5 py-2 text-xs font-bold text-amber-300 backdrop-blur-md border border-white/10">
                    🚀 Expanding Soon
                </span>
            </div>
        </div>
    </div>

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
                                             @if(!empty($addr['sector']))
                                                 <p class="text-xs text-gray-500 leading-relaxed">Sector: {{ $addr['sector'] }}</p>
                                             @endif
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
                                    <input type="text" name="city" id="shippingCityInput" value="Noida" required readonly
                                           class="w-full cursor-not-allowed rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm font-semibold text-gray-700 outline-none">
                                </div>
                                <div>
                                    <label class="mb-1 block text-xs font-semibold text-gray-600">State *</label>
                                    <input type="text" name="state" id="shippingStateInput" value="UP" required readonly
                                           class="w-full cursor-not-allowed rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm font-semibold text-gray-700 outline-none">
                                </div>
                            </div>
                            <div class="grid gap-4 sm:grid-cols-2">
                                <div>
                                    <label class="mb-1 block text-xs font-semibold text-gray-600">PIN Code *</label>
                                    <select name="pincode" id="shippingPincodeInput" required
                                            class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm outline-none focus:border-blue-500">
                                        <option value="">Select PIN Code</option>
                                        @foreach($pinSectors as $pinCode => $sectors)
                                            <option value="{{ $pinCode }}">{{ $pinCode }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="mb-1 block text-xs font-semibold text-gray-600">Sector *</label>
                                    <select name="sector" id="shippingSectorInput" required
                                            class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm outline-none focus:border-blue-500">
                                        <option value="">Select PIN Code First</option>
                                    </select>
                                </div>
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
                        <span class="text-gray-500">&#8505;&#65039; Service Charge</span>
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
if (couponInput?.value.trim()) {
    applyCoupon();
}

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
const hasErrors = @json($errors->has('address') || $errors->has('city') || $errors->has('state') || $errors->has('pincode') || $errors->has('sector'));
let selectedAddressIndex = (pastAddresses.length > 0 && !hasErrors) ? 0 : null;

const addressCards = document.querySelectorAll('.address-card');
const formContainer = document.getElementById('addressFormContainer');
const formActionTitle = document.getElementById('formActionTitle');
const addNewBtn = document.getElementById('addNewAddressBtn');

const streetInput = document.getElementById('shippingAddressInput');
const cityInput = document.getElementById('shippingCityInput');
const stateInput = document.getElementById('shippingStateInput');
const pincodeInput = document.getElementById('shippingPincodeInput');
const sectorInput = document.getElementById('shippingSectorInput');

const pinToSectors = @json($pinSectors);

function populateSectors(pincode) {
    if (!sectorInput) return;
    sectorInput.innerHTML = '<option value="">Select Sector</option>';

    if (!pincode || !pinToSectors[pincode]) {
        sectorInput.innerHTML = '<option value="">Select PIN Code First</option>';
        return;
    }

    const sectors = pinToSectors[pincode];
    sectors.forEach(sector => {
        const opt = document.createElement('option');
        opt.value = sector;
        opt.textContent = sector;
        sectorInput.appendChild(opt);
    });
}

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
        populateSectors(addr.pincode || '');
        sectorInput.value = addr.sector || '';
        
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
    cityInput.value = 'Noida';
    stateInput.value = 'UP';
    pincodeInput.value = '';
    populateSectors('');
    sectorInput.value = '';
    
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
    cityInput.value = addr.city || 'Noida';
    stateInput.value = addr.state || 'UP';
    pincodeInput.value = addr.pincode || '';
    populateSectors(addr.pincode || '');
    sectorInput.value = addr.sector || '';
    
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

// Pincode change event listener for dynamic sectors and City/State population
pincodeInput?.addEventListener('change', (e) => {
    const pin = e.target.value;
    populateSectors(pin);
    cityInput.value = 'Noida';
    stateInput.value = 'UP';
});

// Initial Call
if (pastAddresses.length > 0) {
    updateAddressSelectionUI();
} else {
    // If no past addresses, load defaults
    streetInput.value = @json(old('address', $checkoutDefaults['address'] ?? ''));
    cityInput.value = 'Noida';
    stateInput.value = 'UP';
    const defaultPin = @json(old('pincode', $checkoutDefaults['pincode'] ?? ''));
    pincodeInput.value = defaultPin;
    populateSectors(defaultPin);
    sectorInput.value = @json(old('sector', $checkoutDefaults['sector'] ?? ''));
}
</script>
@endsection
