<?php $__env->startSection('title', 'Checkout'); ?>

<?php $__env->startSection('styles'); ?>
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
        top: 178px;
        z-index: 20;
    }
}
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<?php
    $isCodAvailable = ($pricing['subtotal'] ?? 0) <= 2000;
    $paymentOptions = $isCodAvailable
        ? ['COD' => 'Cash on Delivery', 'online' => 'Online / UPI Payment']
        : ['online' => 'Online / UPI Payment'];
    $selectedPaymentMethod = old('payment_method', $isCodAvailable ? 'COD' : 'online');

    if (! array_key_exists($selectedPaymentMethod, $paymentOptions)) {
        $selectedPaymentMethod = array_key_first($paymentOptions);
    }

    $initialCouponCode = old('coupon_code', session('applied_coupon_' . session('checkout_delivery_day', 'today')));
?>
<div class="checkout-page max-w-5xl mx-auto px-4 py-8">
    <h1 class="nunito font-extrabold text-2xl text-gray-800 mb-6">Checkout</h1>

    
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

    <div id="checkoutGrid" class="grid grid-cols-1 gap-6 lg:grid-cols-[minmax(0,1fr)_20rem]">
        <div class="flex-1 space-y-5">
            <form method="POST" action="<?php echo e(route('frontend.checkout.store')); ?>" id="checkoutForm">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="delivery_slot" value="<?php echo e($selectedDeliverySlot); ?>">

                <?php if($errors->any()): ?>
                <div class="rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-600">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><p><?php echo e($error); ?></p><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <?php endif; ?>

                <div class="overflow-hidden rounded-2xl border bg-white">
                    <button type="button" id="contactInfoToggle" class="w-full flex items-center justify-between border-b px-6 py-4 hover:bg-gray-50 transition">
                        <div class="flex items-center gap-2">
                            <div class="flex h-7 w-7 items-center justify-center rounded-full bg-blue-700 text-xs font-bold text-white">1</div>
                            <h2 class="nunito text-base font-extrabold text-gray-800">Contact Information</h2>
                        </div>
                        <i class="fa-solid fa-chevron-down text-xs text-gray-400 transition-transform" id="contactInfoChevron"></i>
                    </button>

                    
                    <input type="hidden" name="first_name" value="<?php echo e($checkoutDefaults['first_name'] ?? ''); ?>">
                    <input type="hidden" name="last_name"  value="<?php echo e($checkoutDefaults['last_name']  ?? ''); ?>">
                    <input type="hidden" name="email"      value="<?php echo e($checkoutDefaults['email']      ?? ''); ?>">

                    <div id="contactInfoContent" class="px-6 py-5 space-y-4 hidden">
                        
                        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                            <div>
                                <p class="text-xs font-semibold text-gray-400 mb-0.5">First Name</p>
                                <p class="text-sm font-bold text-gray-800"><?php echo e($checkoutDefaults['first_name'] ?? '—'); ?></p>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-gray-400 mb-0.5">Last Name</p>
                                <p class="text-sm font-bold text-gray-800"><?php echo e($checkoutDefaults['last_name'] ?: '—'); ?></p>
                            </div>
                        </div>

                        <div>
                            <p class="text-xs font-semibold text-gray-400 mb-0.5">Email</p>
                            <p class="text-sm font-bold text-gray-800"><?php echo e($checkoutDefaults['email'] ?? '—'); ?></p>
                        </div>

                        <hr class="border-gray-100">
                    </div>

                    
                    <div class="px-6 py-4">
                        <label for="checkoutPhoneInput" class="mb-1 block text-xs font-semibold text-gray-600">
                            Phone <span class="text-red-500">*</span>
                            <span class="ml-1 font-normal text-gray-400">(10 digit mobile number)</span>
                        </label>
                        <input type="tel"
                               name="phone"
                               id="checkoutPhoneInput"
                               value="<?php echo e($checkoutDefaults['phone'] ?? ''); ?>"
                               required
                               inputmode="numeric"
                               pattern="\d{10}"
                               minlength="10"
                               maxlength="10"
                               autocomplete="tel"
                               placeholder="Enter 10-digit mobile number"
                               title="Enter exactly 10-digit mobile number"
                               oninput="this.value = this.value.replace(/\D/g, '').slice(0, 10)"
                               class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-100">
                        <p class="mt-1 text-xs text-gray-400" id="phoneHint">
                            <span id="phoneCounter"><?php echo e(strlen($checkoutDefaults['phone'] ?? '')); ?></span>/10 digits entered
                        </p>
                    </div>
                </div>

                <div class="overflow-hidden rounded-2xl border bg-white">
                    <div class="flex items-center justify-between border-b px-6 py-4">
                        <div class="flex items-center gap-2">
                            <div class="flex h-7 w-7 items-center justify-center rounded-full bg-blue-700 text-xs font-bold text-white">2</div>
                            <h2 class="nunito text-base font-extrabold text-gray-800">Shipping Address</h2>
                        </div>
                        <?php if(!empty($pastAddresses)): ?>
                            <button type="button" onclick="showNewAddressForm()" id="addNewAddressBtn"
                                    class="text-xs font-bold text-blue-700 hover:text-blue-800 flex items-center gap-1">
                                <i class="fa-solid fa-plus text-[10px]"></i> Add New
                            </button>
                        <?php endif; ?>
                    </div>
                    <div class="px-6 py-5 space-y-3">
                        <?php if(!empty($pastAddresses)): ?>
                            <div id="savedAddressesContainer" class="space-y-2 mb-4">
                                <?php $__currentLoopData = $pastAddresses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $addr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="address-card border rounded-xl p-3 cursor-pointer relative hover:border-blue-500 transition-all duration-200 flex items-center justify-between gap-3"
                                         data-index="<?php echo e($index); ?>"
                                         onclick="selectAddressCard(<?php echo e($index); ?>)">
                                         <div class="min-w-0 flex-1">
                                             <div class="flex items-center justify-between gap-2">
                                                 <p class="text-sm font-bold text-gray-800 truncate"><?php echo e($addr['name'] ?? ''); ?></p>
                                                 <span class="text-[10px] font-bold text-green-700 select-badge items-center gap-1 hidden flex-shrink-0">
                                                     <i class="fa-solid fa-circle-check text-[10px]"></i> Selected
                                                 </span>
                                             </div>
                                             <p class="text-xs text-gray-500 mt-1 line-clamp-1"><?php echo e($addr['address']); ?> <?php if(!empty($addr['sector'])): ?>• Sector: <?php echo e($addr['sector']); ?><?php endif; ?></p>
                                             <p class="text-xs text-gray-500 line-clamp-1"><?php echo e($addr['city']); ?>, <?php echo e($addr['state']); ?> - <?php echo e($addr['pincode']); ?><?php if(!empty($addr['phone'])): ?> • <?php echo e($addr['phone']); ?><?php endif; ?></p>
                                         </div>
                                         <button type="button" onclick="editAddressCard(event, <?php echo e($index); ?>)" 
                                                 class="text-xs text-blue-600 hover:text-blue-800 font-semibold flex items-center gap-1 flex-shrink-0">
                                                 <i class="fa-regular fa-pen-to-square text-[10px]"></i>
                                         </button>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        <?php endif; ?>

                        <div id="addressFormContainer" class="space-y-4 pt-2">
                            <?php if(!empty($pastAddresses)): ?>
                                <div class="flex items-center justify-between mb-2">
                                    <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider" id="formActionTitle">Add New Address</h3>
                                    <button type="button" onclick="cancelAddressForm()" class="text-xs text-gray-400 hover:text-gray-600 font-medium">
                                        Cancel
                                    </button>
                                </div>
                            <?php endif; ?>

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
                                  <label class="mb-1 block text-xs font-semibold text-gray-600">Sector *</label>
                                 <div class="relative">
                                    <input type="text" id="shippingSectorSearchInput" placeholder="Search sector or PIN code..."
                                           autocomplete="off"
                                                 class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-100">
                                                 <input type="hidden" name="sector" id="shippingSectorInput">
                                   <div id="sectorDropdown" class="hidden absolute bottom-full left-0 right-0 mb-2 bg-white border border-gray-200 rounded-xl shadow-xl z-50 max-h-72 overflow-y-auto">
        <!-- Sectors will be populated by JS -->
    </div>
