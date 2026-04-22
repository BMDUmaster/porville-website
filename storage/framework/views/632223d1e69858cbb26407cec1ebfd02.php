
<?php $__env->startSection('title', 'Products'); ?>
<?php $__env->startSection('page_title', 'Product Management'); ?>

<?php $__env->startSection('content'); ?>
<div class="p-4 sm:p-6">

    
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <?php $__currentLoopData = [['Total Products','total','indigo','fa-box'],['Active','active','green','fa-circle-check'],['Inactive','inactive','orange','fa-circle-xmark'],['Out of Stock','out_of_stock','purple','fa-battery-empty']]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$label,$key,$color,$icon]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="bg-white p-5 rounded-2xl shadow-sm border flex items-center gap-4">
            <div class="w-12 h-12 bg-<?php echo e($color); ?>-100 text-<?php echo e($color); ?>-600 rounded-xl flex items-center justify-center text-xl">
                <i class="fa-solid <?php echo e($icon); ?>"></i>
            </div>
            <div>
                <p class="text-slate-500 text-xs font-bold uppercase"><?php echo e($label); ?></p>
                <h3 class="text-2xl font-black"><?php echo e($stats[$key]); ?></h3>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    
    <form method="GET" class="bg-white p-4 rounded-2xl shadow-sm border mb-6 flex flex-wrap gap-3 items-center">
        <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Search products..."
               class="flex-1 min-w-[150px] bg-slate-50 border border-slate-200 rounded-lg px-4 py-2 text-sm outline-none focus:border-indigo-500">
        <select name="category" class="bg-slate-50 border border-slate-200 rounded-lg px-4 py-2 text-sm outline-none">
            <option value="">All Categories</option>
            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($cat->id); ?>" <?php echo e(request('category') == $cat->id ? 'selected' : ''); ?>><?php echo e($cat->name); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
        <select name="status" class="bg-slate-50 border border-slate-200 rounded-lg px-4 py-2 text-sm outline-none">
            <option value="">All Status</option>
            <option value="active" <?php echo e(request('status') === 'active' ? 'selected' : ''); ?>>Active</option>
            <option value="inactive" <?php echo e(request('status') === 'inactive' ? 'selected' : ''); ?>>Inactive</option>
        </select>
        <a href="<?php echo e(route('dashboard.products')); ?>" class="bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-bold">Reset</a>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-bold">Filter</button>
        <button type="button" onclick="openModal('addProductModal')"
                class="bg-amber-400 hover:bg-amber-500 text-black px-4 py-2 rounded-lg text-sm font-bold flex items-center gap-1">
            <i class="fa-solid fa-plus"></i> Add Product
        </button>
    </form>

    
    <div class="bg-white rounded-2xl shadow-sm border overflow-x-auto">
        <table class="w-full text-left min-w-[700px]">
            <thead class="bg-slate-50 border-b">
                <tr class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                    <th class="px-6 py-4">Sr.No.</th>
                    <th class="px-6 py-4">Product</th>
                    <th class="px-6 py-4">Category</th>
                    <th class="px-6 py-4">Price</th>
                    <th class="px-6 py-4">Stock</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="border-b border-slate-50 hover:bg-slate-50/50 transition">
                    <td class="px-6 py-4 text-xs font-bold text-slate-400"><?php echo e($products->firstItem() + $i); ?></td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <?php if($product->images && count($product->images)): ?>
                                <img src="<?php echo e(asset('storage/'.$product->images[0])); ?>" class="w-10 h-10 rounded-lg object-cover">
                            <?php else: ?>
                                <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center text-gray-400 text-xs"><i class="fa-regular fa-image"></i></div>
                            <?php endif; ?>
                            <div>
                                <p class="text-xs font-bold text-slate-700"><?php echo e($product->name); ?></p>
                                <p class="text-[10px] text-slate-400">ID: <?php echo e($product->id); ?></p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-xs font-semibold text-slate-600"><?php echo e($product->category->name ?? '—'); ?></td>
                    <td class="px-6 py-4 text-xs font-semibold text-slate-700">₹<?php echo e(number_format($product->price, 2)); ?></td>
                    <td class="px-6 py-4 text-xs <?php echo e($product->stock < 10 ? 'text-red-600 font-bold' : 'text-slate-600'); ?>"><?php echo e($product->stock); ?></td>
                    <td class="px-6 py-4">
                        <span class="text-xs font-bold px-2 py-1 rounded <?php echo e($product->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'); ?>">
                            <?php echo e($product->is_active ? 'ACTIVE' : 'INACTIVE'); ?>

                        </span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex justify-end gap-2">
                            <button onclick="openEditModal(<?php echo e($product->id); ?>, '<?php echo e(addslashes($product->name)); ?>', <?php echo e($product->category_id); ?>, <?php echo e($product->price); ?>, <?php echo e($product->stock); ?>, <?php echo e($product->is_active ? 1 : 0); ?>)"
                                    class="p-2 hover:bg-indigo-100 text-indigo-600 rounded-lg text-xs">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>
                            <form method="POST" action="<?php echo e(route('dashboard.products.destroy', $product)); ?>"
                                  onsubmit="return confirm('Delete this product?')">
                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="p-2 hover:bg-red-100 text-red-500 rounded-lg text-xs">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="7" class="px-6 py-8 text-center text-gray-400">No products found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="mt-4"><?php echo e($products->withQueryString()->links()); ?></div>
