<?php $__env->startSection('title', 'Home Banners'); ?>
<?php $__env->startSection('page_title', 'Home Banner Management'); ?>

<?php $__env->startSection('content'); ?>
<div class="p-4 sm:p-6">
    <div class="mb-4 flex flex-col gap-3 rounded-xl border bg-white p-4 md:flex-row md:items-center">
        <form method="GET" class="relative w-full flex-1">
            <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
            <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Search banners..."
                   class="w-full rounded-lg border py-2.5 pl-9 pr-4 text-sm outline-none focus:border-amber-400">
        </form>
        <button onclick="openModal('addModal')"
                class="flex w-full items-center justify-center gap-2 rounded-xl bg-amber-600 px-5 py-2.5 text-sm font-medium text-white md:w-auto">
            <i class="fa-solid fa-plus"></i> Add Banner
        </button>
    </div>

    <div class="overflow-x-auto rounded-xl border bg-white">
        <table class="w-full min-w-[900px] text-left">
            <thead class="bg-gradient-to-r from-amber-700 to-amber-600 text-white">
                <tr>
                    <th class="px-4 py-3 text-xs uppercase">Sr.No.</th>
                    <th class="px-4 py-3 text-xs uppercase">Banner</th>
                    
                    <th class="px-4 py-3 text-xs uppercase">Status</th>
                    <th class="px-4 py-3 text-center text-xs uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php $__empty_1 = true; $__currentLoopData = $banners; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $banner): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="transition hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm text-gray-500"><?php echo e($banners->firstItem() + $index); ?></td>
                        <td class="px-4 py-3">
                            <div class="flex items-end gap-2">
                                <div>
                                    <p class="mb-1 text-[9px] font-bold uppercase text-slate-400">Desktop</p>
                                    <img src="<?php echo e($banner->image_url); ?>" alt="<?php echo e($banner->title_1); ?> desktop banner" class="h-20 w-36 rounded-lg object-cover">
                                </div>
                                <?php if($banner->mobile_image_url): ?>
                                    <div>
                                        <p class="mb-1 text-[9px] font-bold uppercase text-amber-500">Mobile</p>
                                        <img src="<?php echo e($banner->mobile_image_url); ?>" alt="<?php echo e($banner->title_1); ?> mobile banner" class="h-20 w-14 rounded-lg border border-amber-200 object-cover">
                                    </div>
                                <?php endif; ?>
                            </div>
                        </td>
                       
                       
                        <td class="px-4 py-3">
                            <span class="rounded-full px-3 py-1 text-xs font-bold <?php echo e($banner->is_active ? 'bg-green-100 text-green-700' : 'bg-slate-100 text-slate-500'); ?>">
                                <?php echo e($banner->is_active ? 'Active' : 'Inactive'); ?>

                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex justify-center gap-2">
                                <button type="button"
                                        class="rounded-lg p-2 text-xs text-amber-600 hover:bg-amber-100"
                                        data-id="<?php echo e($banner->id); ?>"
                                        data-badge="<?php echo e(e($banner->badge)); ?>"
                                        data-title-1="<?php echo e(e($banner->title_1)); ?>"
                                        data-title-2="<?php echo e(e($banner->title_2)); ?>"
                                        data-description="<?php echo e(e($banner->description)); ?>"
                                        data-button-text="<?php echo e(e($banner->button_text)); ?>"
                                        data-link-url="<?php echo e(e($banner->link_url)); ?>"
                                        data-sort-order="<?php echo e($banner->sort_order); ?>"
                                        data-is-active="<?php echo e($banner->is_active ? '1' : '0'); ?>"
                                        onclick="openEditModal(this)">
                                    <i class="fa-regular fa-pen-to-square"></i>
                                </button>
                                <form method="POST" action="<?php echo e(route('dashboard.banners.destroy', $banner)); ?>"
                                      onsubmit="return confirm('Delete this banner?')">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="rounded-lg p-2 text-xs text-red-500 hover:bg-red-100">
                                        <i class="fa-regular fa-trash-can"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7" class="px-4 py-10 text-center text-gray-400">No banners found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="mt-4"><?php echo e($banners->withQueryString()->links()); ?></div>
</div>

<div id="addModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 p-4 backdrop-blur-sm">
    <div class="max-h-[92vh] w-full max-w-3xl overflow-y-auto rounded-2xl bg-white shadow-2xl">
        <div class="flex items-center justify-between border-b px-7 py-5">
            <h2 class="text-xl font-semibold">Add Home Banner</h2>
            <button onclick="closeModal('addModal')" class="text-2xl leading-none text-gray-400 hover:text-gray-600">&times;</button>
        </div>
        <form method="POST" action="<?php echo e(route('dashboard.banners.store')); ?>" enctype="multipart/form-data" class="space-y-5 px-7 py-6">
            <?php echo csrf_field(); ?>
            <?php echo $__env->make('dashboard.banners.partials.form', ['mode' => 'add'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <div class="flex gap-3 pt-1">
                <button type="submit" class="flex-1 rounded-xl bg-amber-600 py-3 text-sm font-medium text-white hover:bg-amber-700">Save Banner</button>
                <button type="button" onclick="closeModal('addModal')" class="flex-1 rounded-xl bg-gray-100 py-3 text-sm font-medium text-gray-600 hover:bg-gray-200">Cancel</button>
            </div>
        </form>
    </div>
</div>

<div id="editModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 p-4 backdrop-blur-sm">
    <div class="max-h-[92vh] w-full max-w-3xl overflow-y-auto rounded-2xl bg-white shadow-2xl">
        <div class="flex items-center justify-between border-b px-7 py-5">
            <h2 class="text-xl font-semibold">Edit Home Banner</h2>
            <button onclick="closeModal('editModal')" class="text-2xl leading-none text-gray-400 hover:text-gray-600">&times;</button>
        </div>
        <form id="editForm" method="POST" enctype="multipart/form-data" class="space-y-5 px-7 py-6">
            <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
            <?php echo $__env->make('dashboard.banners.partials.form', ['mode' => 'edit'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <div class="flex gap-3 pt-1">
                <button type="submit" class="flex-1 rounded-xl bg-amber-600 py-3 text-sm font-medium text-white hover:bg-amber-700">Update Banner</button>
                <button type="button" onclick="closeModal('editModal')" class="flex-1 rounded-xl bg-gray-100 py-3 text-sm font-medium text-gray-600 hover:bg-gray-200">Cancel</button>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
const bannerUpdateUrlTemplate = <?php echo json_encode(route('dashboard.banners.update', ['banner' => '__ID__']), 512) ?>;

function openModal(id) {
    document.getElementById(id).classList.remove('hidden');
    document.getElementById(id).classList.add('flex');
}

function closeModal(id) {
    document.getElementById(id).classList.add('hidden');
    document.getElementById(id).classList.remove('flex');
}

function fillField(id, value) {
    const input = document.getElementById(id);
    if (input) {
        input.value = value || '';
    }
}

function openEditModal(button) {
    document.getElementById('editForm').action = bannerUpdateUrlTemplate.replace('__ID__', encodeURIComponent(button.dataset.id));
    openModal('editModal');
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\BMDU Work\porville-fresh-cut\resources\views/dashboard/banners/index.blade.php ENDPATH**/ ?>