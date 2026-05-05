<?php $__env->startSection('title', 'Delivery Boys'); ?>
<?php $__env->startSection('page_title', 'Delivery Boy Management'); ?>

<?php $__env->startSection('content'); ?>
<div class="p-4 lg:p-6">

    <div class="mb-6 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div class="grid grid-cols-1 gap-4 md:grid-cols-3 md:flex-1">
            <?php $__currentLoopData = [['Total Partners',$stats['total'],'blue-500'],['Active',$stats['active'],'green-500'],['Inactive',$stats['inactive'],'red-500']]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$label,$val,$color]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="bg-white p-5 rounded-2xl shadow flex items-center gap-4">
                    <div class="w-12 h-12 bg-<?php echo e($color); ?> text-white flex items-center justify-center rounded-xl text-xl">
                        <i class="fa-solid fa-motorcycle"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500"><?php echo e($label); ?></p>
                        <h2 class="text-2xl font-bold"><?php echo e($val); ?></h2>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <button type="button" onclick="openDeliveryBoyModal()"
                class="bg-blue-600 text-white px-5 py-3 rounded-xl text-sm font-bold shadow hover:bg-blue-700">
            + Add Delivery Boy
        </button>
    </div>

    <form method="GET" class="bg-white p-4 rounded-2xl shadow mb-6 flex flex-wrap gap-3">
        <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Search partner, phone, area..."
               class="px-4 py-2 rounded-full border text-sm w-full md:w-auto outline-none">
        <select name="status" class="px-4 py-2 rounded-full border text-sm outline-none">
            <option value="">All Status</option>
            <option value="active" <?php echo e(request('status') === 'active' ? 'selected' : ''); ?>>Active</option>
            <option value="inactive" <?php echo e(request('status') === 'inactive' ? 'selected' : ''); ?>>Inactive</option>
        </select>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-full text-sm">Filter</button>
        <a href="<?php echo e(route('dashboard.delivery-boys')); ?>" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-full text-sm">Reset</a>
    </form>

    <div class="bg-white rounded-2xl shadow w-full overflow-x-auto">
        <table class="w-full text-sm min-w-[820px]">
            <thead class="bg-blue-600 text-white">
                <tr>
                    <th class="px-4 py-3">Sr no</th>
                    <th class="px-4 py-3">Partner Name</th>
                    <th class="px-4 py-3">Phone Number</th>
                    <th class="px-4 py-3">Area</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Last Assigned</th>
                    <th class="px-4 py-3">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php $__empty_1 = true; $__currentLoopData = $deliveryBoys; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $deliveryBoy): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 font-semibold text-slate-700"><?php echo e($deliveryBoys->firstItem() + $index); ?></td>
                        <td class="px-4 py-3 font-medium"><?php echo e($deliveryBoy->partner_name); ?></td>
                        <td class="px-4 py-3 text-gray-600"><?php echo e($deliveryBoy->phone_number); ?></td>
                        <td class="px-4 py-3 text-gray-600"><?php echo e($deliveryBoy->area); ?></td>
                        <td class="px-4 py-3">
                            <span class="px-3 py-1 rounded-full text-xs font-bold <?php echo e($deliveryBoy->status === 'active' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600'); ?>">
                                <?php echo e(ucfirst($deliveryBoy->status)); ?>

                            </span>
                        </td>
                        <td class="px-4 py-3 text-gray-600">
                            <?php echo e($deliveryBoy->last_assigned ? $deliveryBoy->last_assigned->format('d M Y, h:i A') : 'Not assigned yet'); ?>

                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                <form method="POST" action="<?php echo e(route('dashboard.delivery-boys.toggle', $deliveryBoy)); ?>">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('PATCH'); ?>
                                    <button type="submit"
                                            class="text-xs font-bold <?php echo e($deliveryBoy->status === 'active' ? 'text-red-600' : 'text-green-600'); ?> hover:underline">
                                        <?php echo e($deliveryBoy->status === 'active' ? 'Set Inactive' : 'Set Active'); ?>

                                    </button>
                                </form>
                                <form method="POST" action="<?php echo e(route('dashboard.delivery-boys.destroy', $deliveryBoy)); ?>"
                                      onsubmit="return confirm('Delete this delivery boy?')">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="text-xs font-bold text-slate-500 hover:text-red-600 hover:underline">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7" class="px-4 py-8 text-center text-gray-400">No delivery boys found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="mt-4"><?php echo e($deliveryBoys->links()); ?></div>
</div>

<div id="deliveryBoyModal" class="fixed inset-0 z-[200] hidden items-center justify-center bg-black/60 p-4">
    <div class="w-full max-w-2xl rounded-2xl bg-white shadow-2xl">
        <div class="flex items-center justify-between border-b px-6 py-4">
            <h2 class="text-lg font-bold text-slate-800">Add Delivery Boy</h2>
            <button type="button" onclick="closeDeliveryBoyModal()" class="text-2xl leading-none text-slate-400 hover:text-red-500">&times;</button>
        </div>

        <form method="POST" action="<?php echo e(route('dashboard.delivery-boys.store')); ?>" class="space-y-4 px-6 py-5">
            <?php echo csrf_field(); ?>

            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">Partner Name</label>
                    <input type="text" name="partner_name" required class="w-full rounded-xl border px-4 py-2.5 text-sm outline-none">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">Phone Number</label>
                    <input type="text" name="phone_number" required class="w-full rounded-xl border px-4 py-2.5 text-sm outline-none">
                </div>
            </div>

            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">Area</label>
                    <input type="text" name="area" required class="w-full rounded-xl border px-4 py-2.5 text-sm outline-none">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">Status</label>
                    <select name="status" class="w-full rounded-xl border px-4 py-2.5 text-sm outline-none">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700">Last Assigned</label>
                <input type="datetime-local" name="last_assigned" class="w-full rounded-xl border px-4 py-2.5 text-sm outline-none">
            </div>

            <div class="flex justify-end gap-3 pt-2">
                <button type="button" onclick="closeDeliveryBoyModal()" class="rounded-xl bg-gray-200 px-5 py-2.5 text-sm font-semibold text-gray-700">
                    Cancel
                </button>
                <button type="submit" class="rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-blue-700">
                    Save Delivery Boy
                </button>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
function openDeliveryBoyModal() {
    document.getElementById('deliveryBoyModal').classList.remove('hidden');
    document.getElementById('deliveryBoyModal').classList.add('flex');
}

function closeDeliveryBoyModal() {
    document.getElementById('deliveryBoyModal').classList.add('hidden');
    document.getElementById('deliveryBoyModal').classList.remove('flex');
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\FarmSea-dashboard\resources\views/dashboard/delivery-boys/index.blade.php ENDPATH**/ ?>