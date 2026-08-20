<?php $__env->startSection('title', 'Customers'); ?>
<?php $__env->startSection('page_title', 'Customer Management'); ?>

<?php $__env->startSection('content'); ?>
<div class="p-4 lg:p-6">

    
    <div class="mb-6 grid grid-cols-1 gap-6 sm:grid-cols-2 xl:grid-cols-3">
        <?php $__currentLoopData = [['Total Customers',$stats['total'],'blue-500'],['Active Customers',$stats['active'],'green-500'],['Blocked Customers',$stats['blocked'],'red-500']]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$label,$val,$color]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="flex items-center gap-4 rounded-2xl bg-white p-5 shadow">
            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-<?php echo e($color); ?> text-xl font-bold text-white">
                <?php echo e($label === 'Total Customers' ? 'C' : ($label === 'Active Customers' ? 'A' : 'B')); ?>

            </div>
            <div>
                <p class="text-sm text-gray-500"><?php echo e($label); ?></p>
                <h2 class="text-2xl font-bold"><?php echo e($val); ?></h2>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    
    <form method="GET" class="mb-6 flex flex-col gap-3 rounded-2xl bg-white p-4 shadow sm:flex-row sm:flex-wrap">
        <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Search by customer ID, name, email, phone..."
               class="w-full rounded-full border px-4 py-2 text-sm outline-none sm:w-auto sm:flex-1">
        <select name="status" class="rounded-full border px-4 py-2 text-sm outline-none">
            <option value="">All Status</option>
            <option value="active" <?php echo e(request('status') === 'active' ? 'selected' : ''); ?>>Active</option>
            <option value="blocked" <?php echo e(request('status') === 'blocked' ? 'selected' : ''); ?>>Blocked</option>
        </select>
        <button type="submit" class="rounded-full bg-blue-600 px-4 py-2 text-sm text-white">Filter</button>
        <a href="<?php echo e(route('dashboard.users')); ?>" class="rounded-full bg-gray-200 px-4 py-2 text-sm text-gray-700">Reset</a>
    </form>

    
    <div class="space-y-4 md:hidden">
        <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <article class="rounded-2xl bg-white p-4 shadow">
                <div class="flex items-start justify-between gap-3">
                    <div class="min-w-0">
                        <p class="text-sm font-semibold text-slate-700">#<?php echo e($user->id); ?></p>
                        <p class="mt-1 text-base font-bold text-slate-900"><?php echo e($user->name); ?></p>
                        <p class="mt-1 break-all text-sm text-gray-600"><?php echo e($user->email); ?></p>
                    </div>
                    <span class="rounded-full px-3 py-1 text-xs font-bold <?php echo e($user->status === 'active' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600'); ?>">
                        <?php echo e(ucfirst($user->status ?? 'active')); ?>

                    </span>
                </div>

                <div class="mt-4 grid gap-3 sm:grid-cols-2">
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-[0.16em] text-gray-400">Phone</p>
                        <p class="mt-1 text-sm text-gray-700"><?php echo e($user->phone ?: '-'); ?></p>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-[0.16em] text-gray-400">Orders</p>
                        <p class="mt-1 text-sm font-bold text-indigo-600"><?php echo e($user->orders_count); ?></p>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-[0.16em] text-gray-400">DOB / Gender</p>
                        <p class="mt-1 text-sm text-gray-700"><?php echo e($user->date_of_birth?->format('d M Y') ?: '-'); ?></p>
                        <p class="text-xs text-gray-500"><?php echo e($user->gender ? ucfirst(str_replace('_', ' ', $user->gender)) : 'Not set'); ?></p>
                    </div>
                </div>

                <div class="mt-4 rounded-2xl border border-slate-200 bg-slate-50 p-3">
                    <p class="text-[11px] font-bold uppercase tracking-[0.16em] text-slate-400">Delivery Charge</p>
                    <?php if($user->delivery_charge !== null): ?>
                        <p class="mt-1 text-sm font-semibold text-slate-700">Custom Rs<?php echo e(number_format($user->delivery_charge, 2)); ?></p>
                    <?php endif; ?>
                    <form method="POST" action="<?php echo e(route('dashboard.users.delivery-charge', $user)); ?>" class="mt-3 flex flex-col gap-2">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PATCH'); ?>
                        <input type="number" name="delivery_charge" min="0" step="0.01"
                               value="<?php echo e($user->delivery_charge !== null ? number_format($user->delivery_charge, 2, '.', '') : ''); ?>"
                               placeholder="<?php echo e(number_format($globalDeliveryCharge, 2, '.', '')); ?>"
                               class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm font-medium text-slate-800 outline-none transition focus:border-blue-500">
                        <button type="submit" class="rounded-xl bg-blue-600 px-3 py-2 text-xs font-bold text-white hover:bg-blue-700">
                            Save
                        </button>
                    </form>
                </div>

                <div class="mt-4 flex flex-col gap-2 sm:flex-row">
                    <a href="<?php echo e(route('dashboard.users.show', $user)); ?>"
                       class="inline-flex w-full items-center justify-center rounded-xl bg-blue-50 px-3 py-2 text-xs font-bold text-blue-600 hover:bg-blue-100 sm:w-auto">
                        View
                    </a>
                    <form method="POST" action="<?php echo e(route('dashboard.users.toggle', $user)); ?>" class="w-full sm:w-auto">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PATCH'); ?>
                        <button type="submit"
                                class="w-full rounded-xl px-3 py-2 text-xs font-bold <?php echo e($user->status === 'active' ? 'bg-red-50 text-red-600 hover:bg-red-100' : 'bg-green-50 text-green-600 hover:bg-green-100'); ?>">
                            <?php echo e($user->status === 'active' ? 'Block' : 'Unblock'); ?>

                        </button>
                    </form>
                </div>
            </article>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="rounded-2xl bg-white px-4 py-10 text-center text-gray-400 shadow">No customers found.</div>
        <?php endif; ?>
    </div>

    
    <div class="hidden w-full overflow-x-auto rounded-2xl bg-white shadow md:block">
        <table class="w-full min-w-[980px] text-sm">
            <thead class="bg-blue-600 text-white">
                <tr>
                    <th class="px-4 py-3">customer_id</th>
                    <th class="px-4 py-3">Name</th>
                    <th class="px-4 py-3">Email</th>
                    <th class="px-4 py-3">Phone</th>
                    <th class="px-4 py-3">DOB / Gender</th>
                    <th class="px-4 py-3">Orders</th>
                    <th class="px-4 py-3">Delivery Charge</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 font-semibold text-slate-700">#<?php echo e($user->id); ?></td>
                    <td class="px-4 py-3 font-medium"><?php echo e($user->name); ?></td>
                    <td class="px-4 py-3 text-gray-600"><?php echo e($user->email); ?></td>
                    <td class="px-4 py-3 text-gray-600"><?php echo e($user->phone ?: '-'); ?></td>
                    <td class="px-4 py-3 text-gray-600">
                        <p><?php echo e($user->date_of_birth?->format('d M Y') ?: '-'); ?></p>
                        <p class="mt-1 text-xs text-gray-400"><?php echo e($user->gender ? ucfirst(str_replace('_', ' ', $user->gender)) : 'Not set'); ?></p>
                    </td>
                    <td class="px-4 py-3 text-center font-bold text-indigo-600"><?php echo e($user->orders_count); ?></td>
                    <td class="px-4 py-3">
                        <div class="max-w-[210px]">
                            <?php if($user->delivery_charge !== null): ?>
                                <p class="text-xs font-bold text-blue-600">Custom Rs<?php echo e(number_format($user->delivery_charge, 2)); ?></p>
                            <?php endif; ?>
                            <form method="POST" action="<?php echo e(route('dashboard.users.delivery-charge', $user)); ?>" class="mt-2 flex items-center gap-3">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('PATCH'); ?>
                                <input type="number" name="delivery_charge" min="0" step="0.01"
                                       value="<?php echo e($user->delivery_charge !== null ? number_format($user->delivery_charge, 2, '.', '') : ''); ?>"
                                       placeholder="<?php echo e(number_format($globalDeliveryCharge, 2, '.', '')); ?>"
                                       class="w-24 rounded-lg border border-slate-300 px-2 py-1.5 text-xs font-semibold text-slate-800 outline-none transition focus:border-blue-500">
                                <button type="submit" class="rounded-lg bg-blue-600 px-2.5 py-1.5 text-xs font-bold text-white hover:bg-blue-700">
                                    Save
                                </button>
                            </form>
                        </div>
                    </td>
                    <td class="px-4 py-3">
                        <span class="rounded-full px-3 py-1 text-xs font-bold <?php echo e($user->status === 'active' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600'); ?>">
                            <?php echo e(ucfirst($user->status ?? 'active')); ?>

                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-2">
                            <a href="<?php echo e(route('dashboard.users.show', $user)); ?>"
                               class="text-xs font-bold text-blue-600 hover:underline">View</a>
                            <form method="POST" action="<?php echo e(route('dashboard.users.toggle', $user)); ?>">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('PATCH'); ?>
                                <button type="submit"
                                        class="text-xs font-bold <?php echo e($user->status === 'active' ? 'text-red-600' : 'text-green-600'); ?> hover:underline">
                                    <?php echo e($user->status === 'active' ? 'Block' : 'Unblock'); ?>

                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="9" class="px-4 py-8 text-center text-gray-400">No customers found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="mt-4"><?php echo e($users->withQueryString()->links()); ?></div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\BMDU Work\FarmSea-dashboard\resources\views\dashboard\users\index.blade.php ENDPATH**/ ?>