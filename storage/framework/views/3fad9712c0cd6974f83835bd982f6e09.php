
<?php $__env->startSection('title', 'Profile'); ?>
<?php $__env->startSection('page_title', 'Profile Settings'); ?>

<?php $__env->startSection('content'); ?>
<div class="p-4 lg:p-6 max-w-3xl">

    
    <div class="bg-gradient-to-r from-mayview-blue to-mayview-accent text-white p-6 rounded-2xl mb-6 flex items-center gap-4">
        <div class="relative">
            <div class="w-20 h-20 rounded-full bg-white/20 flex items-center justify-center overflow-hidden text-3xl font-bold">
                <?php if($user->photo ?? false): ?>
                    <img src="<?php echo e(asset('storage/'.$user->photo)); ?>" class="w-full h-full object-cover">
                <?php else: ?>
                    <?php echo e(strtoupper(substr($user->name, 0, 1))); ?>

                <?php endif; ?>
            </div>
        </div>
        <div>
            <h1 class="text-2xl font-bold"><?php echo e($user->name); ?></h1>
            <p class="text-sm opacity-90 capitalize"><?php echo e($user->role); ?></p>
            <p class="text-sm opacity-75"><?php echo e($user->email); ?></p>
        </div>
    </div>

    
    <div class="bg-white p-5 rounded-2xl shadow mb-6">
        <h2 class="font-semibold text-lg mb-4">Update Profile</h2>
        <form method="POST" action="<?php echo e(route('dashboard.profile.update')); ?>" enctype="multipart/form-data" class="space-y-4">
            <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
            <div>
                <label class="block text-sm font-medium mb-1">Full Name</label>
                <input type="text" name="name" value="<?php echo e($user->name); ?>" required
                       class="w-full border px-4 py-2.5 rounded-lg text-sm outline-none focus:border-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Phone</label>
                <input type="text" name="phone" value="<?php echo e($user->phone ?? ''); ?>"
                       class="w-full border px-4 py-2.5 rounded-lg text-sm outline-none focus:border-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Profile Photo</label>
                <input type="file" name="photo" accept="image/*"
                       class="w-full border px-4 py-2 rounded-lg text-sm">
            </div>
            <button type="submit" class="bg-blue-600 text-white px-6 py-2.5 rounded-lg font-bold text-sm hover:bg-blue-700">
                Update Profile
            </button>
        </form>
    </div>

    
    <div class="bg-white p-5 rounded-2xl shadow">
        <h2 class="font-semibold text-lg mb-4">Change Password</h2>
        <form method="POST" action="<?php echo e(route('dashboard.profile.password')); ?>" class="space-y-4">
            <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
            <div>
                <label class="block text-sm font-medium mb-1">Current Password</label>
                <input type="password" name="current_password" required
                       class="w-full border px-4 py-2.5 rounded-lg text-sm outline-none focus:border-blue-500">
                <?php $__errorArgs = ['current_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">New Password</label>
                <input type="password" name="password" required minlength="8"
                       class="w-full border px-4 py-2.5 rounded-lg text-sm outline-none focus:border-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Confirm New Password</label>
                <input type="password" name="password_confirmation" required
                       class="w-full border px-4 py-2.5 rounded-lg text-sm outline-none focus:border-blue-500">
            </div>
            <button type="submit" class="bg-red-500 text-white px-6 py-2.5 rounded-lg font-bold text-sm hover:bg-red-600">
                Update Password
            </button>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\FarmSea-dashboard\resources\views\dashboard\profile\index.blade.php ENDPATH**/ ?>