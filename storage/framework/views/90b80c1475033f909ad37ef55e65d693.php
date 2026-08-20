
<?php $__env->startSection('title', 'Sub-Categories'); ?>
<?php $__env->startSection('page_title', 'Subcategory Details'); ?>

<?php $__env->startSection('content'); ?>
<div class="p-4 sm:p-6">

    
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-6">
        <div class="bg-white border rounded-2xl p-5 flex items-center gap-4">
            <div class="w-14 h-14 rounded-full bg-purple-500 flex items-center justify-center text-white text-xl"><i class="fa-solid fa-trophy"></i></div>
            <div><p class="text-xs text-gray-500 mb-1">Total Sub Categories</p><p class="text-3xl font-extrabold"><?php echo e($stats['total']); ?></p></div>
        </div>
        <div class="bg-white border rounded-2xl p-5 flex items-center gap-4">
            <div class="w-14 h-14 rounded-full bg-emerald-500 flex items-center justify-center text-white text-xl"><i class="fa-solid fa-circle-check"></i></div>
            <div><p class="text-xs text-gray-500 mb-1">Active</p><p class="text-3xl font-extrabold text-emerald-600"><?php echo e($stats['active']); ?></p></div>
        </div>
        <div class="bg-white border rounded-2xl p-5 flex items-center gap-4">
            <div class="w-14 h-14 rounded-full bg-orange-500 flex items-center justify-center text-white text-xl"><i class="fa-solid fa-layer-group"></i></div>
            <div><p class="text-xs text-gray-500 mb-1">Inactive</p><p class="text-3xl font-extrabold text-orange-500"><?php echo e($stats['inactive']); ?></p></div>
        </div>
    </div>

    
    <form method="GET" class="bg-white rounded-xl border p-4 mb-4 grid grid-cols-1 md:grid-cols-6 gap-3 items-center">
        <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Search..."
               class="md:col-span-2 border border-slate-200 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-green-500">
        <select name="category" class="border border-slate-200 rounded-xl px-4 py-2.5 text-sm outline-none">
            <option value="">All Categories</option>
            <?php $__currentLoopData = $parent_categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($cat->id); ?>" <?php echo e(request('category') == $cat->id ? 'selected' : ''); ?>><?php echo e($cat->name); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
        <select name="status" class="border border-slate-200 rounded-xl px-4 py-2.5 text-sm outline-none">
            <option value="">All Status</option>
            <option value="active" <?php echo e(request('status') === 'active' ? 'selected' : ''); ?>>Active</option>
            <option value="inactive" <?php echo e(request('status') === 'inactive' ? 'selected' : ''); ?>>Inactive</option>
        </select>
        <a href="<?php echo e(route('dashboard.subcategories')); ?>" class="bg-red-500 text-white text-sm font-semibold px-5 py-2.5 rounded-xl text-center">Reset</a>
        <button onclick="openModal('addModal')" type="button"
                class="bg-orange-400 text-white text-sm font-semibold px-5 py-2.5 rounded-xl flex items-center gap-1 justify-center">
            <i class="fa-solid fa-plus"></i> Add New
        </button>
    </form>

    
    <div class="bg-white rounded-xl border overflow-x-auto mb-6">
        <table class="w-full text-left min-w-[800px]">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase">Sr.No.</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase">Image</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase">Date</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase">Sub Category</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase">Category</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase">Description</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                <?php $__empty_1 = true; $__currentLoopData = $subcategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $sub): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 font-medium text-gray-700"><?php echo e($subcategories->firstItem() + $i); ?></td>
                    <td class="px-6 py-4">
                        <?php if($sub->image): ?>
                            <img src="<?php echo e(asset('storage/'.$sub->image)); ?>" class="w-12 h-12 rounded-lg object-cover">
                        <?php else: ?>
                            <div class="w-12 h-12 rounded-lg bg-gray-100 flex items-center justify-center text-gray-400 text-xs"><i class="fa-regular fa-image"></i></div>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600"><?php echo e($sub->created_at->format('d M Y')); ?></td>
                    <td class="px-6 py-4">
                        <div>
                            <p class="font-semibold text-sm text-gray-800"><?php echo e($sub->name); ?></p>
                            <p class="text-[10px] text-gray-400 uppercase font-bold">ID: <?php echo e($sub->id); ?></p>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm font-semibold text-gray-700"><?php echo e($sub->parent->name ?? '—'); ?></td>
                    <td class="px-6 py-4 text-sm text-gray-500"><?php echo e(Str::limit($sub->description, 40)); ?></td>
                    <td class="px-6 py-4">
                        <span class="text-xs font-bold px-2.5 py-1 rounded-full <?php echo e($sub->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'); ?>">
                            <?php echo e($sub->is_active ? 'Active' : 'Inactive'); ?>

                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex gap-2">
                            <button onclick="openEditModal(<?php echo e($sub->id); ?>, '<?php echo e(addslashes($sub->name)); ?>', <?php echo e($sub->parent_id ?? 'null'); ?>, '<?php echo e($sub->is_active ? 'active' : 'inactive'); ?>', '<?php echo e(addslashes($sub->description ?? '')); ?>')"
                                    class="p-2 hover:bg-amber-100 text-amber-500 rounded-lg text-xs">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>
                            <form method="POST" action="<?php echo e(route('dashboard.subcategories.destroy', $sub)); ?>"
                                  onsubmit="return confirm('Delete this sub-category?')">
                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="p-2 hover:bg-red-100 text-red-500 rounded-lg text-xs">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="8" class="px-6 py-8 text-center text-gray-400">No sub-categories found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div><?php echo e($subcategories->withQueryString()->links()); ?></div>
