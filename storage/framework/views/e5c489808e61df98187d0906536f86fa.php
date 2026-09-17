<?php if($similar->count()): ?>
    <div class="<?php echo e($wrapperClass ?? 'mt-10'); ?>">
        <div class="mb-5 flex items-center justify-between gap-3">
            <h2 class="text-[24px] font-black text-slate-900">
                Similar <span class="text-[#b8862c]">Products</span>
            </h2>
            <a href="<?php echo e(route('frontend.products', ['category' => $product->category->slug ?? null])); ?>" class="text-[12px] font-black uppercase tracking-[0.18em] text-[#b8862c] transition hover:text-[#8f6a1c]">
                View All
            </a>
        </div>

        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <?php $__currentLoopData = $similar; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php echo $__env->make('frontend.partials.product-card', ['product' => $item], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
<?php endif; ?>
<?php /**PATH C:\BMDU Work\porville-fresh-cut\resources\views/frontend/partials/similar-products.blade.php ENDPATH**/ ?>