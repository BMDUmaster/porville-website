<?php $__env->startSection('title', 'Orders'); ?>
<?php $__env->startSection('page_title', 'FarmSea Orders'); ?>

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
    $pricingDayLabel = fn (?string $day) => $day === 'tomorrow' ? 'Tomorrow' : 'Today';
    $orderPricingDays = fn ($order) => $order->items
        ->pluck('pricing_day')
        ->filter()
        ->unique()
        ->map($pricingDayLabel)
        ->values();
?>
<div class="p-4 md:p-8 space-y-6">

    <form method="GET" class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
        <h1 class="text-2xl font-bold text-gray-800">Live Orders</h1>
        <div class="flex flex-col gap-3 sm:flex-row sm:flex-wrap sm:items-center lg:justify-end">
            <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Search customer / ID..."
                   class="w-full rounded border px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-blue-500 sm:w-auto">
            <select name="status" class="border rounded px-3 py-1.5 text-sm outline-none">
                <option value="">All Status</option>
                <?php $__currentLoopData = $statusOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($status); ?>" <?php echo e(request('status') === $status ? 'selected' : ''); ?>>
                        <?php echo e(ucwords(str_replace('_', ' ', $status))); ?>

                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <select name="date_filter" id="orderDateFilter" onchange="toggleOrderCustomDates()" class="border rounded px-3 py-1.5 text-sm outline-none">
                <?php $__currentLoopData = $dateFilterOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($value); ?>" <?php echo e(request('date_filter') === $value ? 'selected' : ''); ?>><?php echo e($label); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <div id="orderCustomDateFields" class="<?php echo e(request('date_filter') === 'custom' ? 'flex' : 'hidden'); ?> flex-col gap-2 sm:flex-row sm:items-center">
                <input type="date" name="date_from" value="<?php echo e(request('date_from')); ?>"
                       class="rounded border px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-blue-500">
                <span class="hidden text-xs font-bold uppercase tracking-[0.14em] text-gray-400 sm:inline">to</span>
                <input type="date" name="date_to" value="<?php echo e(request('date_to')); ?>"
                       class="rounded border px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <button type="submit" class="rounded bg-blue-600 px-4 py-1.5 text-sm text-white">Filter</button>
            <a href="<?php echo e(route('dashboard.orders')); ?>" class="rounded bg-gray-200 px-4 py-1.5 text-sm text-gray-700">Reset</a>
        </div>
    </form>

    <div id="liveOrdersContent" data-live-orders-url="<?php echo e(request()->fullUrl()); ?>" data-live-orders-count="<?php echo e($orders->total()); ?>">
    <div class="space-y-4 md:hidden">
        <?php $__empty_1 = true; $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <article class="rounded-2xl border bg-white p-4 shadow-sm">
                <div class="flex items-start justify-between gap-3">
                    <div class="min-w-0">
                        <p class="break-all text-sm font-bold text-blue-600">#ORD-<?php echo e(str_pad($order->id, 4, '0', STR_PAD_LEFT)); ?></p>
                        <p class="mt-1 text-xs text-gray-400"><?php echo e($order->created_at->format('d M Y, h:i A')); ?></p>
                    </div>
                    <span class="rounded-full px-2 py-1 text-[10px] font-bold uppercase <?php echo e($order->status_badge_class); ?>">
                        <?php echo e($order->status_label); ?>

                    </span>
                </div>

                <div class="mt-4 grid gap-3 sm:grid-cols-2">
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-[0.16em] text-gray-400">Customer</p>
                        <p class="mt-1 text-sm font-semibold text-gray-800"><?php echo e($order->user->name ?? 'Guest'); ?></p>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-[0.16em] text-gray-400">Items</p>
                        <p class="mt-1 text-sm text-gray-700"><?php echo e($order->items->count()); ?> item(s)</p>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-[0.16em] text-gray-400">Amount</p>
                        <p class="mt-1 text-sm font-bold text-gray-900">Rs<?php echo e(number_format($order->total, 2)); ?></p>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-[0.16em] text-gray-400">Payment</p>
                        <p class="mt-1 text-sm font-semibold uppercase text-gray-700"><?php echo e($order->payment_method ?? 'COD'); ?></p>
                    </div>
                    <div class="sm:col-span-2">
                        <p class="text-[11px] font-bold uppercase tracking-[0.16em] text-gray-400">Delivery Schedule</p>
                        <div class="mt-1 flex flex-wrap items-center gap-2">
                            <?php $__empty_2 = true; $__currentLoopData = $orderPricingDays($order); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dayLabel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                                <span class="rounded-full bg-emerald-50 px-2.5 py-1 text-[11px] font-black text-emerald-700"><?php echo e($dayLabel); ?></span>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                                <span class="rounded-full bg-gray-100 px-2.5 py-1 text-[11px] font-black text-gray-500">Day not set</span>
                            <?php endif; ?>
                            <span class="rounded-full bg-amber-50 px-2.5 py-1 text-[11px] font-black text-amber-700">
                                <i class="fa-regular fa-clock mr-1"></i><?php echo e($order->delivery_slot_label ?? 'Slot not set'); ?>

                            </span>
                        </div>
                    </div>
                    <div class="sm:col-span-2">
                        <p class="text-[11px] font-bold uppercase tracking-[0.16em] text-gray-400">Delivery Boy</p>
                        <?php if($order->deliveryBoy): ?>
                            <p class="mt-1 text-sm font-bold text-gray-800"><?php echo e($order->deliveryBoy->partner_name); ?></p>
                            <p class="text-xs text-gray-500"><?php echo e($order->deliveryBoy->phone_number); ?> | <?php echo e($order->deliveryBoy->area); ?></p>
                        <?php else: ?>
                            <p class="mt-1 text-xs text-gray-400">Not assigned</p>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="mt-4 space-y-3">
                    <a href="<?php echo e(route('dashboard.orders.show', $order)); ?>"
                       class="inline-flex w-full items-center justify-center gap-2 rounded-lg bg-blue-50 px-3 py-2.5 text-xs font-bold text-blue-600 hover:bg-blue-100">
                        <i class="fa-solid fa-eye"></i> View
                    </a>

                    <form method="POST" action="<?php echo e(route('dashboard.orders.status', $order)); ?>" class="space-y-2 rounded-xl border border-gray-200 bg-gray-50 p-3">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PATCH'); ?>
                        <select name="status" onchange="toggleDeliveryBoySelect(this, '<?php echo e($order->id); ?>')" class="w-full rounded border px-3 py-2 text-xs outline-none">
                            <?php $__currentLoopData = $statusOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($status); ?>" <?php echo e($order->status === $status ? 'selected' : ''); ?>>
                                    <?php echo e(ucwords(str_replace('_', ' ', $status))); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>

                        <select name="delivery_boy_id" data-delivery-boy-select
                                class="w-full rounded border px-3 py-2 text-xs outline-none <?php echo e($order->status === 'out_for_delivery' ? '' : 'hidden'); ?>">
                            <option value="">Choose delivery boy</option>
                            <?php $__currentLoopData = $deliveryBoysByOrder[$order->id] ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $deliveryBoy): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($deliveryBoy->id); ?>" <?php echo e($order->delivery_boy_id === $deliveryBoy->id ? 'selected' : ''); ?>>
                                    <?php echo e($deliveryBoy->partner_name); ?> | <?php echo e($deliveryBoy->phone_number); ?> | <?php echo e($deliveryBoy->area); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>

                        <button type="submit" class="w-full rounded bg-blue-600 px-3 py-2 text-[11px] font-bold text-white hover:bg-blue-700">
                            Save
                        </button>
                    </form>
                </div>
            </article>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="rounded-2xl border border-dashed bg-white px-4 py-10 text-center text-gray-400">No orders found.</div>
        <?php endif; ?>
    </div>

    <div class="hidden overflow-x-auto rounded-lg border bg-white shadow-sm md:block">
        <table class="w-full min-w-[1320px] text-left">
            <thead class="border-b bg-gray-50">
                <tr>
                    <th class="px-4 py-4 text-xs font-bold uppercase text-gray-700">Order ID</th>
                    <th class="px-4 py-4 text-xs font-bold uppercase text-gray-700">Date & Time</th>
                    <th class="px-4 py-4 text-xs font-bold uppercase text-gray-700">Customer</th>
                    <th class="px-4 py-4 text-xs font-bold uppercase text-gray-700">Items</th>
                    <th class="px-4 py-4 text-xs font-bold uppercase text-gray-700">Total</th>
                    <th class="px-4 py-4 text-xs font-bold uppercase text-gray-700">Payment</th>
                    <th class="px-4 py-4 text-xs font-bold uppercase text-gray-700">Delivery Schedule</th>
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
                        <td class="px-4 py-4 text-xs">
                            <div class="flex flex-col gap-1">
                                <div class="flex flex-wrap gap-1">
                                    <?php $__empty_2 = true; $__currentLoopData = $orderPricingDays($order); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dayLabel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                                        <span class="rounded-full bg-emerald-50 px-2 py-0.5 font-black text-emerald-700"><?php echo e($dayLabel); ?></span>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                                        <span class="rounded-full bg-gray-100 px-2 py-0.5 font-black text-gray-500">Day not set</span>
                                    <?php endif; ?>
                                </div>
                                <span class="inline-flex w-fit items-center rounded-full bg-amber-50 px-2 py-0.5 font-black text-amber-700">
                                    <i class="fa-regular fa-clock mr-1"></i><?php echo e($order->delivery_slot_label ?? 'Slot not set'); ?>

                                </span>
                            </div>
                        </td>
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

                                    <select name="delivery_boy_id" data-delivery-boy-select
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
                        <td colspan="10" class="px-4 py-8 text-center text-gray-400">No orders found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div><?php echo e($orders->withQueryString()->links()); ?></div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
