<?php $__env->startSection('title', 'Categories'); ?>
<?php $__env->startSection('page_title', 'Category Management'); ?>

<?php $__env->startSection('content'); ?>
<div class="p-4 sm:p-6">

    
    <div class="bg-white rounded-xl border p-4 mb-4 flex flex-col md:flex-row gap-3 items-center">
        <form method="GET" class="flex-1 relative w-full">
            <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
            <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Search categories..."
                   class="w-full pl-9 pr-4 py-2.5 border rounded-lg text-sm outline-none focus:border-blue-400">
        </form>
        <button onclick="openModal('addModal')"
                class="bg-purple-600 text-white px-5 py-2.5 rounded-xl text-sm font-medium flex items-center gap-2 w-full md:w-auto md:self-start justify-center">
            <i class="fa-solid fa-plus"></i> Add New Category
        </button>
    </div>

    
    <div class="bg-white rounded-xl border overflow-x-auto">
        <table class="w-full text-left min-w-[700px]">
            <thead style="background: linear-gradient(to right, #7e22ce, #4f46e5); color: white;">
                <tr>
                    <th class="px-4 py-3 text-xs uppercase">Sr.No.</th>
                    <th class="px-4 py-3 text-xs uppercase">Image</th>
                    <th class="px-4 py-3 text-xs uppercase">Category</th>
                    <th class="px-4 py-3 text-xs uppercase">Sub-cats</th>
                    <th class="px-4 py-3 text-xs uppercase">Date</th>
                    <th class="px-4 py-3 text-xs uppercase">Status</th>
                    <th class="px-4 py-3 text-xs uppercase text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php $__empty_1 = true; $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-4 py-3 align-middle text-sm text-gray-500"><?php echo e($categories->firstItem() + $i); ?></td>
                    <td class="px-4 py-3 align-middle">
                        <?php if($cat->image): ?>
                            <img src="<?php echo e(asset('storage/'.$cat->image)); ?>" class="w-12 h-12 rounded-lg object-cover">
                        <?php else: ?>
                            <div class="w-12 h-12 rounded-lg bg-gray-100 flex items-center justify-center text-gray-400">
                                <i class="fa-regular fa-image"></i>
                            </div>
                        <?php endif; ?>
                    </td>
                    <td class="px-4 py-3 align-middle">
                        <p class="font-semibold text-sm text-gray-800"><?php echo e($cat->name); ?></p>
                        <p class="text-xs text-gray-400 truncate max-w-[200px]"><?php echo e($cat->description ?? 'No description'); ?></p>
                    </td>
                    <td class="px-4 py-3 align-middle text-sm text-gray-600"><?php echo e($cat->children->count()); ?></td>
                    <td class="px-4 py-3 align-middle text-sm text-gray-500"><?php echo e($cat->created_at->format('d M Y')); ?></td>
                    <td class="px-4 py-3 align-middle text-sm">
                        <?php if($cat->is_active): ?>
                            <span class="bg-green-100 text-green-800 text-[11px] font-semibold px-2.5 py-1 rounded-full">Active</span>
                        <?php else: ?>
                            <span class="bg-red-100 text-red-800 text-[11px] font-semibold px-2.5 py-1 rounded-full">Inactive</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-4 py-3 align-middle text-center">
                        <div class="flex items-center justify-center gap-2">
                            <button onclick="openEditModal(<?php echo e($cat->id); ?>, '<?php echo e(addslashes($cat->name)); ?>', '<?php echo e(addslashes($cat->description ?? '')); ?>', <?php echo e($cat->is_active ? 1 : 0); ?>)"
                                    class="flex h-8 w-8 items-center justify-center rounded-lg text-xs text-indigo-600 transition hover:bg-indigo-100">
                                <i class="fa-regular fa-pen-to-square"></i>
                            </button>
                            <form method="POST" action="<?php echo e(route('dashboard.categories.destroy', $cat)); ?>"
                                  onsubmit="return confirm('Delete this category?')">
                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="flex h-8 w-8 items-center justify-center rounded-lg text-xs text-red-500 transition hover:bg-red-100">
                                    <i class="fa-regular fa-trash-can"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="7" class="px-4 py-8 text-center text-gray-400">No categories found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="mt-4"><?php echo e($categories->withQueryString()->links()); ?></div>