</div>
                                </div>
                                <div>
                                    <label class="mb-1 block text-xs font-semibold text-gray-600">PIN Code *</label>
                                    <input type="text" name="pincode" id="shippingPincodeInput" required readonly
                                           placeholder="Auto-filled from Sector"
                                           class="w-full cursor-not-allowed rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm font-semibold text-gray-700 outline-none">
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
                        <?php if (! ($isCodAvailable)): ?>
                            <div class="rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-xs font-semibold text-amber-700">
                                Cash on Delivery is available only for orders up to &#8377;2,000.
                            </div>
                        <?php endif; ?>
                        <?php $__currentLoopData = $paymentOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <label class="flex cursor-pointer items-center gap-3 rounded-xl border border-gray-200 p-4 transition hover:border-blue-500">
                            <input type="radio" name="payment_method" value="<?php echo e($val); ?>" <?php echo e($selectedPaymentMethod === $val ? 'checked' : ''); ?> class="accent-blue-600">
                            <span class="text-sm font-semibold text-gray-700"><?php echo e($label); ?></span>
                        </label>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>

                <div id="checkoutCouponSection" class="rounded-2xl border bg-white p-4 sm:p-5">
                    <label class="mb-2 block text-xs font-semibold text-gray-600">Coupon Code (optional)</label>
                    <div class="flex gap-2">
                        <input type="text" name="coupon_code" id="couponCodeInput" value="<?php echo e($initialCouponCode); ?>" placeholder="Enter coupon code"
                               class="min-w-0 flex-1 rounded-xl border border-gray-200 px-3 py-2.5 text-sm uppercase outline-none focus:border-blue-500 sm:px-4">
                        <button type="button" id="applyCouponButton"
                                class="<?php echo e($initialCouponCode ? '' : 'hidden'); ?> flex-shrink-0 rounded-xl bg-green-600 px-4 py-2.5 text-sm font-bold text-white transition hover:bg-green-700 sm:px-5">
                            Apply
                        </button>
                    </div>
                    <p id="couponMessage" class="mt-2 hidden text-xs font-semibold"></p>
                    <?php if(($availableCoupons ?? collect())->isNotEmpty()): ?>
                        <p class="mt-3 text-[10px] font-bold uppercase tracking-wider text-gray-400">Available Coupons</p>
                        <div class="mt-2 flex flex-wrap gap-1.5 sm:gap-2">
                            <?php $__currentLoopData = $availableCoupons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $coupon): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <button type="button"
                                        data-coupon-code="<?php echo e($coupon->code); ?>"
                                        class="coupon-chip rounded-full border border-green-200 bg-green-50 px-2.5 py-1 text-[10px] font-bold text-green-700 transition hover:border-green-500 hover:bg-white sm:px-3 sm:py-1.5 sm:text-[11px]">
                                    <?php echo e($coupon->code); ?>

                                </button>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php endif; ?>
                </div>

                
                <div id="checkoutOrderSummary" class="overflow-hidden rounded-2xl border bg-white shadow-sm">
                    <div class="h-1 bg-gradient-to-r from-blue-700 to-orange-400"></div>
                    <div class="border-b px-5 py-4">
                        <h2 class="nunito text-base font-extrabold text-gray-800">Order Summary</h2>
                    </div>
                    <div class="max-h-64 space-y-3 overflow-y-auto border-b px-5 py-4">
                        <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center overflow-hidden rounded-lg bg-gray-100">
                                <?php if($item['image']): ?>
                                    <img src="<?php echo e(asset('storage/' . $item['image'])); ?>" class="h-full w-full object-cover" alt="<?php echo e($item['name']); ?>">
                                <?php else: ?>
                                    <i class="fa-solid fa-basket-shopping text-sm text-gray-400"></i>
                                <?php endif; ?>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="truncate text-xs font-semibold text-gray-800"><?php echo e($item['name']); ?></p>
                                <div class="mt-1 flex items-center gap-2">
                                    <span class="text-[10px] text-gray-400">Qty: <?php echo e($item['quantity']); ?></span>
                                    <span class="inline-flex items-center rounded border px-1.5 py-0.5 text-[8px] font-extrabold uppercase tracking-wider <?php echo e(($item['pricing_day'] ?? 'today') === 'tomorrow' ? 'bg-amber-50 text-amber-700 border-amber-100' : 'bg-emerald-50 text-emerald-700 border-emerald-100'); ?>">
                                        <?php echo e(($item['pricing_day'] ?? 'today') === 'tomorrow' ? 'Tomorrow' : 'Today'); ?>

                                    </span>
                                </div>
                            </div>
                            <span class="flex-shrink-0 text-xs font-bold text-gray-800">&#8377;<?php echo e(number_format($item['subtotal'], 2)); ?></span>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    <div class="space-y-2 px-5 py-4 text-sm">
                        <div class="flex justify-between"><span class="text-gray-500">Subtotal</span><span>&#8377;<?php echo e(number_format($pricing['subtotal'], 2)); ?></span></div>
                        <?php if((float) ($pricing['delivery_charge'] ?? 0) > 0): ?>
                            <div class="flex justify-between"><span class="text-gray-500">Delivery</span><span>&#8377;<?php echo e(number_format($pricing['delivery_charge'], 2)); ?></span></div>
                        <?php endif; ?>
                        <?php if((float) ($pricing['service_charge'] ?? 0) > 0): ?>
                        <div class="flex justify-between">
                            <span class="text-gray-500">&#8505;&#65039; Service Charge</span>
                            <span>&#8377;<?php echo e(number_format($pricing['service_charge'], 2)); ?></span>
                        </div>
                        <?php endif; ?>
                        <div id="checkoutDiscountRow" class="hidden justify-between text-green-600">
                            <span>Discount</span>
                            <span id="checkoutDiscountAmount">-&#8377;0.00</span>
                        </div>
                        <hr class="border-gray-100">
                        <div class="flex justify-between text-base font-bold text-gray-800">
                            <span>Total</span>
                            <span id="checkoutFinalTotal" class="text-blue-700">&#8377;<?php echo e(number_format($pricing['total'], 2)); ?></span>
                        </div>
                    </div>
                </div>

                <button type="submit" id="placeOrderButton"
                        onclick="return checkOrderingActive(event)"
                        class="flex w-full items-center justify-center gap-2 rounded-2xl bg-blue-700 py-4 text-sm font-bold text-white transition hover:bg-blue-800">
                    <i class="fa-solid fa-lock text-xs"></i>
                    Place Order - <span id="placeOrderTotal">&#8377;<?php echo e(number_format($pricing['total'], 2)); ?></span>
                </button>
            </form>


