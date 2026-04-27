<?php $__env->startSection('title', 'Order Detail'); ?>
<?php $__env->startSection('page_title', 'Order Details'); ?>

<?php $__env->startSection('content'); ?>
<?php
    $statusOptions = ['pending', 'confirmed', 'processing', 'out_for_delivery', 'delivered', 'cancelled'];
?>
<div class="p-4 md:p-6">
    <div class="mb-4 flex items-center justify-between">
        <a href="<?php echo e(route('dashboard.orders')); ?>" class="flex items-center gap-1 text-sm text-blue-600 hover:underline">
            <i class="fa-solid fa-arrow-left text-xs"></i> Back
        </a>
        <div class="flex items-center gap-2">
            <a href="#" title="Export Excel" class="flex h-9 w-9 items-center justify-center rounded bg-green-600 text-sm text-white hover:bg-green-700">
                <i class="fa-solid fa-file-excel"></i>
            </a>
            <a href="#" title="Export PDF" class="flex h-9 w-9 items-center justify-center rounded bg-red-600 text-sm text-white hover:bg-red-700">
                <i class="fa-solid fa-file-pdf"></i>
            </a>
            <button onclick="window.print()" title="Print" class="flex h-9 w-9 items-center justify-center rounded bg-teal-600 text-sm text-white hover:bg-teal-700">
                <i class="fa-solid fa-print"></i>
            </button>
        </div>
    </div>

    <div class="mb-4 rounded-xl border bg-white p-5">
        <h2 class="mb-4 flex items-center gap-2 text-lg font-bold text-blue-600">
            <i class="fa-solid fa-circle-check text-blue-500"></i> Order Details
        </h2>

        <div class="mb-4 flex justify-end">
            <div class="flex items-center gap-2 text-sm">
                <span class="font-medium text-gray-600">Search:</span>
                <input type="text" id="orderSearch" class="w-48 rounded border border-gray-300 px-3 py-1.5 text-sm outline-none focus:border-blue-400">
            </div>
        </div>

        <div class="overflow-hidden rounded-lg border border-gray-200">
            <div class="flex items-center justify-between border-b bg-gray-50 px-4 py-3">
                <div class="flex items-center gap-3">
                    <span class="rounded bg-gray-800 px-2 py-1 text-xs font-bold text-white">#1</span>
                    <span class="text-sm font-semibold text-gray-800">Order: <?php echo e($order->order_number ?? 'ORD-' . $order->id); ?></span>
                    <span class="rounded px-2.5 py-1 text-xs font-bold <?php echo e($order->status_badge_class); ?>">
                        <?php echo e($order->status_label); ?>

                    </span>
                </div>
                <a href="#" onclick="window.print()" class="flex items-center gap-1.5 rounded border border-red-300 px-3 py-1.5 text-xs font-semibold text-red-600 hover:bg-red-50">
                    <i class="fa-solid fa-download text-xs"></i> Download PDF
                </a>
            </div>

            <div class="border-b bg-white px-4 py-2">
                <span class="text-xs text-gray-500">
                    <i class="fa-regular fa-calendar mr-1"></i>
                    <?php echo e($order->created_at->format('d M Y, h:i A')); ?>

                </span>
            </div>

            <div class="border-b">
                <div class="px-5 py-4">
                    <p class="mb-2 flex items-center gap-1 text-xs font-bold text-teal-600">
                        <i class="fa-regular fa-user"></i> User
                    </p>
                    <p class="text-sm font-bold text-gray-800"><?php echo e($order->user->name ?? 'Guest'); ?></p>
                    <?php if($order->user->phone ?? null): ?>
                        <p class="mt-1 flex items-center gap-1 text-xs text-gray-600">
                            <i class="fa-solid fa-phone text-gray-400"></i> <?php echo e($order->user->phone); ?>

                        </p>
                    <?php endif; ?>
                    <?php if($order->shipping_address): ?>
                        <p class="mt-1 flex items-start gap-1 text-xs text-gray-500">
                            <i class="fa-solid fa-location-dot mt-0.5 text-gray-400"></i>
                            <span>
                                <?php if(is_array($order->shipping_address)): ?>
                                    <?php echo e(implode(', ', array_filter($order->shipping_address))); ?>

                                <?php else: ?>
                                    <?php echo e($order->shipping_address); ?>

                                <?php endif; ?>
                            </span>
                        </p>
                    <?php endif; ?>

                    <div class="mt-4 rounded-lg border border-dashed border-blue-200 bg-blue-50 px-3 py-3">
                        <p class="text-xs font-bold uppercase tracking-wide text-blue-700">Assigned Delivery Boy</p>
                        <?php if($order->deliveryBoy): ?>
                            <p class="mt-2 text-sm font-bold text-gray-800"><?php echo e($order->deliveryBoy->partner_name); ?></p>
                            <p class="mt-1 text-xs text-gray-600"><?php echo e($order->deliveryBoy->phone_number); ?></p>
                            <p class="mt-1 text-xs text-gray-500"><?php echo e($order->deliveryBoy->area); ?></p>
                        <?php else: ?>
                            <p class="mt-2 text-xs text-gray-500">No delivery boy assigned yet.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="border-b bg-white px-5 py-3">
                <p class="mb-3 flex items-center gap-1 text-xs font-bold text-teal-600">
                    <i class="fa-solid fa-cart-shopping"></i> Products
                </p>

                <div class="overflow-x-auto">
                    <table class="w-full min-w-[700px] text-sm">
                        <thead>
                            <tr class="border-b border-gray-200">
                                <th class="pb-2 pr-4 text-left text-xs font-bold uppercase text-gray-600">Image</th>
                                <th class="pb-2 pr-4 text-left text-xs font-bold uppercase text-gray-600">Product</th>
                                <th class="pb-2 pr-4 text-left text-xs font-bold uppercase text-gray-600">QTY</th>
                                <th class="pb-2 pr-4 text-left text-xs font-bold uppercase text-gray-600">Unit</th>
                                <th class="pb-2 pr-4 text-left text-xs font-bold uppercase text-gray-600">MRP</th>
                                <th class="pb-2 pr-4 text-left text-xs font-bold uppercase text-gray-600">Selling</th>
                                <th class="pb-2 pr-4 text-left text-xs font-bold uppercase text-gray-600">Save Offer</th>
                                <th class="pb-2 pr-4 text-left text-xs font-bold uppercase text-gray-600">Vendor</th>
                                <th class="pb-2 text-left text-xs font-bold uppercase text-gray-600">Admin</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <?php $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td class="py-3 pr-4">
                                        <?php $imgs = $item->product->images ?? []; ?>
                                        <?php if(count($imgs)): ?>
                                            <img src="<?php echo e(asset('storage/' . $imgs[0])); ?>" class="h-12 w-12 rounded border object-cover">
                                        <?php else: ?>
                                            <div class="flex h-12 w-12 items-center justify-center rounded border bg-gray-100 text-gray-400">
                                                <i class="fa-regular fa-image text-xs"></i>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td class="py-3 pr-4 text-sm font-medium text-gray-800"><?php echo e($item->product->name ?? 'Deleted Product'); ?></td>
                                    <td class="py-3 pr-4 text-sm text-gray-700"><?php echo e($item->quantity); ?></td>
                                    <td class="py-3 pr-4 text-sm text-gray-700"><?php echo e($item->unit ?? $item->product->unit ?? '-'); ?></td>
                                    <td class="py-3 pr-4 text-sm text-gray-700">Rs<?php echo e(number_format($item->mrp ?? $item->unit_price, 2)); ?></td>
                                    <td class="py-3 pr-4 text-sm text-gray-700">Rs<?php echo e(number_format($item->unit_price, 2)); ?></td>
                                    <td class="py-3 pr-4">
                                        <?php if($item->save_offer): ?>
                                            <span class="rounded bg-amber-400 px-2 py-0.5 text-xs font-bold text-white"><?php echo e($item->save_offer); ?>%</span>
                                        <?php else: ?>
                                            <span class="text-sm text-gray-400">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="py-3 pr-4 text-sm text-gray-700">Rs<?php echo e(number_format($item->vendor_amount ?? 0, 2)); ?></td>
                                    <td class="py-3 text-sm text-gray-700">Rs<?php echo e(number_format($item->admin_amount ?? 0, 2)); ?></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 flex justify-end">
                    <div class="w-full max-w-sm">
                        <div class="flex justify-between border-b border-gray-100 py-2 text-sm">
                            <span class="font-semibold text-gray-700">Delivery</span>
                            <span class="text-gray-700">Rs<?php echo e(number_format($order->delivery_charge ?? $order->shipping_cost ?? 0, 2)); ?></span>
                        </div>
                        <div class="flex justify-between border-b border-gray-100 py-2 text-sm">
                            <span class="font-semibold text-gray-700">Platform</span>
                            <span class="text-gray-700">Rs<?php echo e(number_format($order->platform_fee ?? 0, 2)); ?></span>
                        </div>
                        <div class="flex justify-between border-b border-gray-100 py-2 text-sm">
                            <span class="font-semibold text-gray-700">Admin Commission</span>
                            <span class="text-gray-700">Rs<?php echo e(number_format($order->admin_commission ?? 0, 2)); ?></span>
                        </div>
                        <div class="mt-1 flex justify-between rounded bg-green-50 px-3 py-2.5 text-sm">
                            <span class="font-bold text-gray-800">Grand Total</span>
                            <span class="font-bold text-gray-800">Rs<?php echo e(number_format($order->total, 2)); ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-gray-50 px-5 py-4">
                <form method="POST" action="<?php echo e(route('dashboard.orders.status', $order)); ?>" class="flex flex-wrap items-center gap-3">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PATCH'); ?>
                    <label class="text-sm font-semibold text-gray-700">Update Status:</label>
                    <select name="status" id="detailOrderStatus" onchange="toggleDetailDeliveryBoy()" class="rounded border border-gray-300 px-3 py-2 text-sm outline-none focus:border-blue-400">
                        <?php $__currentLoopData = $statusOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($status); ?>" <?php echo e($order->status === $status ? 'selected' : ''); ?>>
                                <?php echo e(ucwords(str_replace('_', ' ', $status))); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <select name="delivery_boy_id" id="detailDeliveryBoy" class="rounded border border-gray-300 px-3 py-2 text-sm outline-none focus:border-blue-400 <?php echo e($order->status === 'out_for_delivery' ? '' : 'hidden'); ?>">
                        <option value="">Choose delivery boy</option>
                        <?php $__currentLoopData = $deliveryBoys; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $deliveryBoy): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($deliveryBoy->id); ?>" <?php echo e($order->delivery_boy_id === $deliveryBoy->id ? 'selected' : ''); ?>>
                                <?php echo e($deliveryBoy->partner_name); ?> | <?php echo e($deliveryBoy->phone_number); ?> | <?php echo e($deliveryBoy->area); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <button type="submit" class="rounded bg-blue-600 px-5 py-2 text-sm font-bold text-white hover:bg-blue-700">
                        Update
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
function toggleDetailDeliveryBoy() {
    const statusSelect = document.getElementById('detailOrderStatus');
    const deliveryBoySelect = document.getElementById('detailDeliveryBoy');

    if (!statusSelect || !deliveryBoySelect) {
        return;
    }

    const shouldShow = statusSelect.value === 'out_for_delivery';
    deliveryBoySelect.classList.toggle('hidden', !shouldShow);

    if (!shouldShow) {
        deliveryBoySelect.value = '';
    }
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\FarmSea-dashboard\resources\views\dashboard\orders\show.blade.php ENDPATH**/ ?>