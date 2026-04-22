
<?php $__env->startSection('title', 'Dashboard'); ?>
<?php $__env->startSection('page_title', 'Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<div class="p-5 sm:p-6 lg:p-10 fade-in">

    
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 lg:gap-6 mb-10">

        <div class="bg-white rounded-xl border border-gray-200 p-6 hover:-translate-y-1 hover:shadow-lg transition-all duration-300">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm text-gray-500 font-medium">Today's Sales</p>
                    <p class="text-2xl lg:text-3xl font-semibold text-mayview-blue mt-2">₹<?php echo e(number_format($stats['today_sales'], 2)); ?></p>
                </div>
                <div class="bg-blue-100 text-mayview-blue p-3 rounded-xl">
                    <i class="fa-solid fa-indian-rupee-sign text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-6 hover:-translate-y-1 hover:shadow-lg transition-all duration-300">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm text-gray-500 font-medium">Pending Orders</p>
                    <p class="text-2xl lg:text-3xl font-semibold text-admin-orange mt-2"><?php echo e($stats['pending_orders']); ?></p>
                </div>
                <div class="bg-orange-100 text-admin-orange p-3 rounded-xl">
                    <i class="fa-solid fa-clock-rotate-left text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-6 hover:-translate-y-1 hover:shadow-lg transition-all duration-300">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm text-gray-500 font-medium">Total Products</p>
                    <p class="text-2xl lg:text-3xl font-semibold text-emerald-600 mt-2"><?php echo e($stats['total_products']); ?></p>
                </div>
                <div class="bg-emerald-100 text-emerald-600 p-3 rounded-xl">
                    <i class="fa-solid fa-box text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-6 hover:-translate-y-1 hover:shadow-lg transition-all duration-300">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm text-gray-500 font-medium">Low Stock Alert</p>
                    <p class="text-2xl lg:text-3xl font-semibold text-rose-600 mt-2"><?php echo e($stats['low_stock']); ?></p>
                </div>
                <div class="bg-rose-100 text-rose-600 p-3 rounded-xl">
                    <i class="fa-solid fa-triangle-exclamation text-xl"></i>
                </div>
            </div>
            <div class="mt-3 text-xs text-gray-500">Restock recommended</div>
        </div>
    </div>

    
    <div class="grid lg:grid-cols-2 gap-6 lg:gap-8 mb-10">
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
            <h3 class="text-base font-semibold text-gray-800 mb-6">Orders by Status</h3>
            <div class="h-64">
                <canvas id="ordersChart"></canvas>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
            <h3 class="text-base font-semibold text-gray-800 mb-6">Quick Actions</h3>
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                <a href="<?php echo e(route('dashboard.products')); ?>"
                   class="flex flex-col items-center p-5 border border-gray-200 rounded-xl hover:border-mayview-blue hover:bg-blue-50 transition group">
                    <i class="fa-solid fa-plus text-2xl text-mayview-blue mb-2 group-hover:scale-110 transition-transform"></i>
                    <span class="text-xs font-medium text-gray-700">Add Product</span>
                </a>
                <a href="<?php echo e(route('dashboard.orders')); ?>"
                   class="flex flex-col items-center p-5 border border-gray-200 rounded-xl hover:border-admin-orange hover:bg-orange-50 transition group">
                    <i class="fa-solid fa-truck-fast text-2xl text-admin-orange mb-2 group-hover:scale-110 transition-transform"></i>
                    <span class="text-xs font-medium text-gray-700">View Orders</span>
                </a>
                <a href="<?php echo e(route('dashboard.coupons')); ?>"
                   class="flex flex-col items-center p-5 border border-gray-200 rounded-xl hover:border-emerald-600 hover:bg-emerald-50 transition group">
                    <i class="fa-solid fa-tags text-2xl text-emerald-600 mb-2 group-hover:scale-110 transition-transform"></i>
                    <span class="text-xs font-medium text-gray-700">Coupons</span>
                </a>
                <a href="<?php echo e(route('dashboard.orders.report')); ?>"
                   class="flex flex-col items-center p-5 border border-gray-200 rounded-xl hover:border-purple-600 hover:bg-purple-50 transition group">
                    <i class="fa-solid fa-chart-simple text-2xl text-purple-600 mb-2 group-hover:scale-110 transition-transform"></i>
                    <span class="text-xs font-medium text-gray-700">Reports</span>
                </a>
                <a href="<?php echo e(route('dashboard.notifications')); ?>"
                   class="flex flex-col items-center p-5 border border-gray-200 rounded-xl hover:border-amber-500 hover:bg-amber-50 transition group col-span-2 sm:col-span-1">
                    <i class="fa-regular fa-bell text-2xl text-amber-500 mb-2 group-hover:scale-110 transition-transform"></i>
                    <span class="text-xs font-medium text-gray-700">Notifications</span>
                </a>
            </div>
        </div>
    </div>

    
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mb-10">
        <div class="px-6 py-4 border-b bg-gray-50 flex justify-between items-center">
            <h3 class="text-base font-semibold text-gray-800">Recent Orders</h3>
            <a href="<?php echo e(route('dashboard.orders')); ?>" class="text-mayview-blue hover:underline text-sm font-medium">View All</a>
        </div>
        <div class="divide-y divide-gray-100">
            <?php $__empty_1 = true; $__currentLoopData = $recent_orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="px-6 py-4 hover:bg-gray-50 transition">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="font-medium text-sm">#ORD-<?php echo e(str_pad($order->id, 4, '0', STR_PAD_LEFT)); ?></p>
                        <p class="text-xs text-gray-500 mt-0.5"><?php echo e($order->user->name ?? 'Guest'); ?></p>
                    </div>
                    <div class="text-right">
                        <p class="font-semibold text-sm text-emerald-600">₹<?php echo e(number_format($order->total, 2)); ?></p>
                        <span class="text-xs px-2.5 py-0.5 rounded-full mt-1 inline-block font-medium <?php echo e($order->status_badge_class); ?>">
                            <?php echo e(ucfirst($order->status)); ?>

                        </span>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="px-6 py-8 text-center text-gray-400 text-sm">No orders yet.</div>
            <?php endif; ?>
        </div>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
const ordersCtx = document.getElementById('ordersChart').getContext('2d');
new Chart(ordersCtx, {
    type: 'doughnut',
    data: {
        labels: ['Pending', 'Processing', 'Shipped', 'Delivered', 'Cancelled'],
        datasets: [{
            data: [
                <?php echo e($orders_by_status['pending']); ?>,
                <?php echo e($orders_by_status['processing']); ?>,
                <?php echo e($orders_by_status['shipped']); ?>,
                <?php echo e($orders_by_status['delivered']); ?>,
                <?php echo e($orders_by_status['cancelled']); ?>

            ],
            backgroundColor: ['#f97316','#eab308','#3b82f6','#10b981','#ef4444'],
            borderWidth: 2, borderColor: '#fff'
        }]
    },
    options: {
        responsive: true, maintainAspectRatio: false, cutout: '65%',
        plugins: { legend: { position: 'bottom', labels: { padding: 16, font: { size: 11 } } } }
    }
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\FarmSea-dashboard\resources\views/dashboard/index.blade.php ENDPATH**/ ?>