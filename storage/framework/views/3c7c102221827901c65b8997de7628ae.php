
<?php $__env->startSection('title', 'Category Orders'); ?>
<?php $__env->startSection('page_title', 'Category Orders'); ?>

<?php $__env->startSection('content'); ?>
<div class="p-4 sm:p-6 max-w-7xl">

    
    <div class="grid grid-cols-2 sm:grid-cols-5 gap-3 mb-6">
        <?php
            $totalItems = $categories->count();
            $totalWeight = $categories->sum(fn($c) => $c->products->sum(fn($p) => $p->order_items_count));
        ?>
        <div class="bg-white border rounded-xl p-4"><p class="text-xs text-gray-500">Total Items</p><p class="text-xl font-bold"><?php echo e($totalItems); ?></p></div>
        <div class="bg-white border rounded-xl p-4"><p class="text-xs text-gray-500">Total Weight</p><p class="text-xl font-bold text-purple-600"><?php echo e($totalWeight); ?> kg</p></div>
    </div>

    
    <div class="bg-white rounded-xl border overflow-x-auto">
        <table class="w-full min-w-[800px]">
            <thead style="background: linear-gradient(to right, #7e22ce, #4f46e5); color: white;">
                <tr>
                    <th class="px-4 py-3 text-left text-xs uppercase">Image</th>
                    <th class="px-4 py-3 text-left text-xs uppercase">SR</th>
                    <th class="px-4 py-3 text-left text-xs uppercase">Category</th>
                    <th class="px-4 py-3 text-left text-xs uppercase">ID</th>
                    <th class="px-4 py-3 text-left text-xs uppercase">Products</th>
                    <th class="px-4 py-3 text-left text-xs uppercase">Demand</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php $__empty_1 = true; $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php
                    $qty = $cat->products->sum(fn($p) => $p->order_items_count);
                    $demand = $qty > 300 ? ['High','text-red-600'] : ($qty > 150 ? ['Medium','text-yellow-600'] : ['Low','text-green-600']);
                ?>
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3">
                        <?php if($cat->image): ?>
                            <img src="<?php echo e(asset('storage/'.$cat->image)); ?>" class="w-12 h-12 rounded-lg object-cover">
                        <?php else: ?>
                            <div class="w-12 h-12 rounded-lg bg-gray-100 flex items-center justify-center text-gray-400"><i class="fa-regular fa-image"></i></div>
                        <?php endif; ?>
                    </td>
                    <td class="px-4 py-3 text-sm"><?php echo e($i + 1); ?></td>
                    <td class="px-4 py-3">
                        <p class="font-semibold text-sm"><?php echo e($cat->name); ?></p>
                    </td>
                    <td class="px-4 py-3 text-xs text-gray-500"><?php echo e($cat->id); ?></td>
                    <td class="px-4 py-3 text-blue-600 font-bold text-sm"><?php echo e($cat->products_count); ?></td>
                    <td class="px-4 py-3 font-semibold text-sm <?php echo e($demand[1]); ?>"><?php echo e($demand[0]); ?></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="6" class="px-4 py-8 text-center text-gray-400">No categories found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\BMDU Work\FarmSea-dashboard\resources\views\dashboard\orders\category.blade.php ENDPATH**/ ?>