</div>


<div id="addModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 p-4">
    <div class="bg-white w-full max-w-lg rounded-xl shadow-2xl max-h-[92vh] flex flex-col">

        
        <div class="flex items-center justify-between px-6 py-4 border-b">
            <h2 class="text-base font-semibold text-gray-800">Add New Sub-Category</h2>
            <button onclick="closeModal('addModal')" class="text-gray-400 hover:text-gray-700 text-xl leading-none">&times;</button>
        </div>

        <form method="POST" action="<?php echo e(route('dashboard.subcategories.store')); ?>" enctype="multipart/form-data"
              class="overflow-y-auto px-6 py-5 space-y-5">
            <?php echo csrf_field(); ?>

            
            <div>
                <label class="block text-sm text-gray-700 mb-1">Select Category</label>
                <div class="relative">
                    <select name="parent_id" required
                            class="w-full appearance-none border border-gray-300 rounded px-3 py-2.5 text-sm text-gray-700 bg-white outline-none focus:border-blue-400 pr-8">
                        <option value="">Select category</option>
                        <?php $__currentLoopData = $parent_categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($cat->id); ?>"><?php echo e($cat->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <span class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs">&#9660;</span>
                </div>
            </div>

            
            <div>
                <label class="block text-sm text-gray-700 mb-1">Sub-Category Image</label>
                <div class="flex items-center border border-gray-300 rounded overflow-hidden">
                    <label class="bg-gray-100 border-r border-gray-300 px-3 py-2 text-sm text-gray-600 cursor-pointer whitespace-nowrap hover:bg-gray-200">
                        Choose Files
                        <input type="file" name="image" accept="image/*" class="hidden" id="addImageInput"
                               onchange="document.getElementById('addImageLabel').textContent = this.files[0]?.name || 'No file chosen'">
                    </label>
                    <span id="addImageLabel" class="px-3 text-sm text-gray-400">No file chosen</span>
                </div>
            </div>

            
            <div>
                <label class="block text-sm text-gray-700 mb-1">Sub-Category Name</label>
                <input type="text" name="name" required placeholder="Enter sub-category name"
                       class="w-full border border-gray-300 rounded px-3 py-2.5 text-sm text-gray-700 outline-none focus:border-blue-400">
            </div>

            
            <div>
                <label class="block text-sm text-gray-700 mb-1">Description</label>
                <textarea name="description" rows="4" placeholder="Enter sub-category description"
                          class="w-full border border-gray-300 rounded px-3 py-2.5 text-sm text-gray-700 outline-none resize-none focus:border-blue-400"></textarea>
            </div>

            
            <div>
                <label class="block text-sm text-gray-700 mb-1">Status</label>
                <div class="relative">
                    <select name="is_active"
                            class="w-full appearance-none border border-gray-300 rounded px-3 py-2.5 text-sm text-gray-700 bg-white outline-none focus:border-blue-400 pr-8">
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                    <span class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs">&#9660;</span>
                </div>
            </div>

            
            <div class="flex justify-end gap-3 pt-2 pb-1">
                <button type="button" onclick="closeModal('addModal')"
                        class="px-6 py-2 rounded border border-gray-300 text-sm text-gray-600 hover:bg-gray-50 font-medium">Cancel</button>
                <button type="submit"
                        class="px-6 py-2 rounded bg-red-500 hover:bg-red-600 text-white text-sm font-semibold">Save</button>
            </div>
        </form>
    </div>