<div id="orderingInactiveModal"
     class="fixed inset-0 z-[10100] <?php echo e((session('ordering_inactive') || !($orderingActive ?? true)) ? 'flex' : 'hidden'); ?> items-center justify-center bg-black/60 backdrop-blur-sm px-4">
    <div class="w-full max-w-md overflow-hidden rounded-3xl bg-white shadow-2xl">
        
        <div class="bg-gradient-to-br from-slate-900 to-slate-800 px-7 py-7 text-center">
            <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-2xl bg-orange-500/20 ring-2 ring-orange-400/30">
                <i class="fa-solid fa-store-slash text-2xl text-orange-400"></i>
            </div>
            <h2 class="text-xl font-black text-white"><?php echo e($orderingInactiveTitle ?? 'Orders Temporarily Paused'); ?></h2>
            <p class="mt-3 text-sm leading-relaxed text-slate-300"><?php echo e($orderingInactiveMessage ?? 'We are currently not accepting new orders. Please check back soon.'); ?></p>
        </div>
        
        <div class="flex gap-3 bg-slate-50 px-7 py-5">
            <a href="<?php echo e(route('frontend.home')); ?>"
               class="flex flex-1 items-center justify-center gap-2 rounded-xl bg-slate-900 py-3 text-sm font-bold text-white transition hover:bg-slate-700">
                <i class="fa-solid fa-house text-xs"></i> Go Home
            </a>
            <button type="button"
                    onclick="document.getElementById('orderingInactiveModal').classList.add('hidden'); document.getElementById('orderingInactiveModal').classList.remove('flex');"
                    class="flex flex-1 items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white py-3 text-sm font-bold text-slate-600 transition hover:bg-slate-100">
                <i class="fa-solid fa-xmark text-xs"></i> Close
            </button>
        </div>
    </div>
