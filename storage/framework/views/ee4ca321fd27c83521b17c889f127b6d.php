
<?php $__env->startSection('title', 'Users'); ?>
<?php $__env->startSection('page_title', 'User Management'); ?>

<?php $__env->startSection('content'); ?>
<div class="p-4 lg:p-6">

    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <?php $__currentLoopData = [['Total Users',$stats['total'],'blue-500'],['Active Users',$stats['active'],'green-500'],['Blocked Users',$stats['blocked'],'red-500']]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$label,$val,$color]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="bg-white p-5 rounded-2xl shadow flex items-center gap-4">
            <div class="w-12 h-12 bg-<?php echo e($color); ?> text-white flex items-center justify-center rounded-xl text-xl">
                <?php echo e($label === 'Total Users' ? '👤' : ($label === 'Active Users' ? '✔' : '✖')); ?>

            </div>
            <div>
                <p class="text-sm text-gray-500"><?php echo e($label); ?></p>
                <h2 class="text-2xl font-bold"><?php echo e($val); ?></h2>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    
    <form method="GET" class="bg-white p-4 rounded-2xl shadow mb-6 flex flex-wrap gap-3">
        <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Search user..."
               class="px-4 py-2 rounded-full border text-sm w-full md:w-auto outline-none">
        <select name="status" class="px-4 py-2 rounded-full border text-sm outline-none">
            <option value="">All Status</option>
            <option value="active" <?php echo e(request('status') === 'active' ? 'selected' : ''); ?>>Active</option>
            <option value="blocked" <?php echo e(request('status') === 'blocked' ? 'selected' : ''); ?>>Blocked</option>
        </select>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-full text-sm">Filter</button>
        <a href="<?php echo e(route('dashboard.users')); ?>" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-full text-sm">Reset</a>
    </form>

    
    <div class="bg-white rounded-2xl shadow w-full overflow-x-auto">
        <table class="w-full text-sm min-w-[700px]">
            <thead class="bg-blue-600 text-white">
                <tr>
                    <th class="px-4 py-3">user_id</th>
                    <th class="px-4 py-3">Name</th>
                    <th class="px-4 py-3">Email</th>
                    <th class="px-4 py-3">Phone</th>
                    <th class="px-4 py-3">Orders</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3"><?php echo e($users->firstItem() + $i); ?></td>
                    <td class="px-4 py-3 font-medium"><?php echo e($user->name); ?></td>
                    <td class="px-4 py-3 text-gray-600"><?php echo e($user->email); ?></td>
                    <td class="px-4 py-3 text-gray-600"><?php echo e($user->phone ?? '—'); ?></td>
                    <td class="px-4 py-3 text-center font-bold text-indigo-600"><?php echo e($user->orders->count()); ?></td>
                    <td class="px-4 py-3">
                        <span class="px-3 py-1 rounded-full text-xs font-bold <?php echo e($user->status === 'active' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600'); ?>">
                            <?php echo e(ucfirst($user->status ?? 'active')); ?>

                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex gap-2 items-center">
                            <a href="<?php echo e(route('dashboard.users.show', $user)); ?>"
                               class="text-blue-600 text-xs font-bold hover:underline">View</a>
                            <form method="POST" action="<?php echo e(route('dashboard.users.toggle', $user)); ?>">
                                <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                                <button type="submit"
                                        class="text-xs font-bold <?php echo e($user->status === 'active' ? 'text-red-600' : 'text-green-600'); ?> hover:underline">
                                    <?php echo e($user->status === 'active' ? 'Block' : 'Unblock'); ?>

                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="7" class="px-4 py-8 text-center text-gray-400">No users found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="mt-4"><?php echo e($users->withQueryString()->links()); ?></div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\FarmSea-dashboard\resources\views/dashboard/users/index.blade.php ENDPATH**/ ?>