</div>


<div id="editModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 p-4">
    <div class="bg-white w-full max-w-lg rounded-xl shadow-2xl max-h-[92vh] flex flex-col">

        
        <div class="flex items-center justify-between px-6 py-4 border-b">
            <h2 class="text-base font-semibold text-gray-800">Edit Sub-Category</h2>
            <button onclick="closeModal('editModal')" class="text-gray-400 hover:text-gray-700 text-xl leading-none">&times;</button>
        </div>

        <form id="editForm" method="POST" enctype="multipart/form-data"
              class="overflow-y-auto px-6 py-5 space-y-5">
            <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>

            
            <div>
                <label class="block text-sm text-gray-700 mb-1">Select Category</label>
                <div class="relative">
                    <select name="parent_id" id="editParent" required
                            class="w-full appearance-none border border-gray-300 rounded px-3 py-2.5 text-sm text-gray-700 bg-white outline-none focus:border-blue-400 pr-8">
                        <?php $__currentLoopData = $parent_categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($cat->id); ?>"><?php echo e($cat->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <span class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs">&#9660;</span>
                </div>
            </div>

            
            <div>
                <label class="block text-sm text-gray-700 mb-1">Sub-Category Image</label>
                <div class="flex items-center border border-gray-300 rounded overflow-hidden">
                    <label class="bg-gray-100 border-r border-gray-300 px-3 py-2 text-sm text-gray-600 cursor-pointer whitespace-nowrap hover:bg-gray-200">
                        Choose Files
                        <input type="file" name="image" accept="image/*" class="hidden"
                               onchange="document.getElementById('editImageLabel').textContent = this.files[0]?.name || 'No file chosen'">
                    </label>
                    <span id="editImageLabel" class="px-3 text-sm text-gray-400">No file chosen</span>
                </div>
            </div>

            
            <div>
                <label class="block text-sm text-gray-700 mb-1">Sub-Category Name</label>
                <input type="text" name="name" id="editName" required placeholder="Enter sub-category name"
                       class="w-full border border-gray-300 rounded px-3 py-2.5 text-sm text-gray-700 outline-none focus:border-blue-400">
            </div>

            
            <div>
                <label class="block text-sm text-gray-700 mb-1">Description</label>
                <textarea name="description" id="editDesc" rows="4" placeholder="Enter sub-category description"
                          class="w-full border border-gray-300 rounded px-3 py-2.5 text-sm text-gray-700 outline-none resize-none focus:border-blue-400"></textarea>
            </div>

            
            <div>
                <label class="block text-sm text-gray-700 mb-1">Status</label>
                <div class="relative">
                    <select name="is_active" id="editStatus"
                            class="w-full appearance-none border border-gray-300 rounded px-3 py-2.5 text-sm text-gray-700 bg-white outline-none focus:border-blue-400 pr-8">
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                    <span class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs">&#9660;</span>
                </div>
            </div>

            
            <div class="flex justify-end gap-3 pt-2 pb-1">
                <button type="button" onclick="closeModal('editModal')"
                        class="px-6 py-2 rounded border border-gray-300 text-sm text-gray-600 hover:bg-gray-50 font-medium">Cancel</button>
                <button type="submit"
                        class="px-6 py-2 rounded bg-red-500 hover:bg-red-600 text-white text-sm font-semibold">Update</button>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
const subcategoryUpdateUrlTemplate = <?php echo json_encode(route('dashboard.subcategories.update', ['subcategory' => '__ID__']), 512) ?>;

function openModal(id) { document.getElementById(id).classList.remove('hidden'); document.getElementById(id).classList.add('flex'); }
function closeModal(id) { document.getElementById(id).classList.add('hidden'); document.getElementById(id).classList.remove('flex'); }
function openEditModal(id, name, parentId, status, desc) {
    document.getElementById('editName').value   = name;
    document.getElementById('editDesc').value   = desc;
    document.getElementById('editParent').value = parentId;
    document.getElementById('editStatus').value = status === 'active' ? '1' : '0';
    document.getElementById('editForm').action  = subcategoryUpdateUrlTemplate.replace('__ID__', encodeURIComponent(id));
    openModal('editModal');
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\BMDU Work\FarmSea-dashboard\resources\views\dashboard\subcategories\index.blade.php ENDPATH**/ ?>