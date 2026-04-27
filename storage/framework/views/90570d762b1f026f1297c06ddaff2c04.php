<?php $__env->startSection('title', 'Orders'); ?>
<?php $__env->startSection('page_title', 'FarmSea Orders'); ?>

<?php $__env->startSection('content'); ?>
<?php
    $statusOptions = ['pending', 'confirmed', 'processing', 'out_for_delivery', 'delivered', 'cancelled'];
?>
<div class="p-4 md:p-8 space-y-6">

    <form method="GET" class="flex flex-wrap items-center justify-between gap-3">
        <h1 class="text-2xl font-bold text-gray-800">Live Orders</h1>
        <div class="flex flex-wrap items-center gap-3">
            <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Search customer / ID..."
                   class="border rounded px-3 py-1.5 text-sm outline-none focus:ring-2 focus:ring-blue-500">
            <select name="status" class="border rounded px-3 py-1.5 text-sm outline-none">
                <option value="">All Status</option>
                <?php $__currentLoopData = $statusOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($status); ?>" <?php echo e(request('status') === $status ? 'selected' : ''); ?>>
                        <?php echo e(ucwords(str_replace('_', ' ', $status))); ?>

                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <button type="submit" class="rounded bg-blue-600 px-4 py-1.5 text-sm text-white">Filter</button>
            <a href="<?php echo e(route('dashboard.orders')); ?>" class="rounded bg-gray-200 px-4 py-1.5 text-sm text-gray-700">Reset</a>
        </div>
    </form>

    <div class="overflow-x-auto rounded-lg border bg-white shadow-sm">
        <table class="w-full min-w-[1180px] text-left">
            <thead class="border-b bg-gray-50">
                <tr>
                    <th class="px-4 py-4 text-xs font-bold uppercase text-gray-700">Order ID</th>
                    <th class="px-4 py-4 text-xs font-bold uppercase text-gray-700">Date & Time</th>
                    <th class="px-4 py-4 text-xs font-bold uppercase text-gray-700">Customer</th>
                    <th class="px-4 py-4 text-xs font-bold uppercase text-gray-700">Items</th>
                    <th class="px-4 py-4 text-xs font-bold uppercase text-gray-700">Total</th>
                    <th class="px-4 py-4 text-xs font-bold uppercase text-gray-700">Payment</th>
                    <th class="px-4 py-4 text-xs font-bold uppercase text-gray-700">Delivery Boy</th>
                    <th class="px-4 py-4 text-xs font-bold uppercase text-gray-700">Status</th>
                    <th class="px-4 py-4 text-center text-xs font-bold uppercase text-gray-700">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php $__empty_1 = true; $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="transition hover:bg-slate-50">
                        <td class="px-4 py-4 text-sm font-bold text-blue-600">#ORD-<?php echo e(str_pad($order->id, 4, '0', STR_PAD_LEFT)); ?></td>
                        <td class="px-4 py-4 text-sm text-gray-600">
                            <p><?php echo e($order->created_at->format('d M Y')); ?></p>
                            <p class="text-xs text-gray-400"><?php echo e($order->created_at->format('h:i A')); ?></p>
                        </td>
                        <td class="px-4 py-4 text-sm text-gray-800"><?php echo e($order->user->name ?? 'Guest'); ?></td>
                        <td class="px-4 py-4 text-sm text-gray-600"><?php echo e($order->items->count()); ?> item(s)</td>
                        <td class="px-4 py-4 text-sm font-bold text-gray-900">Rs<?php echo e(number_format($order->total, 2)); ?></td>
                        <td class="px-4 py-4 text-xs font-semibold uppercase text-gray-600"><?php echo e($order->payment_method ?? 'COD'); ?></td>
                        <td class="px-4 py-4 text-sm text-gray-600">
                            <?php if($order->deliveryBoy): ?>
                                <div class="font-bold text-gray-800"><?php echo e($order->deliveryBoy->partner_name); ?></div>
                                <div class="text-xs text-gray-500"><?php echo e($order->deliveryBoy->phone_number); ?></div>
                                <div class="text-xs text-gray-400"><?php echo e($order->deliveryBoy->area); ?></div>
                            <?php else: ?>
                                <span class="text-xs text-gray-400">Not assigned</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-4 py-4">
                            <span class="rounded-full px-2 py-1 text-[10px] font-bold uppercase <?php echo e($order->status_badge_class); ?>">
                                <?php echo e($order->status_label); ?>

                            </span>
                        </td>
                        <td class="px-4 py-4">
                            <div class="flex items-start justify-center gap-2">
                                <a href="<?php echo e(route('dashboard.orders.show', $order)); ?>"
                                   class="mt-[28px] rounded-lg bg-blue-50 px-3 py-1.5 text-xs font-bold text-blue-600 hover:bg-blue-100">
                                    <i class="fa-solid fa-eye"></i> View
                                </a>

                                <form method="POST" action="<?php echo e(route('dashboard.orders.status', $order)); ?>" class="inline-flex flex-col gap-2 rounded-lg border border-gray-200 bg-gray-50 p-2">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('PATCH'); ?>
                                    <select name="status" onchange="toggleDeliveryBoySelect(this, '<?php echo e($order->id); ?>')" class="border rounded px-2 py-1 text-xs outline-none">
                                        <?php $__currentLoopData = $statusOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($status); ?>" <?php echo e($order->status === $status ? 'selected' : ''); ?>>
                                                <?php echo e(ucwords(str_replace('_', ' ', $status))); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>

                                    <select name="delivery_boy_id" id="delivery-boy-select-<?php echo e($order->id); ?>"
                                            class="border rounded px-2 py-1 text-xs outline-none <?php echo e($order->status === 'out_for_delivery' ? '' : 'hidden'); ?>">
                                        <option value="">Choose delivery boy</option>
                                        <?php $__currentLoopData = $deliveryBoysByOrder[$order->id] ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $deliveryBoy): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($deliveryBoy->id); ?>" <?php echo e($order->delivery_boy_id === $deliveryBoy->id ? 'selected' : ''); ?>>
                                                <?php echo e($deliveryBoy->partner_name); ?> | <?php echo e($deliveryBoy->phone_number); ?> | <?php echo e($deliveryBoy->area); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>

                                    <button type="submit" class="rounded bg-blue-600 px-3 py-1 text-[11px] font-bold text-white hover:bg-blue-700">
                                        Save
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="9" class="px-4 py-8 text-center text-gray-400">No orders found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div><?php echo e($orders->withQueryString()->links()); ?></div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
function toggleDeliveryBoySelect(select, orderId) {
    const deliverySelect = document.getElementById(`delivery-boy-select-${orderId}`);

    if (!deliverySelect) {
        return;
    }

    const shouldShow = select.value === 'out_for_delivery';
    deliverySelect.classList.toggle('hidden', !shouldShow);

    if (!shouldShow) {
        deliverySelect.value = '';
    }
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\FarmSea-dashboard\resources\views\dashboard\orders\index.blade.php ENDPATH**/ ?>