</div>


<div id="addProductModal" class="fixed inset-0 z-[200] hidden items-center justify-center bg-black/60 p-4">
    <div class="bg-white w-full max-w-3xl rounded-xl shadow-2xl flex flex-col max-h-[95vh]">

        
        <div class="flex items-center justify-between px-6 py-4 border-b">
            <h2 class="text-base font-semibold text-gray-800">Add New Product</h2>
            <button onclick="closeModal('addProductModal')" class="text-gray-400 hover:text-gray-700 text-2xl leading-none">&times;</button>
        </div>

        <form method="POST" action="<?php echo e(route('dashboard.products.store')); ?>" enctype="multipart/form-data"
              class="overflow-y-auto px-6 py-5 space-y-4" id="addProductForm">
            <?php echo csrf_field(); ?>

            
            <div>
                <label class="block text-sm text-gray-700 mb-1">Select Category</label>
                <div class="relative">
                    <select name="category_id" id="addCategorySelect" required onchange="loadSubcategories(this.value)"
                            class="w-full appearance-none border border-gray-300 rounded px-3 py-2.5 text-sm text-gray-700 bg-white outline-none focus:border-blue-400 pr-8">
                        <option value="">Select category</option>
                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($cat->id); ?>"><?php echo e($cat->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <span class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs">&#9660;</span>
                </div>
            </div>

            
            <div>
                <label class="block text-sm text-gray-700 mb-1">Select Sub Category</label>
                <div class="relative">
                    <select name="subcategory_id" id="addSubcategorySelect"
                            class="w-full appearance-none border border-gray-300 rounded px-3 py-2.5 text-sm text-gray-700 bg-white outline-none focus:border-blue-400 pr-8">
                        <option value="">Select sub category</option>
                        <?php $__currentLoopData = $subcategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sub): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($sub->id); ?>" data-parent="<?php echo e($sub->parent_id); ?>"><?php echo e($sub->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <span class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs">&#9660;</span>
                </div>
            </div>

            
            <div>
                <label class="block text-sm text-gray-700 mb-1">Product Image</label>
                <div class="flex items-center border border-gray-300 rounded overflow-hidden">
                    <label class="bg-gray-100 border-r border-gray-300 px-3 py-2 text-sm text-gray-600 cursor-pointer whitespace-nowrap hover:bg-gray-200">
                        Choose Files
                        <input type="file" name="images[]" multiple accept="image/*" class="hidden"
                               onchange="updateFileLabel(this, 'addImageLabel')">
                    </label>
                    <span id="addImageLabel" class="px-3 text-sm text-gray-400">No file chosen</span>
                </div>
            </div>

            
            <div>
                <label class="block text-sm text-gray-700 mb-1">Product Name</label>
                <input type="text" name="name" required placeholder="Enter product name"
                       class="w-full border border-gray-300 rounded px-3 py-2.5 text-sm text-gray-700 outline-none focus:border-blue-400">
            </div>

            
            <div>
                <label class="block text-sm text-gray-700 mb-1">Product Description</label>
                <textarea name="description" rows="4" placeholder="Enter Product Description"
                          class="w-full border border-gray-300 rounded px-3 py-2.5 text-sm text-gray-700 outline-none resize-none focus:border-blue-400"></textarea>
            </div>

            
            <div>
                <div class="flex items-center justify-between mb-3">
                    <span class="text-sm font-semibold text-gray-800">Product Variants</span>
                    <button type="button" onclick="addVariant()"
                            class="px-3 py-1.5 bg-amber-400 hover:bg-amber-500 text-white text-xs font-semibold rounded">
                        Add Variant
                    </button>
                </div>

                <div id="variantsContainer" class="space-y-3">
                    
                    <div class="variant-row border border-gray-200 rounded p-3">
                        <div class="flex items-start gap-2 mb-2">
                            <div class="flex-1">
                                <p class="text-xs text-gray-500 mb-1">Quantity</p>
                                <input type="text" name="variants[0][quantity]" placeholder="e.g. 500-600"
                                       class="w-full border border-gray-300 rounded px-2 py-2 text-sm outline-none focus:border-blue-400">
                            </div>
                            <div class="w-32">
                                <p class="text-xs text-gray-500 mb-1">Unit</p>
                                <div class="relative">
                                    <select name="variants[0][unit]"
                                            class="w-full appearance-none border border-gray-300 rounded px-2 py-2 text-sm bg-white outline-none pr-6">
                                        <option>Gram</option><option>Kg</option><option>Pc</option><option>Litre</option>
                                    </select>
                                    <span class="pointer-events-none absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 text-xs">&#9660;</span>
                                </div>
                            </div>
                            <div class="flex-1">
                                <p class="text-xs text-gray-500 mb-1">Piece</p>
                                <input type="text" name="variants[0][piece]" placeholder="e.g. 6-8 pieces"
                                       class="w-full border border-gray-300 rounded px-2 py-2 text-sm outline-none focus:border-blue-400">
                            </div>
                            <div class="flex-1">
                                <p class="text-xs text-gray-500 mb-1">MRP</p>
                                <div class="relative">
                                    <span class="absolute left-2 top-1/2 -translate-y-1/2 text-gray-400 text-xs">₹</span>
                                    <input type="number" name="variants[0][mrp]" min="0" step="0.01"
                                           class="w-full border border-gray-300 rounded pl-5 pr-2 py-2 text-sm outline-none focus:border-blue-400">
                                </div>
                            </div>
                            <div class="flex-1">
                                <p class="text-xs text-gray-500 mb-1">Selling Price</p>
                                <div class="relative">
                                    <span class="absolute left-2 top-1/2 -translate-y-1/2 text-gray-400 text-xs">₹</span>
                                    <input type="number" name="variants[0][selling_price]" min="0" step="0.01"
                                           class="w-full border border-gray-300 rounded pl-5 pr-2 py-2 text-sm outline-none focus:border-blue-400">
                                </div>
                            </div>
                            <div class="flex-1">
                                <p class="text-xs text-gray-500 mb-1">Save Offer (%)</p>
                                <div class="relative">
                                    <input type="number" name="variants[0][save_offer]" min="0" max="100" step="0.1"
                                           class="w-full border border-gray-300 rounded px-2 pr-5 py-2 text-sm outline-none focus:border-blue-400">
                                    <span class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 text-xs">%</span>
                                </div>
                            </div>
                            <button type="button" onclick="removeVariant(this)"
                                    class="mt-5 text-gray-400 hover:text-red-500 text-lg leading-none">&times;</button>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Admin Amount(Rs)</p>
                                <input type="number" name="variants[0][admin_amount]" min="0" step="0.01"
                                       class="w-full border border-gray-300 rounded px-2 py-2 text-sm outline-none focus:border-blue-400">
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Vendor Amount(Rs)</p>
                                <input type="number" name="variants[0][vendor_amount]" min="0" step="0.01"
                                       class="w-full border border-gray-300 rounded px-2 py-2 text-sm outline-none focus:border-blue-400">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="flex justify-end gap-3 pt-2 pb-1">
                <button type="button" onclick="closeModal('addProductModal')"
                        class="px-6 py-2 rounded border border-gray-300 text-sm text-gray-600 hover:bg-gray-50 font-medium">Cancel</button>
                <button type="submit"
                        class="px-6 py-2 rounded bg-red-500 hover:bg-red-600 text-white text-sm font-semibold">Save</button>
            </div>
        </form>
    </div>
