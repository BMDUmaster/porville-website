<?php $__env->startSection('title', 'All Categories — Porville'); ?>
<?php $__env->startSection('content'); ?>

<?php ($focus = request('focus')); ?>

<section class="bg-black py-12 md:py-16">
    <div class="mx-auto max-w-6xl px-4 text-center">
        <p class="text-[11px] font-extrabold uppercase tracking-[0.3em] text-amber-400">Browse</p>
        <h1 class="font-classic mt-3 text-4xl font-bold text-white md:text-5xl">All Categories</h1>
        <?php if($focus): ?>
            <p class="mt-3 text-sm text-stone-300">
                Highlighted category: <span class="font-bold text-amber-400"><?php echo e(str_replace('-', ' ', $focus)); ?></span>
            </p>
        <?php endif; ?>
    </div>
</section>

<section class="bg-[#faf7f0] py-12 md:py-16">
    <div class="mx-auto max-w-6xl px-4">
        <div class="grid grid-cols-2 gap-5 md:grid-cols-4">
            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php ($isFocused = $focus === $cat->slug); ?>
                <a
                    id="category-<?php echo e($cat->slug); ?>"
                    href="<?php echo e(route('frontend.products', ['category' => $cat->slug])); ?>"
                    class="group block overflow-hidden rounded-2xl border bg-white shadow-sm transition duration-300 hover:-translate-y-1 hover:border-amber-400 hover:shadow-lg <?php echo e($isFocused ? 'ring-2 ring-amber-500 border-amber-300 shadow-lg' : 'border-gray-100'); ?>"
                >
                    <div class="aspect-square overflow-hidden bg-gray-50">
                        <?php if($cat->image): ?>
                            <img src="<?php echo e(asset('storage/'.$cat->image)); ?>" alt="<?php echo e($cat->name); ?>" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105">
                        <?php else: ?>
                            <div class="flex h-full w-full items-center justify-center text-5xl text-amber-200">
                                <i class="fa-solid fa-drumstick-bite"></i>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="border-t border-gray-100 p-4">
                        <p class="font-classic text-base font-bold text-slate-900"><?php echo e($cat->name); ?></p>
                        <p class="mt-1 text-xs font-semibold text-amber-700"><?php echo e($cat->products_count); ?> <?php echo e(Str::plural('product', $cat->products_count)); ?></p>
                        <?php if($cat->children->count()): ?>
                            <div class="mt-2 flex flex-wrap gap-1">
                                <?php $__currentLoopData = $cat->children->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sub): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <span class="rounded-full bg-amber-50 px-2 py-0.5 text-[10px] font-semibold text-amber-700"><?php echo e($sub->name); ?></span>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</section>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\BMDU Work\porville-fresh-cut\resources\views/frontend/pages/categories.blade.php ENDPATH**/ ?>