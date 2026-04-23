
<?php $__env->startSection('title', 'My Profile'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-4xl mx-auto px-4 py-8">
    
    <div class="bg-gradient-to-r from-green-800 to-blue-700 rounded-2xl p-8 text-white mb-6 flex items-center gap-6">
        <div class="w-20 h-20 rounded-full bg-white/20 flex items-center justify-center text-3xl font-extrabold flex-shrink-0">
            <?php echo e(strtoupper(substr($user->name, 0, 1))); ?>

        </div>
        <div>
            <h1 class="text-2xl font-extrabold"><?php echo e($user->name); ?></h1>
            <p class="text-white/70 text-sm mt-1"><?php echo e($user->email); ?></p>
            <?php if($user->phone): ?>
                <p class="text-white/70 text-sm"><?php echo e($user->phone); ?></p>
            <?php endif; ?>
        </div>
        <div class="ml-auto flex gap-3">
            <a href="<?php echo e(route('frontend.orders')); ?>" class="bg-white/15 hover:bg-white/25 text-white font-bold text-sm px-4 py-2 rounded-lg transition">
                My Orders
            </a>
        </div>
    </div>

    <div class="grid md:grid-cols-2 gap-6">
        
        <div class="bg-white rounded-2xl border p-6">
            <h2 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                <i class="fa-solid fa-user-circle text-blue-600"></i> Personal Information
            </h2>
            <?php if(session('success')): ?>
                <div class="mb-4 p-3 bg-green-50 border border-green-200 rounded-lg text-sm text-green-700"><?php echo e(session('success')); ?></div>
            <?php endif; ?>
            <form method="POST" action="<?php echo e(route('frontend.profile.update')); ?>" class="space-y-4">
                <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Full Name</label>
                    <input type="text" name="name" value="<?php echo e($user->name); ?>" required
                           class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Phone</label>
                    <input type="tel" name="phone" value="<?php echo e($user->phone); ?>"
                           class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Email (read-only)</label>
                    <input type="email" value="<?php echo e($user->email); ?>" disabled
                           class="w-full border border-gray-100 rounded-xl px-4 py-2.5 text-sm bg-gray-50 text-gray-400">
                </div>
                <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2.5 rounded-xl text-sm transition">
                    Update Profile
                </button>
            </form>
        </div>

        
        <div class="bg-white rounded-2xl border p-6">
            <h2 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                <i class="fa-solid fa-lock text-red-500"></i> Change Password
            </h2>
            <form method="POST" action="<?php echo e(route('frontend.profile.password')); ?>" class="space-y-4">
                <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                <?php $__errorArgs = ['current_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="p-3 bg-red-50 border border-red-200 rounded-lg text-sm text-red-600"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Current Password</label>
                    <input type="password" name="current_password" required
                           class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">New Password</label>
                    <input type="password" name="password" required
                           class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Confirm New Password</label>
                    <input type="password" name="password_confirmation" required
                           class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-blue-500">
                </div>
                <button type="submit" class="w-full bg-blue-700 hover:bg-blue-800 text-white font-bold py-2.5 rounded-xl text-sm transition">
                    Update Password
                </button>
            </form>
        </div>
    </div>

    
    <?php if($orders->count()): ?>
    <div class="bg-white rounded-2xl border p-6 mt-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="font-bold text-gray-800 flex items-center gap-2">
                <i class="fa-solid fa-clock-rotate-left text-green-600"></i> Recent Orders
            </h2>
            <a href="<?php echo e(route('frontend.orders')); ?>" class="text-blue-600 text-xs font-semibold hover:underline">View All</a>
        </div>
        <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="flex items-center justify-between py-3 border-b last:border-b-0">
            <div>
                <p class="text-sm font-semibold text-gray-800"><?php echo e($order->order_number ?? '#'.$order->id); ?></p>
                <p class="text-xs text-gray-400"><?php echo e($order->created_at->format('d M Y')); ?> · <?php echo e($order->items->count()); ?> item(s)</p>
            </div>
            <div class="text-right">
                <p class="font-bold text-blue-700 text-sm">₹<?php echo e(number_format($order->total, 2)); ?></p>
                <span class="text-xs font-bold px-2 py-0.5 rounded-full <?php echo e($order->status_badge_class); ?>"><?php echo e(ucfirst($order->status)); ?></span>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\FarmSea-dashboard\resources\views\frontend\profile.blade.php ENDPATH**/ ?>