</div>


<div id="editProductModal" class="fixed inset-0 z-[200] hidden items-center justify-center bg-black/60 backdrop-blur-sm p-4">
    <div class="bg-white w-full max-w-2xl rounded-2xl shadow-2xl flex flex-col max-h-[95vh]">
        <div class="p-5 border-b flex justify-between items-center bg-slate-50 rounded-t-2xl">
            <h2 class="font-bold text-slate-700">Edit Product</h2>
            <button onclick="closeModal('editProductModal')" class="text-slate-400 hover:text-red-500 text-2xl">&times;</button>
        </div>
        <form id="editProductForm" method="POST" class="overflow-y-auto p-6 space-y-4">
            <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
            <div>
                <label class="text-xs font-bold text-slate-600 block mb-1">Category</label>
                <select name="category_id" id="editCatId" class="w-full border border-slate-200 rounded-lg px-4 py-2.5 text-sm outline-none">
                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($cat->id); ?>"><?php echo e($cat->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div>
                <label class="text-xs font-bold text-slate-600 block mb-1">Product Name</label>
                <input type="text" name="name" id="editProductName" required
                       class="w-full border border-slate-200 rounded-lg px-4 py-2.5 text-sm outline-none">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-xs font-bold text-slate-600 block mb-1">Price (₹)</label>
                    <input type="number" name="price" id="editPrice" min="0" step="0.01"
                           class="w-full border border-slate-200 rounded-lg px-4 py-2.5 text-sm outline-none">
                </div>
                <div>
                    <label class="text-xs font-bold text-slate-600 block mb-1">Stock</label>
                    <input type="number" name="stock" id="editStock" min="0"
                           class="w-full border border-slate-200 rounded-lg px-4 py-2.5 text-sm outline-none">
                </div>
            </div>
            <div>
                <label class="text-xs font-bold text-slate-600 block mb-1">Status</label>
                <select name="is_active" id="editIsActive" class="w-full border border-slate-200 rounded-lg px-4 py-2.5 text-sm outline-none">
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>
            <div class="flex justify-end gap-3 pt-4">
                <button type="submit" class="bg-indigo-600 text-white px-8 py-2.5 rounded-lg font-bold text-sm">Update</button>
                <button type="button" onclick="closeModal('editProductModal')"
                        class="bg-slate-500 text-white px-8 py-2.5 rounded-lg font-bold text-sm">Cancel</button>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
