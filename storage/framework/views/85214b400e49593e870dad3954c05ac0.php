<?php
    $prefix = $mode === 'edit' ? 'edit' : 'add';
    $requiredImage = $mode === 'add';
?>

<div>
    <label class="mb-1.5 block text-sm font-medium">Banner Image <?php echo e($requiredImage ? '*' : ''); ?></label>
    <input type="file" name="image" accept="image/*" <?php echo e($requiredImage ? 'required' : ''); ?>

           class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm">
    <p class="mt-1 text-xs text-slate-400">Recommended size: 1800 x 700 or wider landscape image.</p>
</div>

<div class="grid gap-4 md:grid-cols-2">
    <div>
        <label class="mb-1.5 block text-sm font-medium">Badge</label>
        <input type="text" name="badge" id="<?php echo e($prefix); ?>Badge" placeholder="Farm Fresh Daily"
               class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-purple-500">
    </div>
    <div>
        <label class="mb-1.5 block text-sm font-medium">Sort Order</label>
        <input type="number" name="sort_order" id="<?php echo e($prefix); ?>SortOrder" value="0" min="0"
               class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-purple-500">
    </div>
</div>

<div class="grid gap-4 md:grid-cols-2">
    <div>
        <label class="mb-1.5 block text-sm font-medium">Title Line 1 <span class="text-red-500">*</span></label>
        <input type="text" name="title_1" id="<?php echo e($prefix); ?>Title1" required placeholder="Premium"
               class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-purple-500">
    </div>
    <div>
        <label class="mb-1.5 block text-sm font-medium">Title Line 2</label>
        <input type="text" name="title_2" id="<?php echo e($prefix); ?>Title2" placeholder="Chicken Delivered Fresh"
               class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-purple-500">
    </div>
</div>

<div>
    <label class="mb-1.5 block text-sm font-medium text-gray-600">Description</label>
    <textarea name="description" id="<?php echo e($prefix); ?>Description" rows="4" placeholder="Banner short text..."
              class="w-full resize-none rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-purple-500"></textarea>
</div>

<div class="grid gap-4 md:grid-cols-2">
    <div>
        <label class="mb-1.5 block text-sm font-medium">Button Text</label>
        <input type="text" name="button_text" id="<?php echo e($prefix); ?>ButtonText" value="Shop Now"
               class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-purple-500">
    </div>
    <div>
        <label class="mb-1.5 block text-sm font-medium">Link URL</label>
        <input type="text" name="link_url" id="<?php echo e($prefix); ?>LinkUrl" placeholder="/shop"
               class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-purple-500">
    </div>
</div>

<label class="flex cursor-pointer items-center gap-3 rounded-xl border border-slate-200 bg-slate-50 px-4 py-3">
    <input type="checkbox" name="is_active" id="<?php echo e($prefix); ?>IsActive" value="1" checked
           class="h-4 w-4 rounded border-gray-300 text-green-600">
    <span class="text-sm font-semibold text-slate-700">Active on home page</span>
</label>
<?php /**PATH C:\BMDU Work\FarmSea-dashboard\resources\views\dashboard\banners\partials\form.blade.php ENDPATH**/ ?>