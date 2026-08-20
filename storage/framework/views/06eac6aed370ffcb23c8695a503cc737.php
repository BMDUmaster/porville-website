<?php $__env->startSection('title', 'System Check'); ?>
<?php $__env->startSection('page_title', 'Server System Check'); ?>

<?php $__env->startSection('content'); ?>
<div class="p-6 max-w-4xl">
    <?php if($hasErrors): ?>
        <div class="mb-4 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
            <strong>Issues found.</strong> Fix the items marked in red below, then try adding categories/products again.
        </div>
    <?php else: ?>
        <div class="mb-4 rounded-xl border border-green-200 bg-green-50 p-4 text-sm text-green-700">
            All critical checks passed.
        </div>
    <?php endif; ?>

    <div class="bg-white rounded-xl border divide-y text-sm">
        <div class="p-4 flex justify-between">
            <span>APP_URL</span>
            <span class="font-mono"><?php echo e($checks['app_url']); ?></span>
        </div>
        <div class="p-4 flex justify-between">
            <span>Database</span>
            <span class="<?php echo e($checks['database']['ok'] ? 'text-green-600' : 'text-red-600'); ?>"><?php echo e($checks['database']['message']); ?></span>
        </div>
        <div class="p-4 flex justify-between">
            <span>storage/app/public writable</span>
            <span class="<?php echo e($checks['storage_writable'] ? 'text-green-600' : 'text-red-600'); ?>"><?php echo e($checks['storage_writable'] ? 'Yes' : 'No — chmod 775 storage'); ?></span>
        </div>
        <div class="p-4 flex justify-between">
            <span>PHP GD extension</span>
            <span class="<?php echo e($checks['gd'] ? 'text-green-600' : 'text-amber-600'); ?>"><?php echo e($checks['gd'] ? 'Enabled' : 'Disabled (uploads use original format)'); ?></span>
        </div>
        <div class="p-4 flex justify-between">
            <span>Logo file (public/images/Farmsea.webp)</span>
            <span class="<?php echo e($checks['logo_file'] ? 'text-green-600' : 'text-red-600'); ?>"><?php echo e($checks['logo_file'] ? 'Found' : 'Missing'); ?></span>
        </div>
        <div class="p-4">
            <p class="font-semibold mb-2">Pending migrations: <?php echo e($checks['migrations']['pending']); ?></p>
            <?php if($checks['migrations']['pending'] > 0): ?>
                <p class="text-red-600 mb-2">Run on server: <code class="bg-slate-100 px-2 py-1 rounded">php artisan migrate --force</code></p>
            <?php endif; ?>
            <pre class="text-xs bg-slate-50 p-3 rounded-lg overflow-x-auto max-h-48"><?php echo e($checks['migrations']['output']); ?></pre>
        </div>
        <?php if(!empty($checks['required_columns']['missing'])): ?>
        <div class="p-4">
            <p class="font-semibold text-red-600 mb-2">Missing database columns (causes 500 on add product/coupon):</p>
            <pre class="text-xs bg-red-50 p-3 rounded-lg"><?php echo e(json_encode($checks['required_columns']['missing'], JSON_PRETTY_PRINT)); ?></pre>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\BMDU Work\FarmSea-dashboard\resources\views\dashboard\system-check.blade.php ENDPATH**/ ?>