</div>
        </div>

        <aside id="checkoutSideColumn" class="w-full lg:self-stretch">
            <div id="checkoutPromoCard" class="group relative overflow-hidden rounded-[24px] border border-emerald-100 bg-white shadow-md">
                <img src="<?php echo e(asset('images/checkout/fresh-delivery-banner.png')); ?>" alt="Fresh meat and seafood packed for delivery" class="h-48 w-full object-cover transition duration-700 group-hover:scale-105">
                <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-slate-950/90 via-slate-900/55 to-transparent px-5 pb-4 pt-12 text-white">
                    <p class="text-[10px] font-black uppercase tracking-[0.2em] text-emerald-300">FarmSea Fresh Promise</p>
                    <h3 class="mt-1 text-lg font-black">Freshness packed with care</h3>
                    <p class="mt-1 text-[11px] font-semibold text-slate-200">Temperature-controlled packing &amp; safe doorstep delivery.</p>
                </div>
            </div>

            <?php if(($checkoutNewArrivals ?? collect())->isNotEmpty()): ?>
                <div id="checkoutNewArrivals" class="mt-6 rounded-[22px] border border-slate-200 bg-white p-3 shadow-sm">
                    <div class="mb-3 flex items-center justify-between px-1">
                        <div>
                            <p class="text-[9px] font-black uppercase tracking-[0.18em] text-blue-600">Just In</p>
                            <h3 class="text-sm font-black text-slate-900">New Arrivals</h3>
                        </div>
                        <a href="<?php echo e(route('frontend.products', ['sort' => 'latest'])); ?>" class="text-[9px] font-black uppercase tracking-wider text-blue-600 hover:underline">View All</a>
                    </div>

                    <div class="space-y-2.5">
                        <?php $__currentLoopData = $checkoutNewArrivals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $arrival): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $arrivalVariantIndex = collect($arrival->variants ?? [])->search(
                                    fn ($variant) => filled($variant['selling_price'] ?? null) || filled($variant['today_price'] ?? null)
                                );
                                $arrivalVariantIndex = $arrivalVariantIndex === false ? null : $arrivalVariantIndex;
                                $arrivalImage = $arrival->images && count($arrival->images) ? asset('storage/' . $arrival->images[0]) : null;
                            ?>
                            <article class="group/item flex items-center gap-3 rounded-2xl border border-slate-100 bg-slate-50/70 p-2 transition hover:border-green-200 hover:bg-green-50/50">
                                <a href="<?php echo e(route('frontend.product.show', $arrival->slug)); ?>" class="h-14 w-14 flex-shrink-0 overflow-hidden rounded-xl bg-white">
                                    <?php if($arrivalImage): ?>
                                        <img src="<?php echo e($arrivalImage); ?>" alt="<?php echo e($arrival->name); ?>" class="h-full w-full object-cover transition duration-300 group-hover/item:scale-105">
                                    <?php else: ?>
                                        <span class="flex h-full w-full items-center justify-center text-slate-300"><i class="fa-solid fa-basket-shopping"></i></span>
                                    <?php endif; ?>
                                </a>
                                <div class="min-w-0 flex-1">
                                    <p class="truncate text-[9px] font-black uppercase tracking-wider text-emerald-600"><?php echo e($arrival->category->name ?? 'Fresh'); ?></p>
                                    <a href="<?php echo e(route('frontend.product.show', $arrival->slug)); ?>" class="mt-0.5 block truncate text-[11px] font-black text-slate-900 hover:text-green-700"><?php echo e($arrival->name); ?></a>
                                    <p class="mt-1 text-xs font-black text-slate-900">Rs<?php echo e(number_format($arrival->display_price, 0)); ?> <span class="text-[9px] font-semibold text-slate-400"><?php echo e($arrival->display_pack_label); ?></span></p>
                                </div>
                                <button type="button" onclick="addToCart(<?php echo e($arrival->id); ?>, <?php echo e($arrivalVariantIndex === null ? 'null' : $arrivalVariantIndex); ?>, 'today')" aria-label="Add <?php echo e($arrival->name); ?> to cart" title="Add to cart" class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-xl bg-green-700 text-white shadow-sm transition hover:bg-green-800">
                                    <i class="fa-solid fa-cart-plus text-xs"></i>
                                </button>
                            </article>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            <?php endif; ?>

            <a id="checkoutMealBanner" href="<?php echo e(route('frontend.products')); ?>" class="group relative mt-6 block overflow-hidden rounded-[22px] border border-amber-100 bg-white shadow-md">
                <img src="<?php echo e(asset('images/checkout/ready-to-cook-meal-banner.png')); ?>" alt="Fresh ready-to-cook fish and chicken meal" class="h-40 w-full object-cover transition duration-700 group-hover:scale-105">
                <div class="absolute inset-0 bg-gradient-to-r from-slate-950/80 via-slate-900/30 to-transparent"></div>
                <div class="absolute inset-y-0 left-0 flex max-w-[72%] flex-col justify-center px-4 text-white">
                    <p class="text-[9px] font-black uppercase tracking-[0.18em] text-amber-300">Cook Something Fresh</p>
                    <h3 class="mt-1 text-base font-black leading-tight">Dinner inspiration, delivered fresh</h3>
                    <span class="mt-3 inline-flex w-fit items-center gap-1.5 rounded-lg bg-white/95 px-3 py-1.5 text-[9px] font-black uppercase tracking-wider text-slate-900">Shop Now <i class="fa-solid fa-arrow-right"></i></span>
                </div>
            </a>

            <div id="checkoutTrustCard" class="mt-6 overflow-hidden rounded-[22px] border border-emerald-100 bg-white shadow-sm">
                <div class="bg-gradient-to-r from-emerald-700 to-green-600 px-4 py-3 text-white">
                    <p class="text-[9px] font-black uppercase tracking-[0.18em] text-emerald-100">Shop With Confidence</p>
                    <h3 class="mt-0.5 text-sm font-black">Why customers trust FarmSea</h3>
                </div>
                <div class="divide-y divide-slate-100 px-3">
                    <div class="flex items-center gap-3 py-3">
                        <span class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600"><i class="fa-solid fa-shield-halved text-xs"></i></span>
                        <div><p class="text-[11px] font-black text-slate-900">Safe &amp; secure checkout</p><p class="mt-0.5 text-[9px] font-semibold text-slate-400">Your order details stay protected</p></div>
                    </div>
                    <div class="flex items-center gap-3 py-3">
                        <span class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-xl bg-amber-50 text-amber-600"><i class="fa-solid fa-headset text-xs"></i></span>
                        <div class="min-w-0 flex-1"><p class="text-[11px] font-black text-slate-900">Need checkout help?</p><p class="mt-0.5 text-[9px] font-semibold text-slate-400">Our support team is ready</p></div>
                        <a href="<?php echo e(route('frontend.contact')); ?>" class="rounded-lg bg-slate-900 px-2.5 py-1.5 text-[8px] font-black uppercase tracking-wider text-white transition hover:bg-emerald-700">Get Help</a>
                    </div>
                </div>
            </div>

            <?php if(($checkoutSimilarProducts ?? collect())->isNotEmpty()): ?>
                <div id="checkoutSimilarProducts" class="mt-6 rounded-[22px] border border-violet-100 bg-white p-3 shadow-sm">
                    <div class="mb-3 flex items-center justify-between px-1">
                        <div>
                            <p class="text-[9px] font-black uppercase tracking-[0.18em] text-violet-600">You May Also Like</p>
                            <h3 class="text-sm font-black text-slate-900">Similar Products</h3>
                        </div>
                        <a href="<?php echo e(route('frontend.products')); ?>" class="text-[9px] font-black uppercase tracking-wider text-violet-600 hover:underline">View All</a>
                    </div>

                    <div class="space-y-2.5">
                        <?php $__currentLoopData = $checkoutSimilarProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $similarProduct): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $similarVariantIndex = collect($similarProduct->variants ?? [])->search(
                                    fn ($variant) => filled($variant['selling_price'] ?? null) || filled($variant['today_price'] ?? null)
                                );
                                $similarVariantIndex = $similarVariantIndex === false ? null : $similarVariantIndex;
                                $similarImage = $similarProduct->images && count($similarProduct->images) ? asset('storage/' . $similarProduct->images[0]) : null;
                            ?>
                            <article class="group/similar flex items-center gap-3 rounded-2xl border border-violet-50 bg-violet-50/45 p-2 transition hover:border-violet-200 hover:bg-violet-50">
                                <a href="<?php echo e(route('frontend.product.show', $similarProduct->slug)); ?>" class="h-14 w-14 flex-shrink-0 overflow-hidden rounded-xl bg-white">
                                    <?php if($similarImage): ?>
                                        <img src="<?php echo e($similarImage); ?>" alt="<?php echo e($similarProduct->name); ?>" class="h-full w-full object-cover transition duration-300 group-hover/similar:scale-105">
                                    <?php else: ?>
                                        <span class="flex h-full w-full items-center justify-center text-violet-200"><i class="fa-solid fa-basket-shopping"></i></span>
                                    <?php endif; ?>
                                </a>
                                <div class="min-w-0 flex-1">
                                    <p class="truncate text-[9px] font-black uppercase tracking-wider text-violet-600"><?php echo e($similarProduct->category->name ?? 'Recommended'); ?></p>
                                    <a href="<?php echo e(route('frontend.product.show', $similarProduct->slug)); ?>" class="mt-0.5 block truncate text-[11px] font-black text-slate-900 hover:text-violet-700"><?php echo e($similarProduct->name); ?></a>
                                    <p class="mt-1 text-xs font-black text-slate-900">Rs<?php echo e(number_format($similarProduct->display_price, 0)); ?> <span class="text-[9px] font-semibold text-slate-400"><?php echo e($similarProduct->display_pack_label); ?></span></p>
                                </div>
                                <button type="button" onclick="addToCart(<?php echo e($similarProduct->id); ?>, <?php echo e($similarVariantIndex === null ? 'null' : $similarVariantIndex); ?>, 'today')" aria-label="Add <?php echo e($similarProduct->name); ?> to cart" title="Add to cart" class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-xl bg-violet-600 text-white shadow-sm transition hover:bg-violet-700">
                                    <i class="fa-solid fa-cart-plus text-xs"></i>
                                </button>
                            </article>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            <?php endif; ?>


        </aside>
    </div>