function openModal(id) { document.getElementById(id).classList.remove('hidden'); document.getElementById(id).classList.add('flex'); }
function closeModal(id) { document.getElementById(id).classList.add('hidden'); document.getElementById(id).classList.remove('flex'); }

function openEditModal(id, name, catId, price, stock, isActive) {
    document.getElementById('editProductName').value  = name;
    document.getElementById('editCatId').value        = catId;
    document.getElementById('editPrice').value        = price;
    document.getElementById('editStock').value        = stock;
    document.getElementById('editIsActive').value     = isActive;
    document.getElementById('editProductForm').action = '/products/' + id;
    openModal('editProductModal');
}

// File label updater
function updateFileLabel(input, labelId) {
    const label = document.getElementById(labelId);
    if (input.files.length > 1) {
        label.textContent = input.files.length + ' files chosen';
    } else if (input.files.length === 1) {
        label.textContent = input.files[0].name;
    } else {
        label.textContent = 'No file chosen';
    }
}

// Subcategory filter by category
function loadSubcategories(categoryId) {
    const select = document.getElementById('addSubcategorySelect');
    const options = select.querySelectorAll('option');
    options.forEach(opt => {
        if (opt.value === '') return;
        opt.style.display = (!categoryId || opt.dataset.parent == categoryId) ? '' : 'none';
    });
    select.value = '';
}

