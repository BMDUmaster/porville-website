<?php $__env->startSection('title', 'Total Orders'); ?>
<?php $__env->startSection('page_title', 'Total Orders'); ?>

<?php $__env->startSection('content'); ?>
<div class="p-4 sm:p-6 max-w-7xl">

    
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-5 mb-8">
        <?php
            $catCards = [
                ['Fish','Fish','blue'],
                ['Chicken','Chicken','yellow'],
                ['Mutton','Mutton','red'],
                ['Veg','Veg','green'],
                ['Fruits','Fruits','purple'],
            ];
        ?>
        <?php $__currentLoopData = $catCards; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$cat,$label,$color]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="bg-white rounded-xl border p-4 flex items-center justify-between hover:-translate-y-1 hover:shadow-lg transition">
            <div>
                <p class="text-xs text-gray-500"><?php echo e($label); ?></p>
                <p class="text-xl font-bold text-<?php echo e($color); ?>-600">
                    <?php echo e(collect($byCategory)->get($cat, ['quantity' => 0])['quantity'] ?? 0); ?> kg
                </p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-<?php echo e($color); ?>-100 flex items-center justify-center text-sm font-bold text-<?php echo e($color); ?>-700"><?php echo e(substr($label, 0, 1)); ?></div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    
    <div class="flex flex-wrap justify-center gap-6 mb-8">
        <div class="bg-white rounded-xl border p-5 flex items-center justify-between w-64 hover:-translate-y-1 hover:shadow-lg transition">
            <div>
                <p class="text-xs text-gray-500">Total Orders</p>
                <p class="text-2xl font-bold"><?php echo e($orders->count()); ?></p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-indigo-100 flex items-center justify-center text-base font-bold text-indigo-700">OD</div>
        </div>
        <div class="bg-white rounded-xl border p-5 flex items-center justify-between w-64 hover:-translate-y-1 hover:shadow-lg transition">
            <div>
                <p class="text-xs text-gray-500">Total Weight</p>
                <p class="text-2xl font-bold text-indigo-600">
                    <?php echo e(collect($byCategory)->sum(fn($c) => $c['quantity'])); ?> kg
                </p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-indigo-100 flex items-center justify-center text-base font-bold text-indigo-700">KG</div>
        </div>
    </div>

    
    <div class="bg-white rounded-xl border overflow-x-auto">
        <table class="w-full min-w-[920px]">
            <thead style="background: linear-gradient(to right, #0ea5e9, #2563eb); color: white;">
                <tr>
                    <th class="px-4 py-3 text-left text-sm">Order ID</th>
                    <th class="px-4 py-3 text-left text-sm">Customer</th>
                    <th class="px-4 py-3 text-left text-sm">Category</th>
                    <th class="px-4 py-3 text-left text-sm">Items Total</th>
                    <th class="px-4 py-3 text-left text-sm">Quantity</th>
                    <th class="px-4 py-3 text-left text-sm">Total</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php $__empty_1 = true; $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="hover:bg-gray-50 align-top">
                    <td class="px-4 py-3 font-bold text-indigo-600">#ORD-<?php echo e(str_pad($order->id, 4, '0', STR_PAD_LEFT)); ?></td>
                    <td class="px-4 py-3"><?php echo e($order->user->name ?? 'Guest'); ?></td>
                    <td class="px-4 py-3"><?php echo e($order->items->first()?->product?->category?->name ?? '-'); ?></td>
                    <td class="px-4 py-3">
                        <div class="space-y-1">
                            <?php $__empty_2 = true; $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                                <div class="text-xs text-gray-700">
                                    <span class="font-semibold text-gray-800"><?php echo e($item->product->name ?? 'Product'); ?></span>
                                    <span class="text-gray-400">-</span>
                                    <span class="font-bold text-emerald-600">Rs<?php echo e(number_format($item->subtotal, 2)); ?></span>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                                <span class="text-xs text-gray-400">No items</span>
                            <?php endif; ?>
                        </div>
                    </td>
                    <td class="px-4 py-3 font-bold text-indigo-600"><?php echo e($order->items->sum('quantity')); ?> kg</td>
                    <td class="px-4 py-3 font-bold">Rs<?php echo e(number_format($order->total, 2)); ?></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="6" class="px-4 py-8 text-center text-gray-400">No orders found.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\FarmSea-dashboard\resources\views\dashboard\orders\total.blade.php ENDPATH**/ ?>