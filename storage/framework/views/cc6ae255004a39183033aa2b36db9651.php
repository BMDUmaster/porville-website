
<?php $__env->startSection('title', 'All Categories'); ?>
<?php $__env->startSection('content'); ?>
<div class="max-w-6xl mx-auto px-4 py-10">
    <h1 class="nunito font-extrabold text-2xl text-gray-800 mb-6">All Categories</h1>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-5">
        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <a href="<?php echo e(route('frontend.products', ['category' => $cat->slug])); ?>"
           class="bg-white rounded-2xl border hover:border-green-400 hover:shadow-lg transition overflow-hidden block">
            <div class="aspect-square bg-gray-50 overflow-hidden">
                <?php if($cat->image): ?>
                    <img src="<?php echo e(asset('storage/'.$cat->image)); ?>" class="w-full h-full object-cover">
                <?php else: ?>
                    <div class="w-full h-full flex items-center justify-center text-5xl">🥩</div>
                <?php endif; ?>
            </div>
            <div class="p-4 border-t">
                <p class="font-bold text-gray-800"><?php echo e($cat->name); ?></p>
                <p class="text-xs text-gray-400 mt-1"><?php echo e($cat->products_count); ?> products</p>
                <?php if($cat->children->count()): ?>
                    <div class="mt-2 flex flex-wrap gap-1">
                        <?php $__currentLoopData = $cat->children->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sub): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <span class="text-[10px] bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full"><?php echo e($sub->name); ?></span>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php endif; ?>
            </div>
        </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\FarmSea-dashboard\resources\views\frontend\pages\categories.blade.php ENDPATH**/ ?>