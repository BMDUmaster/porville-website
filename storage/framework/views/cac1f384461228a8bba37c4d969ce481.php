
<?php $__env->startSection('title', 'Coupons & Offers'); ?>
<?php $__env->startSection('page_title', 'Coupons & Offers'); ?>

<?php $__env->startSection('content'); ?>
<div class="p-4 md:p-8 space-y-6">

    
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
        <div class="bg-white p-5 rounded-2xl shadow-sm border flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-indigo-100 flex items-center justify-center"><i class="fas fa-tags text-indigo-600 text-xl"></i></div>
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase">Active Coupons</p>
                <h2 class="text-2xl font-bold text-gray-800"><?php echo e($stats['active']); ?></h2>
            </div>
        </div>
        <div class="bg-white p-5 rounded-2xl shadow-sm border flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-red-100 flex items-center justify-center"><i class="fas fa-clock text-red-600 text-xl"></i></div>
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase">Expired</p>
                <h2 class="text-2xl font-bold text-gray-800"><?php echo e($stats['expired']); ?></h2>
            </div>
        </div>
        <div class="bg-white p-5 rounded-2xl shadow-sm border flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center"><i class="fas fa-chart-simple text-blue-600 text-xl"></i></div>
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase">Total Redeemed</p>
                <h2 class="text-2xl font-bold text-gray-800"><?php echo e(number_format($stats['total_redeemed'])); ?></h2>
            </div>
        </div>
    </div>

    
    <div class="bg-white p-4 rounded-2xl shadow-sm border flex flex-wrap gap-3 items-center justify-between">
        <form method="GET" class="flex flex-wrap gap-3 items-center">
            <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Search by code..."
                   class="px-4 py-2 rounded-xl bg-gray-50 border text-sm outline-none focus:ring-2 focus:ring-indigo-500">
            <select name="status" class="px-4 py-2 rounded-xl bg-gray-50 border text-sm outline-none">
                <option value="">All Status</option>
                <option value="active" <?php echo e(request('status') === 'active' ? 'selected' : ''); ?>>Active</option>
                <option value="inactive" <?php echo e(request('status') === 'inactive' ? 'selected' : ''); ?>>Inactive</option>
            </select>
            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-xl text-sm font-medium">Filter</button>
            <a href="<?php echo e(route('dashboard.coupons')); ?>" class="px-4 py-2 bg-gray-100 text-gray-600 rounded-xl text-sm font-medium">Reset</a>
        </form>
        <button onclick="openModal('addModal')"
                class="px-4 py-2 bg-indigo-600 text-white rounded-xl text-sm flex items-center gap-2 font-medium">
            <i class="fas fa-plus-circle"></i> Create Coupon
        </button>
    </div>

    
    <div class="bg-white rounded-2xl shadow-sm border overflow-x-auto">
        <table class="w-full text-sm text-left min-w-[900px]">
            <thead class="bg-gray-50 text-gray-600">
                <tr>
                    <th class="px-6 py-4 font-semibold">Code</th>
                    <th class="px-6 py-4 font-semibold">Type</th>
                    <th class="px-6 py-4 font-semibold">Discount</th>
                    <th class="px-6 py-4 font-semibold">Min Order</th>
                    <th class="px-6 py-4 font-semibold">Expires</th>
                    <th class="px-6 py-4 font-semibold">Usage</th>
                    <th class="px-6 py-4 font-semibold">Status</th>
                    <th class="px-6 py-4 font-semibold">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php $__empty_1 = true; $__currentLoopData = $coupons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $coupon): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 font-bold text-indigo-600"><?php echo e($coupon->code); ?></td>
                    <td class="px-6 py-4 capitalize"><?php echo e($coupon->type); ?></td>
                    <td class="px-6 py-4 font-medium">
                        <?php echo e($coupon->type === 'percent' ? $coupon->value.'% OFF' : '₹'.number_format($coupon->value).' OFF'); ?>

                    </td>
                    <td class="px-6 py-4"><?php echo e($coupon->min_order_amount ? '₹'.number_format($coupon->min_order_amount) : '—'); ?></td>
                    <td class="px-6 py-4 text-sm text-gray-600"><?php echo e($coupon->expires_at?->format('d M Y') ?? '—'); ?></td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <span class="font-medium"><?php echo e($coupon->used_count); ?>/<?php echo e($coupon->max_uses ?? '∞'); ?></span>
                            <?php if($coupon->max_uses): ?>
                            <div class="w-16 h-1.5 bg-gray-200 rounded-full">
                                <div class="h-1.5 bg-green-500 rounded-full"
                                     style="width: <?php echo e(min(100, ($coupon->used_count / $coupon->max_uses) * 100)); ?>%"></div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 text-xs font-bold rounded-full <?php echo e($coupon->is_active ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600'); ?>">
                            <?php echo e($coupon->is_active ? 'Active' : 'Inactive'); ?>

                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex gap-2">
                            <button onclick="openEditModal(<?php echo e($coupon->id); ?>, '<?php echo e($coupon->code); ?>', '<?php echo e($coupon->type); ?>', <?php echo e($coupon->value); ?>, <?php echo e($coupon->min_order_amount ?? 0); ?>, <?php echo e($coupon->max_uses ?? 0); ?>, '<?php echo e($coupon->expires_at?->format('Y-m-d')); ?>', <?php echo e($coupon->is_active ? 1 : 0); ?>)"
                                    class="p-1.5 hover:bg-indigo-100 text-indigo-600 rounded-lg">
                                <i class="fas fa-pencil-alt"></i>
                            </button>
                            <form method="POST" action="<?php echo e(route('dashboard.coupons.destroy', $coupon)); ?>"
                                  onsubmit="return confirm('Delete this coupon?')">
                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="p-1.5 hover:bg-red-100 text-red-600 rounded-lg">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="8" class="px-6 py-8 text-center text-gray-400">No coupons found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div><?php echo e($coupons->withQueryString()->links()); ?></div>
</div>


<div id="addModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm p-4">
    <div class="bg-white w-full max-w-lg rounded-2xl shadow-2xl max-h-[90vh] overflow-y-auto">
        <div class="p-5 border-b flex justify-between items-center">
            <h2 class="text-lg font-bold">Create Coupon</h2>
            <button onclick="closeModal('addModal')" class="text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
        </div>
        <form method="POST" action="<?php echo e(route('dashboard.coupons.store')); ?>" class="p-5 space-y-4">
            <?php echo csrf_field(); ?>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Coupon Code <span class="text-red-500">*</span></label>
                    <input type="text" name="code" required placeholder="e.g. FARM20"
                           class="w-full border rounded-xl px-4 py-2.5 text-sm outline-none focus:border-indigo-500 uppercase">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Type</label>
                    <select name="type" class="w-full border rounded-xl px-4 py-2.5 text-sm outline-none">
                        <option value="percent">Percentage (%)</option>
                        <option value="flat">Flat (₹)</option>
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Value <span class="text-red-500">*</span></label>
                    <input type="number" name="value" required min="0" step="0.01" placeholder="e.g. 20"
                           class="w-full border rounded-xl px-4 py-2.5 text-sm outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Min Order (₹)</label>
                    <input type="number" name="min_order_amount" min="0" placeholder="e.g. 500"
                           class="w-full border rounded-xl px-4 py-2.5 text-sm outline-none">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Max Uses</label>
                    <input type="number" name="max_uses" min="1" placeholder="Unlimited"
                           class="w-full border rounded-xl px-4 py-2.5 text-sm outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Expires At <span class="text-red-500">*</span></label>
                    <input type="date" name="expires_at" required
                           class="w-full border rounded-xl px-4 py-2.5 text-sm outline-none">
                </div>
            </div>
            <div class="flex items-center gap-2">
                <input type="checkbox" name="is_active" value="1" checked class="w-4 h-4 accent-indigo-600">
                <label class="text-sm font-medium">Active</label>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="submit" class="flex-1 bg-indigo-600 text-white py-3 rounded-xl font-bold text-sm">Create Coupon</button>
                <button type="button" onclick="closeModal('addModal')" class="flex-1 bg-gray-100 text-gray-600 py-3 rounded-xl font-bold text-sm">Cancel</button>
            </div>
        </form>
    </div>
</div>


<div id="editModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm p-4">
    <div class="bg-white w-full max-w-lg rounded-2xl shadow-2xl max-h-[90vh] overflow-y-auto">
        <div class="p-5 border-b flex justify-between items-center">
            <h2 class="text-lg font-bold">Edit Coupon</h2>
            <button onclick="closeModal('editModal')" class="text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
        </div>
        <form id="editForm" method="POST" class="p-5 space-y-4">
            <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Coupon Code</label>
                    <input type="text" name="code" id="editCode" required
                           class="w-full border rounded-xl px-4 py-2.5 text-sm outline-none uppercase">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Type</label>
                    <select name="type" id="editType" class="w-full border rounded-xl px-4 py-2.5 text-sm outline-none">
                        <option value="percent">Percentage (%)</option>
                        <option value="flat">Flat (₹)</option>
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Value</label>
                    <input type="number" name="value" id="editValue" min="0" step="0.01"
                           class="w-full border rounded-xl px-4 py-2.5 text-sm outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Min Order (₹)</label>
                    <input type="number" name="min_order_amount" id="editMinOrder" min="0"
                           class="w-full border rounded-xl px-4 py-2.5 text-sm outline-none">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Max Uses</label>
                    <input type="number" name="max_uses" id="editMaxUses" min="1"
                           class="w-full border rounded-xl px-4 py-2.5 text-sm outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Expires At</label>
                    <input type="date" name="expires_at" id="editExpires"
                           class="w-full border rounded-xl px-4 py-2.5 text-sm outline-none">
                </div>
            </div>
            <div class="flex items-center gap-2">
                <input type="checkbox" name="is_active" id="editIsActive" value="1" class="w-4 h-4 accent-indigo-600">
                <label class="text-sm font-medium">Active</label>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="submit" class="flex-1 bg-indigo-600 text-white py-3 rounded-xl font-bold text-sm">Update</button>
                <button type="button" onclick="closeModal('editModal')" class="flex-1 bg-gray-100 text-gray-600 py-3 rounded-xl font-bold text-sm">Cancel</button>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
function openModal(id) { document.getElementById(id).classList.remove('hidden'); document.getElementById(id).classList.add('flex'); }
function closeModal(id) { document.getElementById(id).classList.add('hidden'); document.getElementById(id).classList.remove('flex'); }
function openEditModal(id, code, type, value, minOrder, maxUses, expires, isActive) {
    document.getElementById('editCode').value     = code;
    document.getElementById('editType').value     = type;
    document.getElementById('editValue').value    = value;
    document.getElementById('editMinOrder').value = minOrder || '';
    document.getElementById('editMaxUses').value  = maxUses || '';
    document.getElementById('editExpires').value  = expires;
    document.getElementById('editIsActive').checked = isActive == 1;
    document.getElementById('editForm').action    = '/coupons/' + id;
    openModal('editModal');
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\FarmSea-dashboard\resources\views/dashboard/coupons/index.blade.php ENDPATH**/ ?>