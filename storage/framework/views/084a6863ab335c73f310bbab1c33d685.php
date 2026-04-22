
<?php $__env->startSection('title', 'Your Cart'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-6xl mx-auto px-4 py-8">
    <h1 class="nunito font-extrabold text-2xl text-gray-800 mb-6">
        Your Cart <span class="text-base font-semibold text-gray-400">(<?php echo e(count($items)); ?> items)</span>
    </h1>

    <?php if(empty($items)): ?>
        <div class="bg-white rounded-2xl border p-16 text-center">
            <i class="fa-solid fa-cart-shopping text-6xl text-gray-200 mb-4 block"></i>
            <p class="text-gray-500 font-semibold mb-4">Your cart is empty</p>
            <a href="<?php echo e(route('frontend.products')); ?>" class="inline-block bg-blue-700 text-white px-6 py-3 rounded-xl font-bold text-sm hover:bg-blue-800 transition">
                Continue Shopping
            </a>
        </div>
    <?php else: ?>
    <div class="flex flex-col lg:flex-row gap-6">
        
        <div class="flex-1 space-y-4">
            
            <div class="bg-white rounded-2xl border p-5">
                <p class="text-sm font-semibold text-gray-700 mb-3"><i class="fa-solid fa-tag text-blue-600 mr-1"></i>Have a coupon code?</p>
                <form method="GET" class="flex gap-2">
                    <input type="text" name="coupon" placeholder="Enter coupon code"
                           class="flex-1 px-4 py-2.5 text-sm border border-gray-200 rounded-xl outline-none focus:border-blue-500 bg-gray-50">
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white text-sm font-bold px-5 py-2.5 rounded-xl transition">Apply</button>
                </form>
            </div>

            <div class="bg-white rounded-2xl border overflow-hidden">
                <div class="hidden md:grid grid-cols-12 gap-3 px-6 py-3 border-b bg-gray-50 text-xs font-bold text-gray-400 uppercase tracking-wider">
                    <div class="col-span-6">Product</div>
                    <div class="col-span-2 text-center">Price</div>
                    <div class="col-span-2 text-center">Qty</div>
                    <div class="col-span-2 text-right">Total</div>
                </div>

                <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="px-6 py-5 border-b last:border-b-0 relative" id="cart-row-<?php echo e($item['key']); ?>">
                    <button onclick="removeCartItem('<?php echo e($item['key']); ?>')"
                            class="absolute top-4 right-4 w-7 h-7 rounded-full bg-gray-100 hover:bg-red-100 flex items-center justify-center text-gray-400 hover:text-red-500 transition">
                        <i class="fa-solid fa-xmark text-sm"></i>
                    </button>
                    <div class="flex flex-col md:grid md:grid-cols-12 md:gap-3 md:items-center gap-4">
                        <div class="col-span-6 flex gap-4 items-start">
                            <div class="w-20 h-20 rounded-xl bg-gray-50 border flex items-center justify-center flex-shrink-0 overflow-hidden">
                                <?php if($item['image']): ?>
                                    <img src="<?php echo e(asset('storage/'.$item['image'])); ?>" class="w-full h-full object-cover">
                                <?php else: ?>
                                    <span class="text-3xl">🥩</span>
                                <?php endif; ?>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-800 pr-6"><?php echo e($item['name']); ?></p>
                                <?php if($item['variant_label']): ?>
                                    <p class="text-xs text-gray-400 mt-1"><?php echo e($item['variant_label']); ?></p>
                                <?php endif; ?>
                                <p class="text-xs text-gray-400 mt-1"><?php echo e($item['unit']); ?></p>
                            </div>
                        </div>
                        <div class="col-span-2 text-left md:text-center">
                            <p class="text-sm font-bold text-gray-800">₹<?php echo e(number_format($item['price'], 2)); ?></p>
                        </div>
                        <div class="col-span-2 flex justify-start md:justify-center">
                            <div class="flex items-center border border-gray-200 rounded-lg overflow-hidden">
                                <button onclick="updateCartQty('<?php echo e($item['key']); ?>', <?php echo e($item['quantity'] - 1); ?>)"
                                        class="w-8 h-8 flex items-center justify-center text-gray-500 hover:bg-gray-100 font-bold">−</button>
                                <span class="text-sm font-bold px-3 border-x border-gray-200 h-8 flex items-center" id="qty-<?php echo e($item['key']); ?>"><?php echo e($item['quantity']); ?></span>
                                <button onclick="updateCartQty('<?php echo e($item['key']); ?>', <?php echo e($item['quantity'] + 1); ?>)"
                                        class="w-8 h-8 flex items-center justify-center text-gray-500 hover:bg-gray-100 font-bold">+</button>
                            </div>
                        </div>
                        <div class="col-span-2 text-left md:text-right">
                            <p class="text-sm font-bold text-blue-700" id="subtotal-<?php echo e($item['key']); ?>">₹<?php echo e(number_format($item['subtotal'], 2)); ?></p>
                        </div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <a href="<?php echo e(route('frontend.products')); ?>" class="inline-flex items-center gap-2 text-sm text-blue-600 font-medium hover:underline">
                <i class="fa-solid fa-arrow-left text-xs"></i> Continue Shopping
            </a>
        </div>

        
        <div class="w-full lg:w-80 flex-shrink-0">
            <div class="bg-white rounded-2xl border overflow-hidden sticky top-24">
                <div class="h-1 bg-gradient-to-r from-blue-700 to-orange-400"></div>
                <div class="px-6 py-5 border-b">
                    <h2 class="nunito font-extrabold text-lg text-gray-800">Order Summary</h2>
                </div>
                <div class="px-6 py-5 space-y-3">
                    <?php $subtotal = collect($items)->sum('subtotal'); ?>
                    <div class="flex justify-between text-sm"><span class="text-gray-500">Subtotal</span><span class="font-semibold">₹<?php echo e(number_format($subtotal, 2)); ?></span></div>
                    <div class="flex justify-between text-sm"><span class="text-gray-500">Delivery</span><span class="text-green-600 font-semibold">FREE</span></div>
                    <hr class="border-gray-100">
                    <div class="flex justify-between"><span class="font-bold text-gray-800">Total</span><span class="nunito font-extrabold text-xl text-blue-700">₹<?php echo e(number_format($subtotal, 2)); ?></span></div>
                </div>
                <div class="px-6 pb-6">
                    <a href="<?php echo e(route('frontend.checkout')); ?>"
                       class="flex items-center justify-center gap-2 w-full bg-blue-700 hover:bg-blue-800 text-white font-bold py-3.5 rounded-xl text-sm transition">
                        <i class="fa-solid fa-lock text-xs"></i> Proceed to Checkout
                    </a>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
function removeCartItem(key) {
    fetch('<?php echo e(route("frontend.cart.remove")); ?>', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>', 'Accept': 'application/json' },
        body: JSON.stringify({ key })
    }).then(r => r.json()).then(d => { if (d.success) location.reload(); });
}
function updateCartQty(key, qty) {
    fetch('<?php echo e(route("frontend.cart.update")); ?>', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>', 'Accept': 'application/json' },
        body: JSON.stringify({ key, quantity: qty })
    }).then(r => r.json()).then(d => { if (d.success) location.reload(); });
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\FarmSea-dashboard\resources\views/frontend/cart.blade.php ENDPATH**/ ?>