<?php $__env->startSection('title', 'Order History'); ?>
<?php $__env->startSection('page_title', 'Order History'); ?>

<?php $__env->startSection('content'); ?>
<?php
    $statusOptions = ['pending', 'confirmed', 'processing', 'out_for_delivery', 'delivered', 'cancelled'];
    $dateFilterOptions = [
        '' => 'All Dates',
        'today' => 'Today',
        'last_7_days' => 'Last 7 Days',
        'last_30_days' => 'Last 30 Days',
        'custom' => 'Custom Range',
    ];
?>
<div class="p-4 md:p-8 space-y-6">

    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-4">
        <?php $__currentLoopData = [["Today's Orders",$stats['today'],'purple','fa-calendar-day'],['Pending',$stats['pending'],'emerald','fa-clock'],['Delivered',$stats['delivered'],'blue','fa-check'],['Cancelled',$stats['cancelled'],'red','fa-circle-xmark']]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$label,$val,$color,$icon]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="flex items-center gap-4 rounded-2xl border bg-white p-5 shadow-sm">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-<?php echo e($color); ?>-500 text-white shadow-lg">
                    <i class="fa-solid <?php echo e($icon); ?> text-xl"></i>
                </div>
                <div>
                    <p class="text-xs font-medium uppercase text-gray-500"><?php echo e($label); ?></p>
                    <h2 class="text-2xl font-bold text-gray-800"><?php echo e($val); ?></h2>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <form method="GET" class="grid grid-cols-1 gap-3 rounded-2xl border bg-white p-4 shadow-sm sm:grid-cols-2 xl:grid-cols-8">
        <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Search"
               class="rounded-full border bg-gray-50 px-4 py-2.5 text-sm outline-none sm:col-span-2 xl:col-span-1">
        <input type="text" name="order_id" value="<?php echo e(request('order_id')); ?>" placeholder="Order ID"
               class="rounded-full border bg-gray-50 px-4 py-2.5 text-sm outline-none">
        <select name="status" class="rounded-full border bg-gray-50 px-4 py-2.5 text-sm outline-none">
            <option value="">All Status</option>
            <?php $__currentLoopData = $statusOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($status); ?>" <?php echo e(request('status') === $status ? 'selected' : ''); ?>>
                    <?php echo e(ucwords(str_replace('_', ' ', $status))); ?>

                </option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
        <select name="date_filter" id="historyDateFilter" onchange="toggleHistoryCustomDates()" class="rounded-full border bg-gray-50 px-4 py-2.5 text-sm outline-none">
            <?php $__currentLoopData = $dateFilterOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($value); ?>" <?php echo e(request('date_filter') === $value ? 'selected' : ''); ?>><?php echo e($label); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
        <div id="historyCustomDateFields" class="<?php echo e(request('date_filter') === 'custom' ? 'contents' : 'hidden'); ?>">
            <input type="date" name="date_from" value="<?php echo e(request('date_from')); ?>"
                   class="rounded-full border bg-gray-50 px-4 py-2.5 text-sm outline-none">
            <input type="date" name="date_to" value="<?php echo e(request('date_to')); ?>"
                   class="rounded-full border bg-gray-50 px-4 py-2.5 text-sm outline-none">
        </div>
        <button type="submit" class="rounded-full bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white">Filter</button>
        <a href="<?php echo e(route('dashboard.orders.history')); ?>" class="rounded-full bg-gray-100 px-4 py-2.5 text-center text-sm font-semibold text-gray-600">Reset</a>
    </form>

    <div class="space-y-4 md:hidden">
        <?php $__empty_1 = true; $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <article class="rounded-2xl border bg-white p-4 shadow-sm">
                <div class="flex items-start justify-between gap-3">
                    <div class="min-w-0">
                        <p class="text-xs font-bold text-gray-400">#<?php echo e($orders->firstItem() + $i); ?></p>
                        <p class="mt-1 break-all text-sm font-bold text-indigo-600">#ORD-<?php echo e(str_pad($order->id, 4, '0', STR_PAD_LEFT)); ?></p>
                    </div>
                    <span class="rounded-full px-3 py-1 text-xs font-bold <?php echo e($order->status_badge_class); ?>">
                        <?php echo e($order->status_label); ?>

                    </span>
                </div>

                <div class="mt-4 grid gap-3 sm:grid-cols-2">
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-[0.16em] text-gray-400">Date</p>
                        <p class="mt-1 text-sm font-semibold text-gray-800"><?php echo e($order->created_at->format('d M Y')); ?></p>
                        <p class="text-xs text-gray-500"><?php echo e($order->created_at->format('h:i A')); ?></p>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-[0.16em] text-gray-400">Amount</p>
                        <p class="mt-1 text-sm font-bold text-indigo-600">Rs<?php echo e(number_format($order->total, 2)); ?></p>
                        <p class="text-xs font-bold uppercase text-blue-600"><?php echo e($order->payment_method ?? 'COD'); ?></p>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-[0.16em] text-gray-400">Customer</p>
                        <p class="mt-1 text-sm font-bold text-gray-800"><?php echo e($order->user->name ?? 'Guest'); ?></p>
                        <p class="text-xs text-gray-500">ID: <?php echo e($order->user_id); ?></p>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-[0.16em] text-gray-400">Delivery Boy</p>
                        <?php if($order->deliveryBoy): ?>
                            <p class="mt-1 text-sm font-bold text-gray-800"><?php echo e($order->deliveryBoy->partner_name); ?></p>
                            <p class="text-xs text-gray-500"><?php echo e($order->deliveryBoy->area); ?></p>
                        <?php else: ?>
                            <p class="mt-1 text-xs text-gray-400">Not assigned</p>
                        <?php endif; ?>
                    </div>
                </div>

                <a href="<?php echo e(route('dashboard.orders.show', $order)); ?>"
                   class="mt-4 inline-flex w-full items-center justify-center gap-2 rounded-lg bg-indigo-50 px-4 py-2.5 text-sm font-medium text-indigo-600 hover:bg-indigo-100">
                    <i class="fas fa-eye"></i> View
                </a>
            </article>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="rounded-2xl border border-dashed bg-white px-4 py-10 text-center text-gray-400">No order history found.</div>
        <?php endif; ?>
    </div>

    <div class="hidden overflow-x-auto rounded-2xl border bg-white shadow-sm md:block">
        <table class="w-full min-w-[1040px] text-left text-sm">
            <thead class="bg-indigo-600 text-xs uppercase tracking-wider text-white">
                <tr>
                    <th class="px-4 py-4">Sr.No.</th>
                    <th class="px-4 py-4">Date & Time</th>
                    <th class="px-4 py-4">Customer</th>
                    <th class="px-4 py-4">Order ID</th>
                    <th class="px-4 py-4">Amount</th>
                    <th class="px-4 py-4">Delivery Boy</th>
                    <th class="px-4 py-4">Status</th>
                    <th class="px-4 py-4">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php $__empty_1 = true; $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="transition hover:bg-gray-50">
                        <td class="px-4 py-4 font-medium"><?php echo e($orders->firstItem() + $i); ?></td>
                        <td class="px-4 py-4">
                            <span class="font-semibold"><?php echo e($order->created_at->format('d M Y')); ?></span><br>
                            <span class="text-xs text-gray-500"><?php echo e($order->created_at->format('h:i A')); ?></span>
                        </td>
                        <td class="px-4 py-4">
                            <div class="font-bold text-gray-800"><?php echo e($order->user->name ?? 'Guest'); ?></div>
                            <div class="text-xs text-gray-500">ID: <?php echo e($order->user_id); ?></div>
                        </td>
                        <td class="px-4 py-4 font-bold text-indigo-600">#ORD-<?php echo e(str_pad($order->id, 4, '0', STR_PAD_LEFT)); ?></td>
                        <td class="px-4 py-4">
                            <div class="font-bold text-indigo-600">Rs<?php echo e(number_format($order->total, 2)); ?></div>
                            <span class="rounded bg-blue-50 px-2 py-0.5 text-xs font-bold uppercase text-blue-600"><?php echo e($order->payment_method ?? 'COD'); ?></span>
                        </td>
                        <td class="px-4 py-4 text-sm text-gray-600">
                            <?php if($order->deliveryBoy): ?>
                                <div class="font-bold text-gray-800"><?php echo e($order->deliveryBoy->partner_name); ?></div>
                                <div class="text-xs text-gray-500"><?php echo e($order->deliveryBoy->area); ?></div>
                            <?php else: ?>
                                <span class="text-xs text-gray-400">Not assigned</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-4 py-4">
                            <span class="rounded-full px-3 py-1 text-xs font-bold <?php echo e($order->status_badge_class); ?>">
                                <?php echo e($order->status_label); ?>

                            </span>
                        </td>
                        <td class="px-4 py-4">
                            <a href="<?php echo e(route('dashboard.orders.show', $order)); ?>"
                               class="flex items-center gap-2 rounded-lg px-4 py-2 text-sm font-medium text-indigo-600 hover:bg-indigo-50">
                                <i class="fas fa-eye"></i> View
                            </a>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="8" class="px-4 py-8 text-center text-gray-400">No order history found.</td>
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
function toggleHistoryCustomDates() {
    const filter = document.getElementById('historyDateFilter');
    const customFields = document.getElementById('historyCustomDateFields');

    if (!filter || !customFields) {
        return;
    }

    customFields.classList.toggle('hidden', filter.value !== 'custom');
    customFields.classList.toggle('contents', filter.value === 'custom');
}

toggleHistoryCustomDates();
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\BMDU Work\FarmSea-dashboard\resources\views\dashboard\orders\history.blade.php ENDPATH**/ ?>