function toggleDeliveryBoySelect(select, orderId) {
    const form = select.closest('form');
    const deliverySelect = form ? form.querySelector('[data-delivery-boy-select]') : null;

    if (!deliverySelect) {
        return;
    }

    const shouldShow = select.value === 'out_for_delivery';
    deliverySelect.classList.toggle('hidden', !shouldShow);

    if (!shouldShow) {
        deliverySelect.value = '';
    }
}

function toggleOrderCustomDates() {
    const filter = document.getElementById('orderDateFilter');
    const customFields = document.getElementById('orderCustomDateFields');

    if (!filter || !customFields) {
        return;
    }

    customFields.classList.toggle('hidden', filter.value !== 'custom');
    customFields.classList.toggle('flex', filter.value === 'custom');
}

toggleOrderCustomDates();

let liveOrdersTimer = null;
let liveOrdersLoading = false;

function shouldPauseLiveOrdersRefresh() {
    const liveContent = document.getElementById('liveOrdersContent');
    const active = document.activeElement;

    return liveContent && active && liveContent.contains(active) && ['SELECT', 'INPUT', 'BUTTON'].includes(active.tagName);
}

function refreshLiveOrders() {
    const liveContent = document.getElementById('liveOrdersContent');

    if (!liveContent || liveOrdersLoading || shouldPauseLiveOrdersRefresh()) {
        return;
    }

    liveOrdersLoading = true;

    fetch(liveContent.dataset.liveOrdersUrl || window.location.href, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'text/html',
        },
        cache: 'no-store',
    })
        .then((response) => response.text())
        .then((html) => {
            const parsed = new DOMParser().parseFromString(html, 'text/html');
            const freshContent = parsed.getElementById('liveOrdersContent');

            if (!freshContent) {
                return;
            }

            liveContent.innerHTML = freshContent.innerHTML;
            liveContent.dataset.liveOrdersCount = freshContent.dataset.liveOrdersCount || liveContent.dataset.liveOrdersCount || '0';
        })
        .catch(() => {})
        .finally(() => {
            liveOrdersLoading = false;
        });
}

liveOrdersTimer = window.setInterval(refreshLiveOrders, 8000);
document.addEventListener('visibilitychange', () => {
    if (!document.hidden) {
        refreshLiveOrders();
    }
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\BMDU Work\FarmSea-dashboard\resources\views\dashboard\orders\index.blade.php ENDPATH**/ ?>