</div>

<script>
// Order Summary is now part of the main form (not sticky sidebar)

const checkoutPricing = {
    subtotal: Number(<?php echo json_encode((float) ($pricing['subtotal'] ?? 0), 15, 512) ?>),
    total: Number(<?php echo json_encode((float) ($pricing['total'] ?? 0), 15, 512) ?>),
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

const phoneCounter = document.getElementById('phoneCounter');

function sanitizeCheckoutPhone() {
    if (!phoneInput) return;
    const digits = phoneInput.value.replace(/\D/g, '').slice(0, 10);
    phoneInput.value = digits;
    if (phoneCounter) phoneCounter.textContent = digits.length;

    // Visual feedback: green border when exactly 10 digits, red when not
    if (digits.length === 10) {
        phoneInput.classList.remove('border-red-400', 'focus:border-red-400');
        phoneInput.classList.add('border-green-400', 'focus:border-green-400');
    } else if (digits.length > 0) {
        phoneInput.classList.remove('border-green-400', 'focus:border-green-400');
        phoneInput.classList.add('border-red-400', 'focus:border-red-400');
    } else {
        phoneInput.classList.remove('border-green-400', 'border-red-400', 'focus:border-green-400', 'focus:border-red-400');
    }
}

phoneInput?.addEventListener('input', sanitizeCheckoutPhone);
sanitizeCheckoutPhone();

// Address Selection Logic
const pastAddresses = <?php echo json_encode($pastAddresses ?? [], 15, 512) ?>;
const hasErrors = <?php echo json_encode($errors->has('address') || $errors->has('city') || $errors->has('state') || $errors->has('pincode') || $errors->has('sector'), 15, 512) ?>;
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
const sectorSearchInput = document.getElementById('shippingSectorSearchInput');
const sectorDropdown = document.getElementById('sectorDropdown');

const pinToSectors = <?php echo json_encode($pinSectors, 15, 512) ?>;

// Build mapping: sector+pincode -> pincode, and all sector options
const sectorToPinMap = {};
const sectorOptions = [];

Object.entries(pinToSectors).forEach(([pin, sectors]) => {
    sectors.forEach(sector => {
        const optionLabel = `${sector} (${pin})`;
        sectorToPinMap[optionLabel] = pin;
        sectorOptions.push({
            display: optionLabel,
            sector: sector,
            pincode: pin
        });
    });
});

// Sort by sector name, then by pincode
sectorOptions.sort((a, b) => {
    if (a.sector !== b.sector) {
        return a.sector.localeCompare(b.sector, undefined, { numeric: true });
    }
    return a.pincode.localeCompare(b.pincode);
});

console.log('Total sectors loaded:', sectorOptions.length, 'options:', sectorOptions.slice(0, 5), '...');
console.log('sectorSearchInput:', sectorSearchInput);
console.log('sectorDropdown:', sectorDropdown);
console.log('sectorInput:', sectorInput);
console.log('pincodeInput:', pincodeInput);

// Populate all sectors
function populateAllSectors() {
    if (!sectorDropdown) {
        console.error('sectorDropdown element not found');
        return;
    }
    
    sectorDropdown.innerHTML = '';
    if (sectorOptions.length === 0) {
        console.warn('No sector options available');
        const noResult = document.createElement('div');
        noResult.className = 'px-4 py-2.5 text-sm text-gray-500 text-center';
        noResult.textContent = 'No sectors available';
        sectorDropdown.appendChild(noResult);
        return;
    }
    
    sectorOptions.forEach((option, idx) => {
        const div = document.createElement('div');
        div.className = 'px-4 py-2.5 text-sm cursor-pointer hover:bg-blue-50 transition-colors border-b border-gray-100 last:border-b-0 text-gray-700 font-medium';
        div.innerHTML = `<span class="font-semibold">${option.sector}</span><span class="text-gray-400 ml-2 text-xs">${option.pincode}</span>`;
        div.dataset.sectorIndex = idx; // Store the index as data attribute
        div.onclick = (e) => {
            e.stopPropagation();
            selectSectorFromDropdown(option.sector, option.pincode);
        };
        sectorDropdown.appendChild(div);
    });
}

function selectSectorFromDropdown(sector, pincode) {
    // Focus: hamesha current text ke hisab se fresh list dikhao (stale list bug fix)
sectorSearchInput?.addEventListener('focus', () => {
    sectorDropdown.classList.remove('hidden');
    filterSectors();
});

sectorSearchInput?.addEventListener('input', () => {
    sectorDropdown.classList.remove('hidden');
    filterSectors();
});

sectorSearchInput?.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        sectorDropdown.classList.add('hidden');
    }
});
    
    sectorSearchInput.value = sector;
    sectorInput.value = sector;
    pincodeInput.value = pincode;
    sectorDropdown.classList.add('hidden');
    
    // Trigger change event for form validation
    sectorInput.dispatchEvent(new Event('change'));
    pincodeInput.dispatchEvent(new Event('change'));
    
    console.log('Sector selected:', sector, 'Pincode:', pincode);
}

