
<?php $__env->startSection('title', 'Sign In'); ?>

<?php $__env->startSection('content'); ?>
<main class="flex-1 flex items-center justify-center px-4 py-12">
    <div class="w-full max-w-md bg-white rounded-3xl shadow-lg overflow-hidden">
        <div class="px-8 py-8">
            <h1 class="nunito font-extrabold text-2xl text-gray-800 mb-1">LOGIN</h1>
            <p class="text-sm text-gray-400 mb-6">Sign in to your FarmSea account</p>

            <?php if($errors->any()): ?>
                <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg text-sm text-red-600">
                    <?php echo e($errors->first()); ?>

                </div>
            <?php endif; ?>

            <?php if(session('success')): ?>
                <div class="mb-4 p-3 bg-green-50 border border-green-200 rounded-lg text-sm text-green-700">
                    <?php echo e(session('success')); ?>

                </div>
            <?php endif; ?>

            <form method="POST" action="<?php echo e(route('frontend.login.post')); ?>" class="space-y-4">
                <?php echo csrf_field(); ?>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Email Address</label>
                    <input type="email" name="email" value="<?php echo e(old('email')); ?>" required
                           placeholder="Enter your email"
                           class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm outline-none focus:border-blue-500 bg-gray-50">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Password</label>
                    <input type="password" name="password" required
                           placeholder="Enter your password"
                           class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm outline-none focus:border-blue-500 bg-gray-50">
                </div>
                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remember" class="accent-blue-600">
                        <span class="text-sm text-gray-600">Remember me</span>
                    </label>
                    <a href="<?php echo e(route('frontend.password.forgot')); ?>" class="text-sm font-semibold text-blue-700 transition hover:text-blue-800 hover:underline">
                        Forgot password?
                    </a>
                </div>
                <button type="submit"
                        class="w-full bg-blue-700 hover:bg-blue-800 text-white font-bold py-3 rounded-xl text-sm transition">
                    <i class="fa-solid fa-right-to-bracket mr-2"></i> Log In
                </button>
                <p class="text-sm text-gray-500 text-center">
                    Don't have an account?
                    <a href="<?php echo e(route('frontend.register')); ?>" class="text-green-600 font-semibold hover:underline">Register Now</a>
                </p>
            </form>
        </div>
    </div>
</main>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\BMDU Work\FarmSea-dashboard\resources\views\frontend\login.blade.php ENDPATH**/ ?>