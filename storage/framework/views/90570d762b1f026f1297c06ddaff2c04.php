
<?php $__env->startSection('title', 'Orders'); ?>
<?php $__env->startSection('page_title', 'FarmSea Orders'); ?>

<?php $__env->startSection('content'); ?>
<div class="p-4 md:p-8 space-y-6">

    
    <form method="GET" class="flex flex-wrap gap-3 items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-800">Live Orders</h1>
        <div class="flex items-center gap-3 flex-wrap">
            <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Search customer / ID..."
                   class="border rounded px-3 py-1.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
            <select name="status" class="border rounded px-3 py-1.5 text-sm outline-none">
                <option value="">All Status</option>
                <?php $__currentLoopData = ['pending','confirmed','processing','shipped','delivered','cancelled']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($s); ?>" <?php echo e(request('status') === $s ? 'selected' : ''); ?>><?php echo e(ucfirst($s)); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <button type="submit" class="bg-blue-600 text-white px-4 py-1.5 rounded text-sm">Filter</button>
            <a href="<?php echo e(route('dashboard.orders')); ?>" class="bg-gray-200 text-gray-700 px-4 py-1.5 rounded text-sm">Reset</a>
        </div>
    </form>

    <div class="bg-white rounded-lg shadow-sm border overflow-x-auto">
        <table class="w-full text-left min-w-[900px]">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="px-4 py-4 text-xs font-bold uppercase text-gray-700">Order ID</th>
                    <th class="px-4 py-4 text-xs font-bold uppercase text-gray-700">Date & Time</th>
                    <th class="px-4 py-4 text-xs font-bold uppercase text-gray-700">Customer</th>
                    <th class="px-4 py-4 text-xs font-bold uppercase text-gray-700">Items</th>
                    <th class="px-4 py-4 text-xs font-bold uppercase text-gray-700">Total</th>
                    <th class="px-4 py-4 text-xs font-bold uppercase text-gray-700">Payment</th>
                    <th class="px-4 py-4 text-xs font-bold uppercase text-gray-700">Status</th>
                    <th class="px-4 py-4 text-xs font-bold uppercase text-gray-700 text-center">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php $__empty_1 = true; $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="hover:bg-slate-50 transition">
                    <td class="px-4 py-4 text-sm font-bold text-blue-600">#ORD-<?php echo e(str_pad($order->id, 4, '0', STR_PAD_LEFT)); ?></td>
                    <td class="px-4 py-4 text-sm text-gray-600">
                        <p><?php echo e($order->created_at->format('d M Y')); ?></p>
                        <p class="text-xs text-gray-400"><?php echo e($order->created_at->format('h:i A')); ?></p>
                    </td>
                    <td class="px-4 py-4 text-sm text-gray-800"><?php echo e($order->user->name ?? 'Guest'); ?></td>
                    <td class="px-4 py-4 text-sm text-gray-600"><?php echo e($order->items->count()); ?> item(s)</td>
                    <td class="px-4 py-4 text-sm font-bold text-gray-900">₹<?php echo e(number_format($order->total, 2)); ?></td>
                    <td class="px-4 py-4 text-xs font-semibold text-gray-600 uppercase"><?php echo e($order->payment_method ?? 'COD'); ?></td>
                    <td class="px-4 py-4">
                        <span class="px-2 py-1 rounded-full text-[10px] font-bold uppercase <?php echo e($order->status_badge_class); ?>">
                            <?php echo e(ucfirst($order->status)); ?>

                        </span>
                    </td>
                    <td class="px-4 py-4 text-center">
                        <div class="flex justify-center gap-2">
                            <a href="<?php echo e(route('dashboard.orders.show', $order)); ?>"
                               class="px-3 py-1.5 bg-blue-50 text-blue-600 rounded-lg text-xs font-bold hover:bg-blue-100">
                                <i class="fa-solid fa-eye"></i> View
                            </a>
                            <form method="POST" action="<?php echo e(route('dashboard.orders.status', $order)); ?>" class="inline">
                                <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                                <select name="status" onchange="this.form.submit()"
                                        class="border rounded px-2 py-1 text-xs outline-none">
                                    <?php $__currentLoopData = ['pending','confirmed','processing','shipped','delivered','cancelled']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($s); ?>" <?php echo e($order->status === $s ? 'selected' : ''); ?>><?php echo e(ucfirst($s)); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="8" class="px-4 py-8 text-center text-gray-400">No orders found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div><?php echo e($orders->withQueryString()->links()); ?></div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\FarmSea-dashboard\resources\views\dashboard\orders\index.blade.php ENDPATH**/ ?>