function filterSectors() {
    if (!sectorSearchInput || !sectorDropdown) {
        console.error('Required elements not found for filterSectors');
        return;
    }
    
    const searchTerm = sectorSearchInput.value.toLowerCase().trim();
    
    if (searchTerm === '') {
        // Show all sectors
        populateAllSectors();
        return;
    }
    
    // Filter the options
    const filteredOptions = sectorOptions.filter(option => {
        const sectorName = option.sector.toLowerCase();
        const pincode = option.pincode.toLowerCase();
        return sectorName.includes(searchTerm) || pincode.includes(searchTerm);
    });
    
    // Re-populate dropdown with filtered results
    sectorDropdown.innerHTML = '';
    
    if (filteredOptions.length === 0) {
        const noResult = document.createElement('div');
        noResult.className = 'px-4 py-2.5 text-sm text-gray-500 text-center';
        noResult.textContent = 'No sectors found';
        sectorDropdown.appendChild(noResult);
        return;
    }
    
    filteredOptions.forEach((option, idx) => {
        const div = document.createElement('div');
        div.className = 'px-4 py-2.5 text-sm cursor-pointer hover:bg-blue-50 transition-colors border-b border-gray-100 last:border-b-0 text-gray-700 font-medium';
        div.innerHTML = `<span class="font-semibold">${option.sector}</span><span class="text-gray-400 ml-2 text-xs">${option.pincode}</span>`;
        div.onclick = (e) => {
            e.stopPropagation();
            selectSectorFromDropdown(option.sector, option.pincode);
        };
        sectorDropdown.appendChild(div);
    });
}

