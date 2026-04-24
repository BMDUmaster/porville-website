<?php $__env->startSection('title', 'All Categories'); ?>
<?php $__env->startSection('content'); ?>

<?php ($focus = request('focus')); ?>

<div class="mx-auto max-w-6xl px-4 py-10">
    <div class="mb-6 flex flex-col gap-2">
        <h1 class="nunito text-2xl font-extrabold text-gray-800">All Categories</h1>
        <?php if($focus): ?>
            <p class="text-sm text-gray-500">
                Highlighted category: <span class="font-bold text-blue-600"><?php echo e(str_replace('-', ' ', $focus)); ?></span>
            </p>
        <?php endif; ?>
    </div>

    <div class="grid grid-cols-2 gap-5 md:grid-cols-4">
        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php ($isFocused = $focus === $cat->slug); ?>
            <a
                id="category-<?php echo e($cat->slug); ?>"
                href="<?php echo e(route('frontend.products', ['category' => $cat->slug])); ?>"
                class="block overflow-hidden rounded-2xl border bg-white transition duration-300 hover:-translate-y-1 hover:border-green-400 hover:shadow-lg <?php echo e($isFocused ? 'ring-2 ring-blue-500 border-blue-300 shadow-lg' : 'border-gray-200'); ?>"
            >
                <div class="aspect-square overflow-hidden bg-gray-50">
                    <?php if($cat->image): ?>
                        <img src="<?php echo e(asset('storage/'.$cat->image)); ?>" alt="<?php echo e($cat->name); ?>" class="h-full w-full object-cover transition-transform duration-500 hover:scale-105">
                    <?php else: ?>
                        <div class="flex h-full w-full items-center justify-center text-5xl text-gray-300">C</div>
                    <?php endif; ?>
                </div>
                <div class="border-t p-4">
                    <p class="font-bold text-gray-800"><?php echo e($cat->name); ?></p>
                    <p class="mt-1 text-xs text-gray-400"><?php echo e($cat->products_count); ?> products</p>
                    <?php if($cat->children->count()): ?>
                        <div class="mt-2 flex flex-wrap gap-1">
                            <?php $__currentLoopData = $cat->children->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sub): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <span class="rounded-full bg-gray-100 px-2 py-0.5 text-[10px] text-gray-600"><?php echo e($sub->name); ?></span>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\FarmSea-dashboard\resources\views/frontend/pages/categories.blade.php ENDPATH**/ ?>