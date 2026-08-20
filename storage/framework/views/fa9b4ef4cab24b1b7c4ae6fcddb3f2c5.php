<?php $__env->startSection('title', 'Reviews'); ?>
<?php $__env->startSection('page_title', 'Customer Reviews'); ?>

<?php $__env->startSection('content'); ?>
<div class="p-4 md:p-6">
    <div class="mb-5 grid grid-cols-2 gap-3 md:grid-cols-4">
        <?php $__currentLoopData = ['all' => 'All Reviews', 'pending' => 'Pending', 'approved' => 'Approved', 'hidden' => 'Hidden']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e(route('dashboard.reviews', $key === 'all' ? [] : ['status' => $key])); ?>" class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                <p class="text-xs font-bold uppercase text-slate-400"><?php echo e($label); ?></p>
                <p class="mt-2 text-2xl font-black text-slate-900"><?php echo e($counts[$key]); ?></p>
            </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <form class="mb-5 flex flex-wrap gap-3 rounded-2xl border border-slate-200 bg-white p-4">
        <select name="status" class="rounded-xl border border-slate-300 px-4 py-2.5 text-sm">
            <option value="">All statuses</option>
            <?php $__currentLoopData = \App\Models\Review::STATUSES; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($status); ?>" <?php echo e(request('status') === $status ? 'selected' : ''); ?>><?php echo e(ucfirst($status)); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
        <select name="rating" class="rounded-xl border border-slate-300 px-4 py-2.5 text-sm">
            <option value="">All ratings</option>
            <?php $__currentLoopData = range(5, 1); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rating): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($rating); ?>" <?php echo e((int) request('rating') === $rating ? 'selected' : ''); ?>><?php echo e($rating); ?> Stars</option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
        <button class="rounded-xl bg-slate-900 px-5 py-2.5 text-sm font-bold text-white">Filter</button>
    </form>

    <div class="space-y-4">
        <?php $__empty_1 = true; $__currentLoopData = $reviews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex flex-col gap-4 md:flex-row md:items-start md:justify-between">
                    <div class="min-w-0">
                        <div class="text-xl text-yellow-400"><?php echo e(str_repeat('★', $review->rating)); ?><span class="text-slate-200"><?php echo e(str_repeat('★', 5 - $review->rating)); ?></span></div>
                        <h2 class="mt-2 font-black text-slate-900"><?php echo e($review->user?->name ?? $review->order?->shipping_address['name'] ?? 'Customer'); ?></h2>
                        <p class="mt-1 text-xs text-slate-500">Order <?php echo e($review->order?->order_number ?: '#'.$review->order_id); ?> · <?php echo e($review->created_at->format('d M Y, h:i A')); ?></p>
                        <p class="mt-3 text-sm leading-6 text-slate-700"><?php echo e($review->comment ?: 'No written comment.'); ?></p>
                        <p class="mt-3 text-xs font-semibold text-slate-400">Product: <span class="text-slate-700"><?php echo e($review->product?->name ?? 'Not selected'); ?></span></p>
                    </div>
                    <div class="flex flex-wrap items-center gap-2">
                        <form method="POST" action="<?php echo e(route('dashboard.reviews.update', $review)); ?>" class="flex gap-2">
                            <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                            <select name="status" class="rounded-xl border border-slate-300 px-3 py-2 text-xs font-bold">
                                <?php $__currentLoopData = \App\Models\Review::STATUSES; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($status); ?>" <?php echo e($review->status === $status ? 'selected' : ''); ?>><?php echo e(ucfirst($status)); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <select name="display_on" class="rounded-xl border border-slate-300 px-3 py-2 text-xs font-bold">
                                <option value="home" <?php echo e($review->display_on === 'home' ? 'selected' : ''); ?>>Home Page</option>
                                <option value="product" <?php echo e($review->display_on === 'product' ? 'selected' : ''); ?>>Product Page</option>
                            </select>
                            <button class="rounded-xl bg-blue-600 px-4 py-2 text-xs font-bold text-white">Update</button>
                        </form>
                        <form method="POST" action="<?php echo e(route('dashboard.reviews.destroy', $review)); ?>" onsubmit="return confirm('Delete this review?')">
                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                            <button class="rounded-xl bg-red-50 px-4 py-2 text-xs font-bold text-red-600">Delete</button>
                        </form>
                    </div>
                </div>
            </article>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="rounded-2xl border border-dashed border-slate-300 bg-white p-12 text-center text-slate-400">No reviews found.</div>
        <?php endif; ?>
    </div>
    <div class="mt-5"><?php echo e($reviews->links()); ?></div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\BMDU Work\FarmSea-dashboard\resources\views\dashboard\reviews\index.blade.php ENDPATH**/ ?>