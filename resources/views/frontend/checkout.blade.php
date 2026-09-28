@extends('frontend.layouts.app')
@section('title', 'Checkout')

@section('styles')
<style>
@media (max-width: 639px) {
    .checkout-page h1 { font-size: 1.2rem !important; line-height: 1.45 !important; }
    .checkout-page h2 { font-size: .88rem !important; line-height: 1.4 !important; }
    .checkout-page h3 { font-size: .76rem !important; line-height: 1.4 !important; }
}
@media (min-width: 1024px) {
    .checkout-summary-sticky {
        position: -webkit-sticky;
        position: sticky;
        top: 130px;
        z-index: 20;
    }
}
</style>
@endsection

@section('content')
@php
    $isCodAvailable = ($pricing['subtotal'] ?? 0) <= 2000;
    $paymentOptions = $isCodAvailable
        ? ['COD' => 'Cash on Delivery', 'online' => 'Online / UPI Payment']
        : ['online' => 'Online / UPI Payment'];
    $selectedPaymentMethod = old('payment_method', $isCodAvailable ? 'COD' : 'online');

    if (! array_key_exists($selectedPaymentMethod, $paymentOptions)) {
        $selectedPaymentMethod = array_key_first($paymentOptions);
    }

    // Resolve session coupon for checkout — only proper coupons, not offers
    $checkoutDay = session('checkout_delivery_day', 'today');
    $sessionCouponCode = session('applied_coupon_' . $checkoutDay);
    $checkoutAppliedCoupon = null;
    $checkoutDiscount = 0.0;

    if ($sessionCouponCode) {
        $_c = \App\Models\Coupon::valid()->where('code', $sessionCouponCode)->first();
        $hasEntryType = \Illuminate\Support\Facades\Schema::hasColumn('coupons', 'entry_type');
        // Skip offers on checkout (entry_type = 'offer' OR AUTO-OFFER- prefix)
        $_isOffer = $_c && (
            ($hasEntryType && $_c->entry_type === 'offer') ||
            \Illuminate\Support\Str::startsWith($sessionCouponCode, 'AUTO-OFFER-')
        );
        if ($_c && !$_isOffer) {
            $subtotalForDiscount = (float) ($pricing['subtotal'] ?? 0);
            if (!is_null($_c->product_id)) {
                $applicableBase = (float) collect($items)
                    ->where('product_id', $_c->product_id)
                    ->sum('subtotal');
            } else {
                $applicableBase = $subtotalForDiscount;
            }
            if ($applicableBase > 0) {
                $checkoutDiscount = $_c->type === 'percent'
                    ? round($applicableBase * $_c->value / 100, 2)
                    : min((float) $_c->value, $applicableBase);
                $checkoutAppliedCoupon = $_c;
            }
        }
    }

    $initialCouponCode = old('coupon_code', $checkoutAppliedCoupon?->code);
    $checkoutTotalAfterDiscount = max(0, (float)($pricing['total'] ?? 0) - $checkoutDiscount);
