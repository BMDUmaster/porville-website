
<?php $__env->startSection('title', 'Orders Report'); ?>
<?php $__env->startSection('page_title', 'Orders Report'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl">

    
    <div class="bg-gradient-to-r from-mayview-blue via-mayview-accent to-blue-500 text-white px-6 py-8">
        <p class="opacity-90 mt-2">Category & Sub-category Sales Overview</p>
    </div>

    <div class="px-4 sm:px-6 lg:px-8 py-8 space-y-6">

        
        <form method="GET" class="bg-white p-4 rounded-xl shadow flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="flex items-center gap-2 w-full md:w-auto">
                <label class="text-sm font-medium text-gray-600">Select Date:</label>
                <input type="date" name="date" value="<?php echo e($date); ?>"
                       class="border rounded-lg px-3 py-2 text-sm w-full md:w-auto outline-none focus:border-blue-500">
            </div>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm">Apply</button>
        </form>

        
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
            <div class="bg-gradient-to-r from-green-500 to-emerald-500 text-white p-6 rounded-xl shadow">
                <p class="text-sm opacity-80">Total Sales</p>
                <h3 class="text-3xl font-bold mt-2">₹<?php echo e(number_format($totalSales, 2)); ?></h3>
            </div>
            <div class="bg-gradient-to-r from-blue-500 to-indigo-500 text-white p-6 rounded-xl shadow">
                <p class="text-sm opacity-80">Total Quantity</p>
                <h3 class="text-3xl font-bold mt-2"><?php echo e($totalQty); ?> Kg</h3>
            </div>
            <div class="bg-gradient-to-r from-purple-500 to-pink-500 text-white p-6 rounded-xl shadow">
                <p class="text-sm opacity-80">Categories</p>
                <h3 class="text-3xl font-bold mt-2"><?php echo e(count($byCategory)); ?></h3>
            </div>
        </div>

        
        <?php if(count($byCategory)): ?>
        <div class="bg-white p-6 rounded-xl shadow">
            <h2 class="text-lg font-semibold mb-4">Category-wise Sales</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <?php $__currentLoopData = $byCategory; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="p-4 rounded-xl bg-gradient-to-r from-blue-50 to-indigo-50 hover:shadow-lg hover:scale-[1.02] transition cursor-pointer">
                    <div class="flex justify-between items-center">
                        <h3 class="font-semibold text-gray-700"><?php echo e($cat); ?></h3>
                        <span class="text-sm text-gray-500">₹<?php echo e(number_format($data['amount'], 2)); ?></span>
                    </div>
                    <p class="text-xl font-bold text-indigo-600 mt-2"><?php echo e($data['qty']); ?> Kg</p>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

        
        <div class="bg-white rounded-xl shadow">
            <div class="px-6 py-4 border-b">
                <h2 class="text-lg font-semibold">Sub-category Sales</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-3 text-left">Category</th>
                            <th class="px-4 py-3 text-left">Sub Category</th>
                            <th class="px-4 py-3 text-left">Quantity</th>
                            <th class="px-4 py-3 text-left">Sales</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php $__currentLoopData = $byCategory; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php $__currentLoopData = $data['subs']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sub => $subData): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3"><?php echo e($cat); ?></td>
                                <td class="px-4 py-3"><?php echo e($sub); ?></td>
                                <td class="px-4 py-3"><?php echo e($subData['qty']); ?> Kg</td>
                                <td class="px-4 py-3">₹<?php echo e(number_format($subData['amount'], 2)); ?></td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php else: ?>
        <div class="bg-white rounded-xl p-8 text-center text-gray-400">
            No sales data for <?php echo e(\Carbon\Carbon::parse($date)->format('d M Y')); ?>.
        </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\FarmSea-dashboard\resources\views\dashboard\orders\report.blade.php ENDPATH**/ ?>