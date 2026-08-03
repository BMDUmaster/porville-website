<?php $__env->startSection('title', 'Checkout'); ?>

<?php $__env->startSection('styles'); ?>
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
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<?php
    $isCodAvailable = ($pricing['subtotal'] ?? 0) <= 2000;
    $paymentOptions = $isCodAvailable
        ? ['COD' => 'Cash on Delivery', 'online' => 'Online Payment', 'upi' => 'UPI']
        : ['online' => 'Online Payment', 'upi' => 'UPI'];
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
                    <div class="flex items-center gap-2 border-b px-6 py-4">
                        <div class="flex h-7 w-7 items-center justify-center rounded-full bg-blue-700 text-xs font-bold text-white">1</div>
                        <h2 class="nunito text-base font-extrabold text-gray-800">Contact Information</h2>
                    </div>
                    <div class="grid grid-cols-1 gap-4 px-6 py-5 sm:grid-cols-2">
                        <div>
                            <label class="mb-1 block text-xs font-semibold text-gray-600">First Name *</label>
                            <input type="text" name="first_name" value="<?php echo e($checkoutDefaults['first_name'] ?? ''); ?>" required readonly
                                   class="w-full cursor-not-allowed rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-500 outline-none">
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-semibold text-gray-600">Last Name</label>
                            <input type="text" name="last_name" value="<?php echo e($checkoutDefaults['last_name'] ?? ''); ?>" readonly
                                   class="w-full cursor-not-allowed rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-500 outline-none">
                        </div>
                        <div class="sm:col-span-2">
                            <label class="mb-1 block text-xs font-semibold text-gray-600">Email *</label>
                            <input type="email" name="email" value="<?php echo e($checkoutDefaults['email'] ?? ''); ?>" required readonly
                                   class="w-full cursor-not-allowed rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-500 outline-none">
                        </div>
                        <div class="sm:col-span-2">
                            <label class="mb-1 block text-xs font-semibold text-gray-600">Phone *</label>
                            <input type="tel" name="phone" id="checkoutPhoneInput" value="<?php echo e($checkoutDefaults['phone'] ?? ''); ?>" required
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
                        <?php if(!empty($pastAddresses)): ?>
                            <button type="button" onclick="showNewAddressForm()" id="addNewAddressBtn"
                                    class="text-xs font-bold text-blue-700 hover:text-blue-800 flex items-center gap-1">
                                <i class="fa-solid fa-plus text-[10px]"></i> Add New
                            </button>
                        <?php endif; ?>
                    </div>
                    <div class="px-6 py-5 space-y-4">
                        <?php if(!empty($pastAddresses)): ?>
                            <div id="savedAddressesContainer" class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-4">
                                <?php $__currentLoopData = $pastAddresses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $addr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="address-card border rounded-xl p-4 cursor-pointer relative hover:border-blue-500 transition-all duration-200 flex flex-col justify-between"
                                         data-index="<?php echo e($index); ?>"
                                         onclick="selectAddressCard(<?php echo e($index); ?>)">
                                         <div>
                                             <div class="flex justify-between items-start">
                                                 <p class="text-sm font-bold text-gray-800"><?php echo e($addr['name'] ?? ''); ?></p>
                                             </div>
                                             <p class="text-xs text-gray-500 mt-1.5 leading-relaxed"><?php echo e($addr['address']); ?></p>
                                             <?php if(!empty($addr['sector'])): ?>
                                                 <p class="text-xs text-gray-500 leading-relaxed">Sector: <?php echo e($addr['sector']); ?></p>
                                             <?php endif; ?>
                                             <p class="text-xs text-gray-500 leading-relaxed"><?php echo e($addr['city']); ?>, <?php echo e($addr['state']); ?> - <?php echo e($addr['pincode']); ?></p>
                                             <?php if(!empty($addr['phone'])): ?>
                                                 <p class="text-xs text-gray-500 mt-1 leading-relaxed"><i class="fa-solid fa-phone text-[9px] mr-1 text-slate-400"></i><?php echo e($addr['phone']); ?></p>
                                             <?php endif; ?>
                                         </div>
                                         
                                         <div class="mt-4 pt-3 border-t border-slate-50 flex items-center justify-between">
                                             <span class="text-[10px] font-bold text-green-700 select-badge items-center gap-1 hidden">
                                                 <i class="fa-solid fa-circle-check text-[10px]"></i> Selected
                                             </span>
                                             <button type="button" onclick="editAddressCard(event, <?php echo e($index); ?>)" 
                                                     class="text-xs text-blue-600 hover:text-blue-800 font-semibold flex items-center gap-1 ml-auto">
                                                 <i class="fa-regular fa-pen-to-square text-[10px]"></i> Edit
                                             </button>
                                         </div>
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
                                    <label class="mb-1 block text-xs font-semibold text-gray-600">PIN Code *</label>
                                    <select name="pincode" id="shippingPincodeInput" required
                                            class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm outline-none focus:border-blue-500">
                                        <option value="">Select PIN Code</option>
                                        <?php $__currentLoopData = $pinSectors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pinCode => $sectors): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($pinCode); ?>"><?php echo e($pinCode); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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

                <div id="checkoutCouponSection" class="rounded-2xl border bg-white p-5">
                    <label class="mb-2 block text-xs font-semibold text-gray-600">Coupon Code (optional)</label>
                    <div class="flex flex-col gap-2 sm:flex-row">
                        <input type="text" name="coupon_code" id="couponCodeInput" value="<?php echo e($initialCouponCode); ?>" placeholder="Enter coupon code"
                               class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm uppercase outline-none focus:border-blue-500">
                        <button type="button" id="applyCouponButton"
                                class="<?php echo e($initialCouponCode ? '' : 'hidden'); ?> rounded-xl bg-green-600 px-5 py-2.5 text-sm font-bold text-white transition hover:bg-green-700">
                            Apply
                        </button>
                    </div>
                    <p id="couponMessage" class="mt-2 hidden text-xs font-semibold"></p>
                    <?php if(($availableCoupons ?? collect())->isNotEmpty()): ?>
                        <div class="mt-4 flex flex-wrap gap-2">
                            <?php $__currentLoopData = $availableCoupons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $coupon): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <button type="button"
                                        data-coupon-code="<?php echo e($coupon->code); ?>"
                                        class="coupon-chip rounded-full border border-green-200 bg-green-50 px-3 py-1.5 text-[11px] font-bold text-green-700 transition hover:border-green-500 hover:bg-white">
                                    <?php echo e($coupon->code); ?>

                                </button>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php endif; ?>
                </div>

                <button type="submit" id="placeOrderButton"
                        class="flex w-full items-center justify-center gap-2 rounded-2xl bg-blue-700 py-4 text-sm font-bold text-white transition hover:bg-blue-800">
                    <i class="fa-solid fa-lock text-xs"></i>
                    Place Order - <span id="placeOrderTotal">&#8377;<?php echo e(number_format($pricing['total'], 2)); ?></span>
                </button>
            </form>
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

            <div id="checkoutSummarySticky" class="checkout-summary-sticky mt-6 overflow-hidden rounded-2xl border bg-white shadow-sm lg:max-h-[calc(100vh-194px)]">
                <div class="h-1 bg-gradient-to-r from-blue-700 to-orange-400"></div>
                <div class="border-b px-5 py-4">
                    <h2 class="nunito text-base font-extrabold text-gray-800">Order Summary</h2>
                </div>
                <div class="max-h-64 space-y-3 overflow-y-auto border-b px-5 py-4 lg:max-h-[calc(100vh-430px)]">
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
        </aside>
    </div>