@endphp
<div class="checkout-page max-w-5xl mx-auto px-4 py-8">
    <h1 class="nunito font-extrabold text-2xl text-gray-800 mb-6">Checkout</h1>

    <div id="checkoutGrid" class="grid grid-cols-1 gap-6 lg:grid-cols-[minmax(0,1fr)_20rem]">
        <div class="flex-1 space-y-5">
            <form method="POST" action="{{ route('frontend.checkout.store') }}" id="checkoutForm">
                @csrf
                <input type="hidden" name="delivery_slot" id="checkoutDeliverySlotInput" value="{{ $selectedDeliverySlot }}">
                <input type="hidden" name="delivery_date" id="checkoutDeliveryDateInput" value="{{ $selectedDeliveryDate }}">

                @if($errors->any())
                <div class="rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-600">
                    @foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach
                </div>
                @endif

                <div class="overflow-hidden rounded-2xl border bg-white">
                    <div class="flex items-center gap-2 border-b px-6 py-4">
                        <div class="flex h-7 w-7 items-center justify-center rounded-full bg-neutral-800 text-xs font-bold text-white"><i class="fa-regular fa-clock text-[11px]"></i></div>
                        <h2 class="nunito text-base font-extrabold text-gray-800">Delivery Timing</h2>
                    </div>
                    <div class="space-y-4 px-6 py-5">
                        <div>
                            <p class="mb-2 text-xs font-bold uppercase tracking-[0.12em] text-gray-500">Choose a delivery date</p>
                            <div class="flex flex-wrap gap-2" id="checkoutDeliveryDateTabs">
                                @forelse($upcomingDates as $day)
                                    <button type="button" onclick="selectCheckoutDeliveryDate('{{ $day['date'] }}')"
                                            data-checkout-delivery-date="{{ $day['date'] }}"
                                            class="checkout-delivery-date-chip {{ $selectedDeliveryDate === $day['date'] ? 'border-neutral-800 bg-neutral-800 text-white' : 'border-gray-200 bg-white text-gray-600' }} rounded-xl border px-3.5 py-2.5 text-xs font-bold transition hover:border-neutral-500">
                                        {{ $day['date_label'] }}
                                    </button>
                                @empty
                                    <span class="text-xs font-semibold text-gray-400">No delivery slots configured yet.</span>
                                @endforelse
                            </div>
                        </div>
                        <div>
                            <p class="mb-2 text-xs font-bold uppercase tracking-[0.12em] text-gray-500">Choose a time slot</p>
                            <div class="flex flex-wrap gap-2" id="checkoutDeliverySlotTabs">
                                @forelse($deliverySlotOptions as $slot)
                                    <button type="button" onclick="selectCheckoutDeliverySlot('{{ $slot['value'] }}')"
                                            data-checkout-delivery-slot="{{ $slot['value'] }}"
                                            class="checkout-delivery-slot-chip {{ $selectedDeliverySlot === $slot['value'] ? 'border-amber-600 bg-amber-50 text-amber-700' : 'border-gray-200 bg-white text-gray-600' }} rounded-xl border px-3.5 py-2.5 text-xs font-bold transition hover:border-amber-400">
                                        {{ $slot['label'] }}
                                    </button>
                                @empty
                                    <span class="rounded-xl border border-amber-200 bg-amber-50 px-3.5 py-2.5 text-xs font-bold text-amber-700">No slots left for this date</span>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

                <div class="overflow-hidden rounded-2xl border bg-white">
                    <button type="button" id="contactInfoToggle" class="w-full flex items-center justify-between border-b px-6 py-4 hover:bg-gray-50 transition">
                        <div class="flex items-center gap-2">
                            <div class="flex h-7 w-7 items-center justify-center rounded-full bg-neutral-800 text-xs font-bold text-white">1</div>
                            <h2 class="nunito text-base font-extrabold text-gray-800">Contact Information</h2>
                        </div>
                        <i class="fa-solid fa-chevron-down text-xs text-gray-400 transition-transform" id="contactInfoChevron"></i>
                    </button>

                    {{-- Hidden inputs so form still submits name/email --}}
                    <input type="hidden" name="first_name" value="{{ $checkoutDefaults['first_name'] ?? '' }}">
                    <input type="hidden" name="last_name"  value="{{ $checkoutDefaults['last_name']  ?? '' }}">
                    <input type="hidden" name="email"      value="{{ $checkoutDefaults['email']      ?? '' }}">

                    <div id="contactInfoContent" class="px-6 py-5 space-y-4 hidden">
                        {{-- Name & Email as styled read-only labels --}}
                        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                            <div>
                                <p class="text-xs font-semibold text-gray-400 mb-0.5">First Name</p>
                                <p class="text-sm font-bold text-gray-800">{{ $checkoutDefaults['first_name'] ?? '—' }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-gray-400 mb-0.5">Last Name</p>
                                <p class="text-sm font-bold text-gray-800">{{ $checkoutDefaults['last_name'] ?: '—' }}</p>
                            </div>
                        </div>

                        <div>
                            <p class="text-xs font-semibold text-gray-400 mb-0.5">Email</p>
                            <p class="text-sm font-bold text-gray-800">{{ $checkoutDefaults['email'] ?? '—' }}</p>
                        </div>

                        <hr class="border-gray-100">
                    </div>

                    {{-- Phone: always visible, exactly 10 digits --}}
                    <div class="px-6 py-4">
                        <label for="checkoutPhoneInput" class="mb-1 block text-xs font-semibold text-gray-600">
                            Phone <span class="text-red-500">*</span>
                            <span class="ml-1 font-normal text-gray-400">(10 digit mobile number)</span>
                        </label>
                        <input type="tel"
                               name="phone"
                               id="checkoutPhoneInput"
                               value="{{ $checkoutDefaults['phone'] ?? '' }}"
                               required
                               inputmode="numeric"
                               pattern="\d{10}"
                               minlength="10"
                               maxlength="10"
                               autocomplete="tel"
                               placeholder="Enter 10-digit mobile number"
                               title="Enter exactly 10-digit mobile number"
                               oninput="this.value = this.value.replace(/\D/g, '').slice(0, 10)"
                               class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm outline-none focus:border-neutral-600 focus:ring-1 focus:ring-amber-100">
                        <p class="mt-1 text-xs text-gray-400" id="phoneHint">
                            <span id="phoneCounter">{{ strlen($checkoutDefaults['phone'] ?? '') }}</span>/10 digits entered
                        </p>
                    </div>
                </div>

                <div class="overflow-hidden rounded-2xl border bg-white">
                    <div class="flex items-center justify-between border-b px-6 py-4">
                        <div class="flex items-center gap-2">
                            <div class="flex h-7 w-7 items-center justify-center rounded-full bg-neutral-800 text-xs font-bold text-white">2</div>
                            <h2 class="nunito text-base font-extrabold text-gray-800">Shipping Address</h2>
                        </div>
                        @if(!empty($pastAddresses))
                            <button type="button" onclick="showNewAddressForm()" id="addNewAddressBtn"
                                    class="text-xs font-bold text-amber-700 hover:text-amber-800 flex items-center gap-1">
                                <i class="fa-solid fa-plus text-[10px]"></i> Add New
                            </button>
                        @endif
                    </div>
                    <div class="px-6 py-5 space-y-3">
                        @if(!empty($pastAddresses))
                            <div id="savedAddressesContainer" class="space-y-2 mb-4">
                                @foreach($pastAddresses as $index => $addr)
                                    <div class="address-card border rounded-xl p-3 cursor-pointer relative hover:border-neutral-600 transition-all duration-200 flex items-center justify-between gap-3"
                                         data-index="{{ $index }}"
                                         onclick="selectAddressCard({{ $index }})">
                                         <div class="min-w-0 flex-1">
                                             <div class="flex items-center justify-between gap-2">
                                                 <p class="text-sm font-bold text-gray-800 truncate">{{ $addr['name'] ?? '' }}</p>
                                                 <span class="text-[10px] font-bold text-amber-700 select-badge items-center gap-1 hidden flex-shrink-0">
                                                     <i class="fa-solid fa-circle-check text-[10px]"></i> Selected
                                                 </span>
                                             </div>
                                             <p class="text-xs text-gray-500 mt-1 line-clamp-1">{{ $addr['address'] }} @if(!empty($addr['sector']))• Sector: {{ $addr['sector'] }}@endif</p>
                                             <p class="text-xs text-gray-500 line-clamp-1">{{ $addr['city'] }}, {{ $addr['state'] }} - {{ $addr['pincode'] }}@if(!empty($addr['phone'])) • {{ $addr['phone'] }}@endif</p>
                                         </div>
                                         <button type="button" onclick="editAddressCard(event, {{ $index }})" 
                                                 class="text-xs text-amber-600 hover:text-amber-800 font-semibold flex items-center gap-1 flex-shrink-0">
                                                 <i class="fa-regular fa-pen-to-square text-[10px]"></i>
                                         </button>
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
                                       class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm outline-none focus:border-neutral-600">
                            </div>
                            <div class="grid gap-4 sm:grid-cols-2">
                                <div>
                                    <label class="mb-1 block text-xs font-semibold text-gray-600">City *</label>
                                    <input type="text" name="city" id="shippingCityInput" required
                                           class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm outline-none focus:border-neutral-600">
                                </div>
                                <div>
                                    <label class="mb-1 block text-xs font-semibold text-gray-600">State *</label>
                                    <input type="text" name="state" id="shippingStateInput" required
                                           class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm outline-none focus:border-neutral-600">
                                </div>
                            </div>
                            <div class="grid gap-4 sm:grid-cols-2">
                                <div>
                                    <label class="mb-1 block text-xs font-semibold text-gray-600">Area / Sector</label>
                                    <input type="text" name="sector" id="shippingSectorInput" placeholder="e.g. Sangam Vihar"
                                           class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm outline-none focus:border-neutral-600">
                                </div>
                                <div>
                                    <label class="mb-1 block text-xs font-semibold text-gray-600">PIN Code *</label>
                                    <input type="text" name="pincode" id="shippingPincodeInput" required maxlength="6" inputmode="numeric" placeholder="110080"
                                           class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm font-semibold text-gray-700 outline-none focus:border-neutral-600">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="overflow-hidden rounded-2xl border bg-white">
                    <div class="flex items-center gap-2 border-b px-6 py-4">
                        <div class="flex h-7 w-7 items-center justify-center rounded-full bg-neutral-800 text-xs font-bold text-white">3</div>
                        <h2 class="nunito text-base font-extrabold text-gray-800">Payment Method</h2>
                    </div>
                    <div class="space-y-3 px-6 py-5">
                        @unless($isCodAvailable)
                            <div class="rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-xs font-semibold text-amber-700">
                                Cash on Delivery is available only for orders up to &#8377;2,000.
                            </div>
                        @endunless
                        @foreach($paymentOptions as $val => $label)
                        <label class="flex cursor-pointer items-center gap-3 rounded-xl border border-gray-200 p-4 transition hover:border-neutral-600">
                            <input type="radio" name="payment_method" value="{{ $val }}" {{ $selectedPaymentMethod === $val ? 'checked' : '' }} class="accent-amber-600">
                            <span class="text-sm font-semibold text-gray-700">{{ $label }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <div id="checkoutCouponSection" class="rounded-2xl border bg-white p-4 sm:p-5">
                    <label class="mb-2 block text-xs font-semibold text-gray-600">Coupon Code (optional)</label>
                    <div class="flex gap-2">
                        <input type="text" name="coupon_code" id="couponCodeInput" value="{{ $initialCouponCode }}" placeholder="Enter coupon code"
                               class="min-w-0 flex-1 rounded-xl border border-gray-200 px-3 py-2.5 text-sm uppercase outline-none focus:border-neutral-600 sm:px-4">
                        <button type="button" id="applyCouponButton"
                                class="{{ $initialCouponCode ? '' : 'hidden' }} flex-shrink-0 rounded-xl bg-amber-600 px-4 py-2.5 text-sm font-bold text-white transition hover:bg-amber-700 sm:px-5">
                            Apply
                        </button>
                    </div>
                    <p id="couponMessage" class="mt-2 hidden text-xs font-semibold"></p>
                    @if(($availableCoupons ?? collect())->isNotEmpty())
                        <p class="mt-3 text-[10px] font-bold uppercase tracking-wider text-gray-400">Available Coupons</p>
                        <div class="mt-2 flex flex-wrap gap-1.5 sm:gap-2">
                            @foreach($availableCoupons as $coupon)
                                <button type="button"
                                        data-coupon-code="{{ $coupon->code }}"
                                        class="coupon-chip rounded-full border border-amber-200 bg-amber-50 px-2.5 py-1 text-[10px] font-bold text-amber-700 transition hover:border-amber-500 hover:bg-white sm:px-3 sm:py-1.5 sm:text-[11px]">
                                    {{ $coupon->code }}
                                </button>
                            @endforeach
                        </div>
                    @endif
                </div>

            </form>
        </div>

        <aside id="checkoutSideColumn" class="w-full lg:self-stretch">
            {{-- Order Summary --}}
            <div id="checkoutOrderSummary" class="checkout-summary-sticky overflow-hidden rounded-2xl border bg-white shadow-sm">
                <div class="h-1 bg-gradient-to-r from-amber-700 to-amber-400"></div>
                <div class="border-b px-4 py-3">
                    <h2 class="nunito text-sm font-extrabold text-gray-800">Order Summary</h2>
                </div>
                <div class="max-h-44 space-y-2.5 overflow-y-auto border-b px-4 py-3">
                    @foreach($items as $item)
                    <div class="flex items-center gap-2.5">
                        <div class="flex h-8 w-8 flex-shrink-0 items-center justify-center overflow-hidden rounded-lg bg-gray-100">
                            @if($item['image'])
                                <img src="{{ asset('storage/' . $item['image']) }}" class="h-full w-full object-cover" alt="{{ $item['name'] }}">
                            @else
                                <i class="fa-solid fa-basket-shopping text-xs text-gray-400"></i>
                            @endif
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="truncate text-[11px] font-semibold text-gray-800">{{ $item['name'] }}</p>
                            <span class="text-[9px] text-gray-400">Qty: {{ $item['quantity'] }}</span>
                        </div>
                        <span class="flex-shrink-0 text-[11px] font-bold text-gray-800">&#8377;{{ number_format($item['subtotal'], 2) }}</span>
                    </div>
                    @endforeach
                </div>
                <div class="space-y-1.5 px-4 py-3 text-xs">
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
                    <div id="checkoutDiscountRow" class="{{ $checkoutDiscount > 0 ? 'flex' : 'hidden' }} justify-between text-amber-600">
                        <span>Discount</span>
                        <span id="checkoutDiscountAmount">-&#8377;{{ number_format($checkoutDiscount, 2) }}</span>
                    </div>
                    <hr class="border-gray-100">
                    <div class="flex justify-between text-sm font-bold text-gray-800">
                        <span>Total</span>
                        <span id="checkoutFinalTotal" class="text-amber-700">&#8377;{{ number_format($checkoutTotalAfterDiscount, 2) }}</span>
                    </div>
                </div>
                <div class="px-4 pb-4">
                    <button type="submit" form="checkoutForm" id="placeOrderButton"
                            class="flex w-full items-center justify-center gap-2 rounded-xl bg-neutral-800 py-3 text-xs font-bold text-white transition hover:bg-black">
                        <i class="fa-solid fa-lock text-xs"></i>
                        Place Order - <span id="placeOrderTotal">&#8377;{{ number_format($checkoutTotalAfterDiscount, 2) }}</span>
                    </button>
                </div>
            </div>

            <div id="checkoutPromoCard" class="group relative mt-6 overflow-hidden rounded-[24px] border border-emerald-100 bg-white shadow-md">
                <img src="{{ asset('images/checkout/fresh-delivery-banner.png') }}" alt="Fresh meat and seafood packed for delivery" class="h-48 w-full object-cover transition duration-700 group-hover:scale-105">
                <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-slate-950/90 via-slate-900/55 to-transparent px-5 pb-4 pt-12 text-white">
                    <p class="text-[10px] font-black uppercase tracking-[0.2em] text-emerald-300">Porville Fresh Promise</p>
                    <h3 class="mt-1 text-lg font-black">Freshness packed with care</h3>
                    <p class="mt-1 text-[11px] font-semibold text-slate-200">Temperature-controlled packing &amp; safe doorstep delivery.</p>
                </div>
            </div>

            @if(($checkoutNewArrivals ?? collect())->isNotEmpty())
                <div id="checkoutNewArrivals" class="mt-6 rounded-[22px] border border-slate-200 bg-white p-3 shadow-sm">
                    <div class="mb-3 flex items-center justify-between px-1">
                        <div>
                            <p class="text-[9px] font-black uppercase tracking-[0.18em] text-amber-600">Just In</p>
                            <h3 class="text-sm font-black text-slate-900">New Arrivals</h3>
                        </div>
                        <a href="{{ route('frontend.products', ['sort' => 'latest']) }}" class="text-[9px] font-black uppercase tracking-wider text-amber-600 hover:underline">View All</a>
                    </div>

                    <div class="space-y-2.5">
                        @foreach($checkoutNewArrivals as $arrival)
                            @php
                                $arrivalVariantIndex = collect($arrival->variants ?? [])->search(
                                    fn ($variant) => filled($variant['selling_price'] ?? null) || filled($variant['today_price'] ?? null)
                                );
                                $arrivalVariantIndex = $arrivalVariantIndex === false ? null : $arrivalVariantIndex;
                                $arrivalImage = $arrival->images && count($arrival->images) ? asset('storage/' . $arrival->images[0]) : null;
                            @endphp
                            <article class="group/item flex items-center gap-3 rounded-2xl border border-slate-100 bg-slate-50/70 p-2 transition hover:border-amber-200 hover:bg-amber-50/50">
                                <a href="{{ route('frontend.product.show', $arrival->slug) }}" class="h-14 w-14 flex-shrink-0 overflow-hidden rounded-xl bg-white">
                                    @if($arrivalImage)
                                        <img src="{{ $arrivalImage }}" alt="{{ $arrival->name }}" class="h-full w-full object-cover transition duration-300 group-hover/item:scale-105">
                                    @else
                                        <span class="flex h-full w-full items-center justify-center text-slate-300"><i class="fa-solid fa-basket-shopping"></i></span>
                                    @endif
                                </a>
                                <div class="min-w-0 flex-1">
                                    <p class="truncate text-[9px] font-black uppercase tracking-wider text-emerald-600">{{ $arrival->category->name ?? 'Fresh' }}</p>
                                    <a href="{{ route('frontend.product.show', $arrival->slug) }}" class="mt-0.5 block truncate text-[11px] font-black text-slate-900 hover:text-amber-700">{{ $arrival->name }}</a>
                                    <p class="mt-1 text-xs font-black text-slate-900">Rs{{ number_format($arrival->display_price, 0) }} <span class="text-[9px] font-semibold text-slate-400">{{ $arrival->display_pack_label }}</span></p>
                                </div>
                                <button type="button" onclick="addToCart({{ $arrival->id }}, {{ $arrivalVariantIndex === null ? 'null' : $arrivalVariantIndex }}, 'today')" aria-label="Add {{ $arrival->name }} to cart" title="Add to cart" class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-xl bg-amber-700 text-white shadow-sm transition hover:bg-amber-800">
                                    <i class="fa-solid fa-cart-plus text-xs"></i>
                                </button>
                            </article>
                        @endforeach
                    </div>
                </div>
            @endif

            <a id="checkoutMealBanner" href="{{ route('frontend.products') }}" class="group relative mt-6 block overflow-hidden rounded-[22px] border border-amber-100 bg-white shadow-md">
                <img src="{{ asset('images/checkout/ready-to-cook-meal-banner.png') }}" alt="Fresh ready-to-cook fish and chicken meal" class="h-40 w-full object-cover transition duration-700 group-hover:scale-105">
                <div class="absolute inset-0 bg-gradient-to-r from-slate-950/80 via-slate-900/30 to-transparent"></div>
                <div class="absolute inset-y-0 left-0 flex max-w-[72%] flex-col justify-center px-4 text-white">
                    <p class="text-[9px] font-black uppercase tracking-[0.18em] text-amber-300">Cook Something Fresh</p>
                    <h3 class="mt-1 text-base font-black leading-tight">Dinner inspiration, delivered fresh</h3>
                    <span class="mt-3 inline-flex w-fit items-center gap-1.5 rounded-lg bg-white/95 px-3 py-1.5 text-[9px] font-black uppercase tracking-wider text-slate-900">Shop Now <i class="fa-solid fa-arrow-right"></i></span>
                </div>
            </a>

            <div id="checkoutTrustCard" class="mt-6 overflow-hidden rounded-[22px] border border-emerald-100 bg-white shadow-sm">
                <div class="bg-gradient-to-r from-emerald-700 to-emerald-500 px-4 py-3 text-white">
                    <p class="text-[9px] font-black uppercase tracking-[0.18em] text-emerald-100">Shop With Confidence</p>
                    <h3 class="mt-0.5 text-sm font-black">Why customers trust Porville</h3>
                </div>
                <div class="divide-y divide-slate-100 px-3">
                    <div class="flex items-center gap-3 py-3">
                        <span class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600"><i class="fa-solid fa-shield-halved text-xs"></i></span>
                        <div><p class="text-[11px] font-black text-slate-900">Safe &amp; secure checkout</p><p class="mt-0.5 text-[9px] font-semibold text-slate-400">Your order details stay protected</p></div>
                    </div>
                    <div class="flex items-center gap-3 py-3">
                        <span class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-xl bg-amber-50 text-amber-600"><i class="fa-solid fa-headset text-xs"></i></span>
                        <div class="min-w-0 flex-1"><p class="text-[11px] font-black text-slate-900">Need checkout help?</p><p class="mt-0.5 text-[9px] font-semibold text-slate-400">Our support team is ready</p></div>
                        <a href="{{ route('frontend.contact') }}" class="rounded-lg bg-slate-900 px-2.5 py-1.5 text-[8px] font-black uppercase tracking-wider text-white transition hover:bg-emerald-700">Get Help</a>
                    </div>
                </div>
            </div>

            @if(($checkoutSimilarProducts ?? collect())->isNotEmpty())
                <div id="checkoutSimilarProducts" class="mt-6 rounded-[22px] border border-violet-100 bg-white p-3 shadow-sm">
                    <div class="mb-3 flex items-center justify-between px-1">
                        <div>
                            <p class="text-[9px] font-black uppercase tracking-[0.18em] text-violet-600">You May Also Like</p>
                            <h3 class="text-sm font-black text-slate-900">Similar Products</h3>
                        </div>
                        <a href="{{ route('frontend.products') }}" class="text-[9px] font-black uppercase tracking-wider text-violet-600 hover:underline">View All</a>
                    </div>

                    <div class="space-y-2.5">
                        @foreach($checkoutSimilarProducts as $similarProduct)
                            @php
                                $similarVariantIndex = collect($similarProduct->variants ?? [])->search(
                                    fn ($variant) => filled($variant['selling_price'] ?? null) || filled($variant['today_price'] ?? null)
                                );
                                $similarVariantIndex = $similarVariantIndex === false ? null : $similarVariantIndex;
                                $similarImage = $similarProduct->images && count($similarProduct->images) ? asset('storage/' . $similarProduct->images[0]) : null;
                            @endphp
                            <article class="group/similar flex items-center gap-3 rounded-2xl border border-violet-50 bg-violet-50/45 p-2 transition hover:border-violet-200 hover:bg-violet-50">
                                <a href="{{ route('frontend.product.show', $similarProduct->slug) }}" class="h-14 w-14 flex-shrink-0 overflow-hidden rounded-xl bg-white">
                                    @if($similarImage)
                                        <img src="{{ $similarImage }}" alt="{{ $similarProduct->name }}" class="h-full w-full object-cover transition duration-300 group-hover/similar:scale-105">
                                    @else
                                        <span class="flex h-full w-full items-center justify-center text-violet-200"><i class="fa-solid fa-basket-shopping"></i></span>
                                    @endif
                                </a>
                                <div class="min-w-0 flex-1">
                                    <p class="truncate text-[9px] font-black uppercase tracking-wider text-violet-600">{{ $similarProduct->category->name ?? 'Recommended' }}</p>
                                    <a href="{{ route('frontend.product.show', $similarProduct->slug) }}" class="mt-0.5 block truncate text-[11px] font-black text-slate-900 hover:text-violet-700">{{ $similarProduct->name }}</a>
                                    <p class="mt-1 text-xs font-black text-slate-900">Rs{{ number_format($similarProduct->display_price, 0) }} <span class="text-[9px] font-semibold text-slate-400">{{ $similarProduct->display_pack_label }}</span></p>
                                </div>
                                <button type="button" onclick="addToCart({{ $similarProduct->id }}, {{ $similarVariantIndex === null ? 'null' : $similarVariantIndex }}, 'today')" aria-label="Add {{ $similarProduct->name }} to cart" title="Add to cart" class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-xl bg-violet-600 text-white shadow-sm transition hover:bg-violet-700">
                                    <i class="fa-solid fa-cart-plus text-xs"></i>
                                </button>
                            </article>
                        @endforeach
                    </div>
                </div>
            @endif


        </aside>
    </div>
</div>

<script>
// Order Summary is now part of the main form (not sticky sidebar)

const checkoutPricing = {
    subtotal: Number(@json((float) ($pricing['subtotal'] ?? 0))),
    total: Number(@json((float) ($pricing['total'] ?? 0))),
};

// Server-side applied coupon (from session) — already shown in order summary
const checkoutServerDiscount = Number(@json((float) $checkoutDiscount));
const checkoutServerTotal = Number(@json((float) $checkoutTotalAfterDiscount));

// Full cart items for accurate per-product discount preview
const checkoutCartItems = @json(collect($items)->map(fn ($i) => ['product_id' => $i['product_id'], 'price' => $i['price'], 'quantity' => $i['quantity']])->values());

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

function selectCheckoutDeliveryDate(date) {
    fetch('{{ route("frontend.cart.delivery-date") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
        body: JSON.stringify({ delivery_date: date })
    }).then(r => r.json()).then(d => {
        if (!d.success) return;

        document.getElementById('checkoutDeliveryDateInput').value = date;
        document.querySelectorAll('[data-checkout-delivery-date]').forEach((chip) => {
            const isActive = chip.dataset.checkoutDeliveryDate === date;
            chip.classList.toggle('border-neutral-800', isActive);
            chip.classList.toggle('bg-neutral-800', isActive);
            chip.classList.toggle('text-white', isActive);
            chip.classList.toggle('border-gray-200', !isActive);
            chip.classList.toggle('bg-white', !isActive);
            chip.classList.toggle('text-gray-600', !isActive);
        });

        const container = document.getElementById('checkoutDeliverySlotTabs');
        if (container) {
            if (!d.options.length) {
                container.innerHTML = '<span class="rounded-xl border border-amber-200 bg-amber-50 px-3.5 py-2.5 text-xs font-bold text-amber-700">No slots left for this date</span>';
            } else {
                container.innerHTML = d.options.map((slot) => `
                    <button type="button" onclick="selectCheckoutDeliverySlot('${slot.value}')" data-checkout-delivery-slot="${slot.value}"
                            class="checkout-delivery-slot-chip border-gray-200 bg-white text-gray-600 rounded-xl border px-3.5 py-2.5 text-xs font-bold transition hover:border-amber-400">
                        ${slot.label}
                    </button>
                `).join('');
            }
        }

        document.getElementById('checkoutDeliverySlotInput').value = d.selected_delivery_slot || '';
    });
}

function selectCheckoutDeliverySlot(value) {
    document.getElementById('checkoutDeliverySlotInput').value = value;
    document.querySelectorAll('[data-checkout-delivery-slot]').forEach((chip) => {
        const isActive = chip.dataset.checkoutDeliverySlot === value;
        chip.classList.toggle('border-amber-600', isActive);
        chip.classList.toggle('bg-amber-50', isActive);
        chip.classList.toggle('text-amber-700', isActive);
        chip.classList.toggle('border-gray-200', !isActive);
        chip.classList.toggle('bg-white', !isActive);
        chip.classList.toggle('text-gray-600', !isActive);
    });
}

function setCouponMessage(message, isSuccess = false) {
    couponMessage.textContent = message;
    couponMessage.classList.remove('hidden', 'text-amber-600', 'text-red-600');
    couponMessage.classList.add(isSuccess ? 'text-amber-600' : 'text-red-600');
}

function updateCouponButton() {
    const hasCode = couponInput.value.trim().length > 0;
    applyCouponButton.classList.toggle('hidden', !hasCode);

    if (!hasCode) {
        couponMessage.classList.add('hidden');
        // Restore server-computed discount if available, else zero
        if (checkoutServerDiscount > 0) {
            discountAmount.textContent = '-' + checkoutCurrency(checkoutServerDiscount);
            discountRow.classList.remove('hidden');
            discountRow.classList.add('flex');
            finalTotal.textContent = checkoutCurrency(checkoutServerTotal);
            placeOrderTotal.textContent = checkoutCurrency(checkoutServerTotal);
        } else {
            discountRow.classList.add('hidden');
            discountRow.classList.remove('flex');
            finalTotal.textContent = checkoutCurrency(checkoutPricing.total);
            placeOrderTotal.textContent = checkoutCurrency(checkoutPricing.total);
        }
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
            cart_items: checkoutCartItems,
        }),
    })
        .then(async (response) => {
            let data;
            try {
                data = await response.json();
            } catch (_) {
                throw new Error('Coupon could not be applied. Please try again.');
            }

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
        })        .finally(() => {
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
// Don't auto-apply on page load — server-side discount already rendered in Order Summary
// applyCoupon() is triggered only by manual input or chip click

const phoneCounter = document.getElementById('phoneCounter');

function sanitizeCheckoutPhone() {
    if (!phoneInput) return;
    const digits = phoneInput.value.replace(/\D/g, '').slice(0, 10);
    phoneInput.value = digits;
    if (phoneCounter) phoneCounter.textContent = digits.length;

    // Visual feedback: green border when exactly 10 digits, red when not
    if (digits.length === 10) {
        phoneInput.classList.remove('border-red-400', 'focus:border-red-400');
        phoneInput.classList.add('border-amber-400', 'focus:border-amber-400');
    } else if (digits.length > 0) {
        phoneInput.classList.remove('border-amber-400', 'focus:border-amber-400');
        phoneInput.classList.add('border-red-400', 'focus:border-red-400');
    } else {
        phoneInput.classList.remove('border-amber-400', 'border-red-400', 'focus:border-amber-400', 'focus:border-red-400');
    }
}

phoneInput?.addEventListener('input', sanitizeCheckoutPhone);
sanitizeCheckoutPhone();

// Address Selection Logic
const pastAddresses = @json($pastAddresses ?? []);
const hasErrors = @json($errors->has('address') || $errors->has('city') || $errors->has('state') || $errors->has('pincode') || $errors->has('sector'));
// -1 means "form mode" (add new / edit), >= 0 means a saved card is selected
let selectedAddressIndex = (pastAddresses.length > 0 && !hasErrors) ? 0 : -1;

const addressCards = document.querySelectorAll('.address-card');
const formContainer = document.getElementById('addressFormContainer');
const formActionTitle = document.getElementById('formActionTitle');
const addNewBtn = document.getElementById('addNewAddressBtn');

const streetInput = document.getElementById('shippingAddressInput');
const cityInput = document.getElementById('shippingCityInput');
const stateInput = document.getElementById('shippingStateInput');
const pincodeInput = document.getElementById('shippingPincodeInput');
const sectorInput = document.getElementById('shippingSectorInput');

function updateAddressSelectionUI() {
    const isCardSelected = selectedAddressIndex >= 0;

    addressCards.forEach((card, idx) => {
        const isSelected = isCardSelected && idx === selectedAddressIndex;
        card.classList.toggle('border-neutral-700', isSelected);
        card.classList.toggle('bg-amber-50/20', isSelected);
        card.classList.toggle('shadow-sm', isSelected);
        if (!isSelected) {
            card.classList.remove('border-neutral-700', 'bg-amber-50/20', 'shadow-sm');
        }

        const badge = card.querySelector('.select-badge');
        if (badge) {
            badge.classList.toggle('hidden', !isSelected);
            badge.classList.toggle('inline-flex', isSelected);
        }
    });

    if (isCardSelected) {
        // Saved address selected — populate hidden inputs and hide form
        const addr = pastAddresses[selectedAddressIndex];
        streetInput.value = addr.address || '';
        cityInput.value = addr.city || '';
        stateInput.value = addr.state || '';
        sectorInput.value = addr.sector || '';
        pincodeInput.value = addr.pincode || '';

        formContainer.classList.add('hidden');
        [streetInput, cityInput, stateInput, pincodeInput].forEach(el => {
            if (el) el.removeAttribute('required');
        });
        addNewBtn?.classList.remove('hidden');
    } else {
        // Form mode (add new or edit)
        formContainer.classList.remove('hidden');
        [streetInput, cityInput, stateInput, pincodeInput].forEach(el => {
            if (el) el.setAttribute('required', '');
        });
        addNewBtn?.classList.add('hidden');
    }
}

function selectAddressCard(index) {
    selectedAddressIndex = index;
    updateAddressSelectionUI();
}

function showNewAddressForm() {
    selectedAddressIndex = -1;

    streetInput.value = '';
    cityInput.value = '';
    stateInput.value = '';
    sectorInput.value = '';
    pincodeInput.value = '';

    if (formActionTitle) formActionTitle.textContent = 'Add New Address';

    updateAddressSelectionUI();
    streetInput.focus();
}

function editAddressCard(event, index) {
    event.stopPropagation();
    selectedAddressIndex = -1; // form mode

    const addr = pastAddresses[index];
    streetInput.value = addr.address || '';
    cityInput.value = addr.city || '';
    stateInput.value = addr.state || '';
    sectorInput.value = addr.sector || '';
    pincodeInput.value = addr.pincode || '';

    if (formActionTitle) formActionTitle.textContent = 'Edit Address';

    updateAddressSelectionUI();
    streetInput.focus();
}

function cancelAddressForm() {
    if (pastAddresses.length > 0) {
        selectedAddressIndex = 0;
        updateAddressSelectionUI();
    }
}

// Initial setup
if (pastAddresses.length > 0 && !hasErrors) {
    updateAddressSelectionUI();
} else {
    // No past addresses or validation errors — show form with old/default values
    formContainer?.classList.remove('hidden');
    addNewBtn?.classList.add('hidden');
    streetInput.value = @json(old('address', $checkoutDefaults['address'] ?? ''));
    cityInput.value = @json(old('city', $checkoutDefaults['city'] ?? ''));
    stateInput.value = @json(old('state', $checkoutDefaults['state'] ?? ''));
    sectorInput.value = @json(old('sector', $checkoutDefaults['sector'] ?? ''));
    pincodeInput.value = @json(old('pincode', $checkoutDefaults['pincode'] ?? ''));
}

// Contact Information Toggle
const contactToggle = document.getElementById('contactInfoToggle');
const contactContent = document.getElementById('contactInfoContent');
const contactChevron = document.getElementById('contactInfoChevron');

if (contactToggle) {
    contactToggle.addEventListener('click', function() {
        contactContent.classList.toggle('hidden');
        contactChevron.style.transform = contactContent.classList.contains('hidden') ? '' : 'rotate(180deg)';
    });
}

</script>
@endsection