// Event listeners for sector search
sectorSearchInput?.addEventListener('focus', () => {
    sectorDropdown.classList.remove('hidden');
    if (sectorDropdown.innerHTML === '') {
        populateAllSectors();
    }
});

sectorSearchInput?.addEventListener('input', () => {
    // Make sure dropdown is visible when typing
    sectorDropdown.classList.remove('hidden');
    if (sectorDropdown.innerHTML === '') {
        populateAllSectors();
    }
    filterSectors();
});

sectorSearchInput?.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        sectorDropdown.classList.add('hidden');
    }
});

// Close the sector list whenever the customer clicks outside its input/list.
document.addEventListener('click', (event) => {
    if (!sectorDropdown || !sectorSearchInput) return;

    const sectorPicker = sectorSearchInput.closest('.relative');
    if (sectorPicker && !sectorPicker.contains(event.target)) {
        sectorDropdown.classList.add('hidden');
    }
});

document.getElementById('checkoutForm')?.addEventListener('submit', function (e) {
    const isFormMode = selectedAddressIndex === -1; // naya address add/edit ho raha hai
    if (isFormMode && !sectorInput.value) {
        e.preventDefault();
        sectorSearchInput.classList.add('border-red-400');
        sectorSearchInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
        sectorSearchInput.focus();
        alert('Kripya apna sector select karein.');
    }
});

