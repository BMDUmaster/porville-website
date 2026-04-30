<?php $__env->startSection('title', 'Checkout'); ?>

<?php $__env->startSection('content'); ?>
<?php
    $selectedPaymentMethod = old('payment_method', 'COD');
?>
<div class="max-w-5xl mx-auto px-4 py-8">
    <h1 class="nunito font-extrabold text-2xl text-gray-800 mb-6">Checkout</h1>

    <div class="flex flex-col gap-6 lg:flex-row">
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
                            <input type="text" name="first_name" value="<?php echo e(old('first_name', $checkoutDefaults['first_name'] ?? '')); ?>" required
                                   class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm outline-none focus:border-blue-500">
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-semibold text-gray-600">Last Name *</label>
                            <input type="text" name="last_name" value="<?php echo e(old('last_name', $checkoutDefaults['last_name'] ?? '')); ?>" required
                                   class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm outline-none focus:border-blue-500">
                        </div>
                        <div class="sm:col-span-2">
                            <label class="mb-1 block text-xs font-semibold text-gray-600">Email *</label>
                            <input type="email" name="email" value="<?php echo e(old('email', $checkoutDefaults['email'] ?? '')); ?>" required
                                   class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm outline-none focus:border-blue-500">
                        </div>
                        <div class="sm:col-span-2">
                            <label class="mb-1 block text-xs font-semibold text-gray-600">Phone *</label>
                            <input type="tel" name="phone" value="<?php echo e(old('phone', $checkoutDefaults['phone'] ?? '')); ?>" required
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
                            <input type="text" name="address" value="<?php echo e(old('address', $checkoutDefaults['address'] ?? '')); ?>" required
                                   class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm outline-none focus:border-blue-500">
                        </div>
                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <label class="mb-1 block text-xs font-semibold text-gray-600">City *</label>
                                <input type="text" name="city" value="<?php echo e(old('city', $checkoutDefaults['city'] ?? '')); ?>" required
                                       class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm outline-none focus:border-blue-500">
                            </div>
                            <div>
                                <label class="mb-1 block text-xs font-semibold text-gray-600">State *</label>
                                <input type="text" name="state" value="<?php echo e(old('state', $checkoutDefaults['state'] ?? '')); ?>" required
                                       class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm outline-none focus:border-blue-500">
                            </div>
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-semibold text-gray-600">PIN Code *</label>
                            <input type="text" name="pincode" value="<?php echo e(old('pincode', $checkoutDefaults['pincode'] ?? '')); ?>" required
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
                        <?php $__currentLoopData = ['COD' => 'Cash on Delivery', 'online' => 'Online Payment', 'upi' => 'UPI']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <label class="flex cursor-pointer items-center gap-3 rounded-xl border border-gray-200 p-4 transition hover:border-blue-500">
                            <input type="radio" name="payment_method" value="<?php echo e($val); ?>" <?php echo e($selectedPaymentMethod === $val ? 'checked' : ''); ?> class="accent-blue-600">
                            <span class="text-sm font-semibold text-gray-700"><?php echo e($label); ?></span>
                        </label>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>

                <div class="rounded-2xl border bg-white p-5">
                    <label class="mb-2 block text-xs font-semibold text-gray-600">Coupon Code (optional)</label>
                    <input type="text" name="coupon_code" value="<?php echo e(old('coupon_code')); ?>" placeholder="Enter coupon code"
                           class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm outline-none focus:border-blue-500">
                </div>

                <button type="submit"
                        class="flex w-full items-center justify-center gap-2 rounded-2xl bg-blue-700 py-4 text-sm font-bold text-white transition hover:bg-blue-800">
                    <i class="fa-solid fa-lock text-xs"></i>
                    Place Order - &#8377;<?php echo e(number_format($pricing['total'], 2)); ?>

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
                            <p class="text-[10px] text-gray-400">Qty: <?php echo e($item['quantity']); ?></p>
                        </div>
                        <span class="flex-shrink-0 text-xs font-bold text-gray-800">&#8377;<?php echo e(number_format($item['subtotal'], 2)); ?></span>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <div class="space-y-2 px-5 py-4 text-sm">
                    <div class="flex justify-between"><span class="text-gray-500">Subtotal</span><span>&#8377;<?php echo e(number_format($pricing['subtotal'], 2)); ?></span></div>
                    <div class="flex justify-between"><span class="text-gray-500">Delivery</span><span>&#8377;<?php echo e(number_format($pricing['delivery_charge'], 2)); ?></span></div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Service Charge (<?php echo e(rtrim(rtrim(number_format($pricing['service_charge_percent'], 2), '0'), '.')); ?>%)</span>
                        <span>&#8377;<?php echo e(number_format($pricing['service_charge'], 2)); ?></span>
                    </div>
                    <hr class="border-gray-100">
                    <div class="flex justify-between text-base font-bold text-gray-800">
                        <span>Total</span>
                        <span class="text-blue-700">&#8377;<?php echo e(number_format($pricing['total'], 2)); ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\FarmSea-dashboard\resources\views/frontend/checkout.blade.php ENDPATH**/ ?>