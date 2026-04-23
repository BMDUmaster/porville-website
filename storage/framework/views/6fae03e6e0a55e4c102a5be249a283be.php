
<?php $__env->startSection('title', 'My Orders'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-5xl mx-auto px-4 py-8">
    <h1 class="nunito font-extrabold text-2xl text-gray-800 mb-6">Order History</h1>

    
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <?php $__currentLoopData = [['Total Orders',$stats['total'],'fa-box','blue'],['Delivered',$stats['delivered'],'fa-check-circle','green'],['In Progress',$stats['active'],'fa-hourglass-half','orange'],['Total Spent','₹'.number_format($stats['spent'],0),'fa-wallet','purple']]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$label,$val,$icon,$color]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="bg-white rounded-2xl border p-5">
            <div class="flex items-start justify-between mb-3">
                <div>
                    <p class="text-xs text-gray-500 font-semibold uppercase tracking-wider mb-1"><?php echo e($label); ?></p>
                    <p class="nunito font-extrabold text-2xl text-gray-800"><?php echo e($val); ?></p>
                </div>
                <div class="w-10 h-10 bg-<?php echo e($color); ?>-100 rounded-xl flex items-center justify-center">
                    <i class="fa-solid <?php echo e($icon); ?> text-<?php echo e($color); ?>-600"></i>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    
    <form method="GET" class="bg-white rounded-2xl border p-5 mb-6 flex flex-wrap gap-3">
        <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Search by order number..."
               class="flex-1 min-w-[200px] px-4 py-2.5 border border-gray-200 rounded-xl text-sm outline-none focus:border-blue-500">
        <select name="status" onchange="this.form.submit()" class="px-4 py-2.5 border border-gray-200 rounded-xl text-sm outline-none">
            <option value="">All Orders</option>
            <?php $__currentLoopData = ['pending','confirmed','processing','shipped','delivered','cancelled']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($s); ?>" <?php echo e(request('status') == $s ? 'selected' : ''); ?>><?php echo e(ucfirst($s)); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
        <button type="submit" class="bg-blue-700 text-white px-5 py-2.5 rounded-xl text-sm font-bold">Search</button>
    </form>

    
    <div class="bg-white rounded-2xl border overflow-hidden">
        <table class="w-full min-w-[600px]">
            <thead class="bg-gray-50 border-b">
                <tr class="text-xs font-bold text-gray-500 uppercase tracking-wider">
                    <th class="px-6 py-4 text-left">Order ID</th>
                    <th class="px-6 py-4 text-left">Date</th>
                    <th class="px-6 py-4 text-left">Items</th>
                    <th class="px-6 py-4 text-left">Status</th>
                    <th class="px-6 py-4 text-right">Amount</th>
                    <th class="px-6 py-4 text-center">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php $__empty_1 = true; $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4">
                        <a href="<?php echo e(route('frontend.order.show', $order->id)); ?>" class="font-bold text-blue-700 hover:underline text-sm">
                            <?php echo e($order->order_number ?? '#'.$order->id); ?>

                        </a>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600"><?php echo e($order->created_at->format('d M Y')); ?></td>
                    <td class="px-6 py-4 text-sm text-gray-600"><?php echo e($order->items->count()); ?> item(s)</td>
                    <td class="px-6 py-4">
                        <span class="text-xs font-bold px-2.5 py-1 rounded-full <?php echo e($order->status_badge_class); ?>">
                            <?php echo e(ucfirst($order->status)); ?>

                        </span>
                    </td>
                    <td class="px-6 py-4 text-right font-bold text-blue-700">₹<?php echo e(number_format($order->total, 2)); ?></td>
                    <td class="px-6 py-4 text-center">
                        <a href="<?php echo e(route('frontend.order.show', $order->id)); ?>"
                           class="px-3 py-1.5 bg-blue-700 text-white text-xs font-bold rounded-lg hover:bg-blue-800 transition">
                            <i class="fa-solid fa-eye"></i> View
                        </a>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="6" class="px-6 py-12 text-center text-gray-400">No orders found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="mt-4"><?php echo e($orders->links()); ?></div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\FarmSea-dashboard\resources\views\frontend\my-orders.blade.php ENDPATH**/ ?>