// Initialize sectors on page load
if (sectorSearchInput && sectorDropdown && sectorOptions.length > 0) {
    // Pre-populate the dropdown so it's ready immediately
    populateAllSectors();
    // If a default sector is set, show the dropdown initially
    if (sectorSearchInput.value) {
        // Already has a value, keep dropdown closed
    }
} else if (!sectorSearchInput) {
    console.error('sectorSearchInput element not found');
}

function populateSectors(pincode, selectedSector) {
    // This function is kept for backward compatibility
    // Now we populate all sectors regardless of pincode
    if (selectedSector) {
        sectorSearchInput.value = selectedSector;
        sectorInput.value = selectedSector;
        
        // Find the pincode for this sector
        const matchingOption = sectorOptions.find(opt => opt.sector === selectedSector && opt.pincode === pincode);
        if (matchingOption) {
            pincodeInput.value = matchingOption.pincode;
        }
    }
}

function updateAddressSelectionUI() {
    const isCardSelected = selectedAddressIndex >= 0;

    addressCards.forEach((card, idx) => {
        const isSelected = isCardSelected && idx === selectedAddressIndex;
        card.classList.toggle('border-blue-600', isSelected);
        card.classList.toggle('bg-blue-50/20', isSelected);
        card.classList.toggle('shadow-sm', isSelected);
        // Only reset border if not selected — avoid overriding blue border
        if (!isSelected) {
            card.classList.remove('border-blue-600', 'bg-blue-50/20', 'shadow-sm');
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
        cityInput.value = addr.city || 'Noida';
        stateInput.value = addr.state || 'UP';

        // Set sector and pincode from saved address
        const sector = addr.sector || '';
        const pin = addr.pincode || '';
        if (sector) {
            sectorSearchInput.value = sector;
            sectorInput.value = sector;
            pincodeInput.value = pin;
        }

        formContainer.classList.add('hidden');
        // Disable required on hidden form inputs to prevent form submission block
        [streetInput, pincodeInput, sectorInput, sectorSearchInput].forEach(el => {
            if (el) el.removeAttribute('required');
        });
        addNewBtn?.classList.remove('hidden');
    } else {
        // Form mode (add new or edit)
        formContainer.classList.remove('hidden');
        // Restore required attributes
        if (streetInput) streetInput.setAttribute('required', '');
        if (pincodeInput) pincodeInput.setAttribute('required', '');
        if (sectorInput) sectorInput.setAttribute('required', '');
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
    cityInput.value = 'Noida';
    stateInput.value = 'UP';
    sectorSearchInput.value = '';
    sectorInput.value = '';
    pincodeInput.value = '';
    sectorDropdown.classList.add('hidden');

    if (formActionTitle) formActionTitle.textContent = 'Add New Address';

    updateAddressSelectionUI();
    sectorSearchInput.focus();
}

function editAddressCard(event, index) {
    event.stopPropagation();
    selectedAddressIndex = -1; // form mode

    const addr = pastAddresses[index];
    streetInput.value = addr.address || '';
    cityInput.value = addr.city || 'Noida';
    stateInput.value = addr.state || 'UP';
    
    const sector = addr.sector || '';
    const pin = addr.pincode || '';
    sectorSearchInput.value = sector;
    sectorInput.value = sector;
    pincodeInput.value = pin;

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
    streetInput.value = <?php echo json_encode(old('address', $checkoutDefaults['address'] ?? ''), 512) ?>;
    cityInput.value = 'Noida';
    stateInput.value = 'UP';
    const defaultPin = <?php echo json_encode(old('pincode', $checkoutDefaults['pincode'] ?? ''), 512) ?>;
    const defaultSector = <?php echo json_encode(old('sector', $checkoutDefaults['sector'] ?? ''), 512) ?>;
    sectorSearchInput.value = defaultSector;
    sectorInput.value = defaultSector;
    pincodeInput.value = defaultPin;
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

// Ordering active check — show popup if ordering is off
const orderingActive = <?php echo e(($orderingActive ?? true) ? 'true' : 'false'); ?>;

function checkOrderingActive(e) {
    if (!orderingActive) {
        e.preventDefault();
        const modal = document.getElementById('orderingInactiveModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        return false;
    }
    return true;
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\BMDU Work\FarmSea-dashboard\resources\views\frontend\checkout.blade.php ENDPATH**/ ?>