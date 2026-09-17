<?php
    $requiredImage = $mode === 'add';
?>

<div class="grid gap-4 md:grid-cols-2">
    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
        <label class="mb-1.5 block text-sm font-bold text-slate-800">Desktop Banner Image <?php echo e($requiredImage ? '*' : ''); ?></label>
        <input type="file" name="image" accept="image/*" <?php echo e($requiredImage ? 'required' : ''); ?>

               class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm">
        <p class="mt-2 text-xs text-slate-400">Recommended: 1800 × 700 landscape.</p>
    </div>

    <div class="rounded-2xl border border-amber-200 bg-amber-50/60 p-4">
        <label class="mb-1.5 block text-sm font-bold text-amber-900">Mobile Banner Image</label>
        <input type="file" name="mobile_image" accept="image/*"
               class="w-full rounded-xl border border-amber-200 bg-white px-4 py-3 text-sm">
        <p class="mt-2 text-xs text-amber-500">Optional: 750 × 900 portrait. Desktop image is used if no mobile image is uploaded.</p>
    </div>
</div>
<?php /**PATH C:\BMDU Work\porville-fresh-cut\resources\views/dashboard/banners/partials/form.blade.php ENDPATH**/ ?>