</div>


<div id="addModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm p-4">
    <div class="bg-white w-full max-w-2xl rounded-2xl shadow-2xl">
        <div class="px-7 py-5 border-b flex justify-between items-center">
            <h2 class="text-xl font-semibold">Add Category</h2>
            <button onclick="closeModal('addModal')" class="text-gray-400 hover:text-gray-600 text-2xl leading-none">&times;</button>
        </div>
        <form method="POST" action="<?php echo e(route('dashboard.categories.store')); ?>" enctype="multipart/form-data" class="px-7 py-6 space-y-5">
            <?php echo csrf_field(); ?>
            <div>
                <label class="block text-sm font-medium mb-1.5">Category Image</label>
                <input type="file" name="image" accept="image/*"
                       class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1.5">Category Name <span class="text-red-500">*</span></label>
                <input type="text" name="name" required placeholder="e.g. Fresh Fish"
                       class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm outline-none focus:border-purple-500">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1.5 text-gray-600">Description</label>
                <textarea name="description" rows="5" placeholder="Enter category details..."
                          class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm outline-none resize-none"></textarea>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1.5">Status</label>
                <select name="is_active" class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm outline-none focus:border-purple-500">
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>
            <div class="flex gap-3 pt-1">
                <button type="submit" class="flex-1 bg-purple-600 hover:bg-purple-700 text-white py-3 rounded-xl font-medium text-sm">Save Category</button>
                <button type="button" onclick="closeModal('addModal')" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-600 py-3 rounded-xl font-medium text-sm">Cancel</button>
            </div>
        </form>
    </div>
</div>


<div id="editModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm p-4">
    <div class="bg-white w-full max-w-2xl rounded-2xl shadow-2xl">
        <div class="px-7 py-5 border-b flex justify-between items-center">
            <h2 class="text-xl font-semibold">Edit Category</h2>
            <button onclick="closeModal('editModal')" class="text-gray-400 hover:text-gray-600 text-2xl leading-none">&times;</button>
        </div>
        <form id="editForm" method="POST" enctype="multipart/form-data" class="px-7 py-6 space-y-5">
            <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
            <div>
                <label class="block text-sm font-medium mb-1.5">Update Image</label>
                <input type="file" name="image" accept="image/*"
                       class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1.5">Category Name <span class="text-red-500">*</span></label>
                <input type="text" name="name" id="editName" required
                       class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm outline-none focus:border-purple-500">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1.5 text-gray-600">Description</label>
                <textarea name="description" id="editDesc" rows="5"
                          class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm outline-none resize-none"></textarea>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1.5">Status</label>
                <select name="is_active" id="editStatus" class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm outline-none focus:border-purple-500">
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>
            <div class="flex gap-3 pt-1">
                <button type="submit" class="flex-1 bg-purple-600 hover:bg-purple-700 text-white py-3 rounded-xl font-medium text-sm">Update Category</button>
                <button type="button" onclick="closeModal('editModal')" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-600 py-3 rounded-xl font-medium text-sm">Cancel</button>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
const categoryUpdateUrlTemplate = <?php echo json_encode(route('dashboard.categories.update', ['category' => '__ID__']), 512) ?>;

function openModal(id) {
    document.getElementById(id).classList.remove('hidden');
    document.getElementById(id).classList.add('flex');
}
function closeModal(id) {
    document.getElementById(id).classList.add('hidden');
    document.getElementById(id).classList.remove('flex');
}
function openEditModal(id, name, desc, isActive) {
    document.getElementById('editName').value = name;
    document.getElementById('editDesc').value = desc;
    document.getElementById('editStatus').value = isActive;
    document.getElementById('editForm').action = categoryUpdateUrlTemplate.replace('__ID__', encodeURIComponent(id));
    openModal('editModal');
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\BMDU Work\FarmSea-dashboard\resources\views\dashboard\categories\index.blade.php ENDPATH**/ ?>