// Variant management
let variantIndex = 1;

function addVariant() {
    const idx = variantIndex++;
    const container = document.getElementById('variantsContainer');
    const div = document.createElement('div');
    div.className = 'variant-row border border-gray-200 rounded p-3';
    div.innerHTML = `
        <div class="flex items-start gap-2 mb-2">
            <div class="flex-1">
                <p class="text-xs text-gray-500 mb-1">Quantity</p>
                <input type="text" name="variants[${idx}][quantity]" placeholder="e.g. 500-600"
                       class="w-full border border-gray-300 rounded px-2 py-2 text-sm outline-none focus:border-blue-400">
            </div>
            <div class="w-32">
                <p class="text-xs text-gray-500 mb-1">Unit</p>
                <div class="relative">
                    <select name="variants[${idx}][unit]"
                            class="w-full appearance-none border border-gray-300 rounded px-2 py-2 text-sm bg-white outline-none pr-6">
                        <option>Gram</option><option>Kg</option><option>Pc</option><option>Litre</option>
                    </select>
                    <span class="pointer-events-none absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 text-xs">&#9660;</span>
                </div>
            </div>
            <div class="flex-1">
                <p class="text-xs text-gray-500 mb-1">Piece</p>
                <input type="text" name="variants[${idx}][piece]" placeholder="e.g. 6-8 pieces"
                       class="w-full border border-gray-300 rounded px-2 py-2 text-sm outline-none focus:border-blue-400">
            </div>
            <div class="flex-1">
                <p class="text-xs text-gray-500 mb-1">MRP</p>
                <div class="relative">
                    <span class="absolute left-2 top-1/2 -translate-y-1/2 text-gray-400 text-xs">₹</span>
                    <input type="number" name="variants[${idx}][mrp]" min="0" step="0.01"
                           class="w-full border border-gray-300 rounded pl-5 pr-2 py-2 text-sm outline-none focus:border-blue-400">
                </div>
            </div>
            <div class="flex-1">
                <p class="text-xs text-gray-500 mb-1">Selling Price</p>
                <div class="relative">
                    <span class="absolute left-2 top-1/2 -translate-y-1/2 text-gray-400 text-xs">₹</span>
                    <input type="number" name="variants[${idx}][selling_price]" min="0" step="0.01"
                           class="w-full border border-gray-300 rounded pl-5 pr-2 py-2 text-sm outline-none focus:border-blue-400">
                </div>
            </div>
            <div class="flex-1">
                <p class="text-xs text-gray-500 mb-1">Save Offer (%)</p>
                <div class="relative">
                    <input type="number" name="variants[${idx}][save_offer]" min="0" max="100" step="0.1"
                           class="w-full border border-gray-300 rounded px-2 pr-5 py-2 text-sm outline-none focus:border-blue-400">
                    <span class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 text-xs">%</span>
                </div>
            </div>
            <button type="button" onclick="removeVariant(this)"
                    class="mt-5 text-gray-400 hover:text-red-500 text-lg leading-none">&times;</button>
        </div>
        <div class="grid grid-cols-2 gap-3">
            <div>
                <p class="text-xs text-gray-500 mb-1">Admin Amount(Rs)</p>
                <input type="number" name="variants[${idx}][admin_amount]" min="0" step="0.01"
                       class="w-full border border-gray-300 rounded px-2 py-2 text-sm outline-none focus:border-blue-400">
            </div>
            <div>
                <p class="text-xs text-gray-500 mb-1">Vendor Amount(Rs)</p>
                <input type="number" name="variants[${idx}][vendor_amount]" min="0" step="0.01"
                       class="w-full border border-gray-300 rounded px-2 py-2 text-sm outline-none focus:border-blue-400">
            </div>
        </div>`;
    container.appendChild(div);
}

function removeVariant(btn) {
    const rows = document.querySelectorAll('.variant-row');
    if (rows.length > 1) {
        btn.closest('.variant-row').remove();
    }
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\FarmSea-dashboard\resources\views/dashboard/products/index.blade.php ENDPATH**/ ?>