</div>

<script>
function alignCheckoutSummaryWithCoupon() {
    const grid = document.getElementById('checkoutGrid');
    const coupon = document.getElementById('checkoutCouponSection');
    const promo = document.getElementById('checkoutPromoCard');
    const arrivals = document.getElementById('checkoutNewArrivals');
    const mealBanner = document.getElementById('checkoutMealBanner');
    const trustCard = document.getElementById('checkoutTrustCard');
    const similarProducts = document.getElementById('checkoutSimilarProducts');
    const summary = document.getElementById('checkoutSummarySticky');

    if (!grid || !coupon || !promo || !summary) return;

    if (window.innerWidth < 1024) {
        summary.style.marginTop = '';
        return;
    }

    const couponOffset = coupon.getBoundingClientRect().top - grid.getBoundingClientRect().top;
    const arrivalsHeight = arrivals ? arrivals.offsetHeight + 24 : 0;
    const mealBannerHeight = mealBanner ? mealBanner.offsetHeight + 24 : 0;
    const trustCardHeight = trustCard ? trustCard.offsetHeight + 24 : 0;
    const similarHeight = similarProducts ? similarProducts.offsetHeight + 24 : 0;
    const gapAfterPromo = Math.max(24, couponOffset - promo.offsetHeight - arrivalsHeight - mealBannerHeight - trustCardHeight - similarHeight - 70);
    summary.style.marginTop = `${gapAfterPromo}px`;
}

window.addEventListener('load', alignCheckoutSummaryWithCoupon);
window.addEventListener('resize', alignCheckoutSummaryWithCoupon);

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

function sanitizeCheckoutPhone() {
    if (!phoneInput) {
        return;
    }

    phoneInput.value = phoneInput.value.replace(/\D/g, '').slice(0, 12);
}

phoneInput?.addEventListener('input', sanitizeCheckoutPhone);
sanitizeCheckoutPhone();

// Address Selection Logic
const pastAddresses = <?php echo json_encode($pastAddresses ?? [], 15, 512) ?>;
const hasErrors = <?php echo json_encode($errors->has('address') || $errors->has('city') || $errors->has('state') || $errors->has('pincode') || $errors->has('sector'), 15, 512) ?>;
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

const pinToSectors = <?php echo json_encode($pinSectors, 15, 512) ?>;

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
    streetInput.value = <?php echo json_encode(old('address', $checkoutDefaults['address'] ?? ''), 512) ?>;
    cityInput.value = 'Noida';
    stateInput.value = 'UP';
    const defaultPin = <?php echo json_encode(old('pincode', $checkoutDefaults['pincode'] ?? ''), 512) ?>;
    pincodeInput.value = defaultPin;
    populateSectors(defaultPin);
    sectorInput.value = <?php echo json_encode(old('sector', $checkoutDefaults['sector'] ?? ''), 512) ?>;
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\BMDU Work\FarmSea-dashboard\resources\views\frontend\checkout.blade.php ENDPATH**/ ?>