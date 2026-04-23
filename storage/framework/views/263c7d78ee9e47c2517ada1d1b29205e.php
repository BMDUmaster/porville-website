
<?php $__env->startSection('title', 'Track Order'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-2xl mx-auto px-4 py-12">
    <h1 class="nunito font-extrabold text-2xl text-gray-800 mb-2">Track Your Order</h1>
    <p class="text-gray-500 text-sm mb-8">Enter your order number to track your delivery status.</p>

    <form method="GET" class="bg-white rounded-2xl border p-6 mb-6">
        <label class="block text-sm font-semibold text-gray-700 mb-2">Order Number</label>
        <div class="flex gap-3">
            <input type="text" name="order_number" value="<?php echo e(request('order_number')); ?>"
                   placeholder="e.g. ORD-ABCDEF123456"
                   class="flex-1 px-4 py-3 border border-gray-200 rounded-xl text-sm outline-none focus:border-blue-500">
            <button type="submit" class="bg-blue-700 hover:bg-blue-800 text-white font-bold px-6 py-3 rounded-xl text-sm transition">
                Track
            </button>
        </div>
    </form>

    <?php if(request('order_number')): ?>
        <?php if($order): ?>
        <div class="bg-white rounded-2xl border p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-bold text-gray-800"><?php echo e($order->order_number); ?></h2>
                <span class="text-xs font-bold px-3 py-1 rounded-full <?php echo e($order->status_badge_class); ?>">
                    <?php echo e(ucfirst($order->status)); ?>

                </span>
            </div>
            <p class="text-sm text-gray-500 mb-4">Placed on <?php echo e($order->created_at->format('d M Y, h:i A')); ?></p>

            
            <?php
                $steps = ['pending','confirmed','processing','shipped','delivered'];
                $currentIdx = array_search($order->status, $steps);
            ?>
            <div class="flex items-center gap-0 mb-6">
                <?php $__currentLoopData = $steps; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $idx => $step): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="flex items-center <?php echo e($idx < count($steps)-1 ? 'flex-1' : ''); ?>">
                    <div class="flex flex-col items-center">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold
                            <?php echo e($currentIdx !== false && $idx <= $currentIdx ? 'bg-green-600 text-white' : 'bg-gray-200 text-gray-400'); ?>">
                            <?php if($currentIdx !== false && $idx < $currentIdx): ?>
                                <i class="fa-solid fa-check text-xs"></i>
                            <?php else: ?>
                                <?php echo e($idx + 1); ?>

                            <?php endif; ?>
                        </div>
                        <p class="text-[9px] text-gray-500 mt-1 capitalize whitespace-nowrap"><?php echo e($step); ?></p>
                    </div>
                    <?php if($idx < count($steps)-1): ?>
                    <div class="flex-1 h-0.5 mx-1 <?php echo e($currentIdx !== false && $idx < $currentIdx ? 'bg-green-600' : 'bg-gray-200'); ?>"></div>
                    <?php endif; ?>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <div class="border-t pt-4">
                <p class="text-sm font-semibold text-gray-700 mb-2">Order Items</p>
                <?php $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="flex items-center gap-3 py-2 border-b last:border-b-0">
                    <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0">
                        <?php if($item->product && $item->product->images): ?>
                            <img src="<?php echo e(asset('storage/'.$item->product->images[0])); ?>" class="w-full h-full object-cover rounded-lg">
                        <?php else: ?>
                            <span>🥩</span>
                        <?php endif; ?>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-gray-800"><?php echo e($item->product->name ?? 'Product'); ?></p>
                        <p class="text-xs text-gray-400">Qty: <?php echo e($item->quantity); ?></p>
                    </div>
                    <p class="text-sm font-bold text-gray-800">₹<?php echo e(number_format($item->subtotal, 2)); ?></p>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <div class="flex justify-between pt-3 font-bold text-gray-800">
                    <span>Total</span>
                    <span>₹<?php echo e(number_format($order->total, 2)); ?></span>
                </div>
            </div>
        </div>
        <?php else: ?>
        <div class="bg-red-50 border border-red-200 rounded-2xl p-6 text-center">
            <i class="fa-solid fa-circle-xmark text-red-400 text-3xl mb-3 block"></i>
            <p class="font-semibold text-red-700">Order not found</p>
            <p class="text-sm text-red-500 mt-1">Please check your order number and try again.</p>
        </div>
        <?php endif; ?>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\FarmSea-dashboard\resources\views\frontend\track-order.blade.php ENDPATH**/ ?>