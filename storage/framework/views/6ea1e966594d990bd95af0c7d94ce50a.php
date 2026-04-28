
<?php $__env->startSection('title', 'Order Details'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-3xl mx-auto px-4 py-8">
    <div class="flex items-center gap-3 mb-6">
        <a href="<?php echo e(route('frontend.orders')); ?>" class="text-blue-600 hover:underline text-sm flex items-center gap-1">
            <i class="fa-solid fa-arrow-left text-xs"></i> Back to Orders
        </a>
        <a href="<?php echo e(route('frontend.order.invoice', ['id' => $order->id, 'download' => 1])); ?>"
           target="_blank"
           class="inline-flex items-center gap-2 rounded-lg bg-[#0f766e] px-3 py-2 text-xs font-bold text-white transition hover:bg-[#0d665f]">
            <i class="fa-solid fa-file-arrow-down text-xs"></i> Download Invoice
        </a>
    </div>

    <div class="bg-white rounded-2xl border overflow-hidden">
        <div class="px-6 py-4 border-b flex items-center justify-between">
            <div>
                <h1 class="font-bold text-gray-800"><?php echo e($order->order_number ?? '#'.$order->id); ?></h1>
                <p class="text-xs text-gray-400 mt-1"><?php echo e($order->created_at->format('d M Y, h:i A')); ?></p>
            </div>
            <span class="text-xs font-bold px-3 py-1.5 rounded-full <?php echo e($order->status_badge_class); ?>">
                <?php echo e($order->status_label); ?>

            </span>
        </div>

        
        <div class="px-6 py-4 border-b">
            <h2 class="font-semibold text-gray-700 mb-3 text-sm flex items-center gap-2">
                <i class="fa-solid fa-cart-shopping text-teal-600"></i> Products
            </h2>
            <table class="w-full text-sm">
                <thead class="border-b">
                    <tr class="text-xs font-bold text-gray-500 uppercase">
                        <th class="pb-2 text-left">Product</th>
                        <th class="pb-2 text-left">QTY</th>
                        <th class="pb-2 text-left">Unit Price</th>
                        <th class="pb-2 text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td class="py-3 font-medium text-gray-800"><?php echo e($item->product->name ?? 'Deleted Product'); ?></td>
                        <td class="py-3 text-gray-600">
                            <?php echo e($item->quantity); ?>

                            <?php if($item->variant_label): ?>
                                <span class="text-xs text-gray-400">(<?php echo e($item->variant_label); ?>)</span>
                            <?php endif; ?>
                        </td>
                        <td class="py-3 text-gray-600">₹<?php echo e(number_format($item->unit_price, 2)); ?></td>
                        <td class="py-3 text-right font-bold text-gray-800">₹<?php echo e(number_format($item->subtotal, 2)); ?></td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>

        
        <div class="px-6 py-4 flex justify-end">
            <div class="w-64 space-y-2 text-sm">
                <div class="flex justify-between"><span class="text-gray-500">Subtotal</span><span>₹<?php echo e(number_format($order->subtotal, 2)); ?></span></div>
                <?php if($order->discount > 0): ?>
                <div class="flex justify-between text-green-600"><span>Discount</span><span>-₹<?php echo e(number_format($order->discount, 2)); ?></span></div>
                <?php endif; ?>
                <div class="flex justify-between"><span class="text-gray-500">Delivery</span><span>₹<?php echo e(number_format($order->delivery_charge ?? $order->shipping_cost ?? 0, 2)); ?></span></div>
                <hr class="border-gray-100">
                <div class="flex justify-between font-bold text-gray-800 text-base">
                    <span>Grand Total</span>
                    <span>₹<?php echo e(number_format($order->total, 2)); ?></span>
                </div>
            </div>
        </div>

        
        <?php if($order->shipping_address): ?>
        <div class="px-6 py-4 border-t bg-gray-50">
            <h2 class="font-semibold text-gray-700 mb-2 text-sm">Shipping Address</h2>
            <?php if(is_array($order->shipping_address)): ?>
                <p class="text-sm text-gray-600"><?php echo e($order->shipping_address['name'] ?? ''); ?></p>
                <p class="text-sm text-gray-600"><?php echo e($order->shipping_address['address'] ?? ''); ?>, <?php echo e($order->shipping_address['city'] ?? ''); ?></p>
                <p class="text-sm text-gray-600"><?php echo e($order->shipping_address['state'] ?? ''); ?> — <?php echo e($order->shipping_address['pincode'] ?? ''); ?></p>
            <?php else: ?>
                <p class="text-sm text-gray-600"><?php echo e($order->shipping_address); ?></p>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\FarmSea-dashboard\resources\views\frontend\order-detail.blade.php ENDPATH**/ ?>