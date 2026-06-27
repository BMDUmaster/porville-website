<?php $__env->startSection('title', 'Forgot Password'); ?>

<?php $__env->startSection('content'); ?>
<main class="flex flex-1 items-center justify-center bg-slate-50 px-4 py-12">
    <div class="w-full max-w-md overflow-hidden rounded-3xl bg-white shadow-lg">
        <div class="px-8 py-8">
            <a href="<?php echo e(route('frontend.login')); ?>" class="mb-6 inline-flex items-center gap-2 text-sm font-bold text-slate-500 transition hover:text-blue-700">
                <i class="fa-solid fa-arrow-left text-xs"></i>
                Back to login
            </a>

            <div class="mb-6">
                <h1 class="nunito text-2xl font-extrabold text-gray-800">
                    <?php echo e($step === 'reset' ? 'CREATE NEW PASSWORD' : ($step === 'otp' ? 'VERIFY OTP' : 'FORGOT PASSWORD')); ?>

                </h1>
                <p class="mt-1 text-sm text-gray-400">
                    <?php echo e($step === 'reset' ? 'Set a fresh password for your FarmSea account.' : ($step === 'otp' ? 'Enter the OTP sent for your email.' : 'Enter your email address to receive an OTP.')); ?>

                </p>
            </div>

            <div class="mb-6 grid grid-cols-3 gap-2">
                <?php $__currentLoopData = ['email' => 'Email', 'otp' => 'OTP', 'reset' => 'Reset']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="h-2 rounded-full <?php echo e($step === $key || ($step === 'otp' && $key === 'email') || ($step === 'reset' && in_array($key, ['email', 'otp'], true)) ? 'bg-blue-700' : 'bg-slate-200'); ?>"></div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <?php if($errors->any()): ?>
                <div class="mb-4 rounded-xl border border-red-200 bg-red-50 p-3 text-sm font-medium text-red-600">
                    <?php echo e($errors->first()); ?>

                </div>
            <?php endif; ?>

            <?php if(session('success')): ?>
                <div class="mb-4 rounded-xl border border-green-200 bg-green-50 p-3 text-sm font-medium text-green-700">
                    <?php echo e(session('success')); ?>

                </div>
            <?php endif; ?>

            <?php if($step === 'email'): ?>
                <form method="POST" action="<?php echo e(route('frontend.password.otp')); ?>" class="space-y-4">
                    <?php echo csrf_field(); ?>
                    <div>
                        <label class="mb-1 block text-sm font-semibold text-gray-700">Email Address</label>
                        <input type="email" name="email" value="<?php echo e(old('email')); ?>" required
                               placeholder="Enter your registered email"
                               class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:bg-white">
                    </div>

                    <button type="submit" class="w-full rounded-xl bg-blue-700 py-3 text-sm font-bold text-white transition hover:bg-blue-800">
                        <i class="fa-solid fa-paper-plane mr-2"></i> Send OTP
                    </button>
                </form>
            <?php elseif($step === 'otp'): ?>
                <form method="POST" action="<?php echo e(route('frontend.password.verify')); ?>" class="space-y-4">
                    <?php echo csrf_field(); ?>
                    <div class="rounded-2xl border border-blue-100 bg-blue-50 px-4 py-3 text-sm font-semibold text-blue-800">
                        <?php echo e(session('password_reset_otp_email')); ?>

                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-semibold text-gray-700">Enter OTP</label>
                        <input type="text" name="email_otp" value="<?php echo e(old('email_otp')); ?>" inputmode="numeric" maxlength="4" required
                               placeholder="4 digit OTP"
                               class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-center text-lg font-extrabold tracking-[0.45em] outline-none transition focus:border-blue-500 focus:bg-white">
                    </div>

                    <button type="submit" class="w-full rounded-xl bg-blue-700 py-3 text-sm font-bold text-white transition hover:bg-blue-800">
                        <i class="fa-solid fa-check mr-2"></i> Submit OTP
                    </button>
                </form>
            <?php else: ?>
                <form method="POST" action="<?php echo e(route('frontend.password.reset')); ?>" class="space-y-4">
                    <?php echo csrf_field(); ?>
                    <div>
                        <label class="mb-1 block text-sm font-semibold text-gray-700">Create New Password</label>
                        <input type="password" name="password" required minlength="8"
                               placeholder="Enter new password"
                               class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:bg-white">
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-semibold text-gray-700">Confirm Password</label>
                        <input type="password" name="password_confirmation" required minlength="8"
                               placeholder="Confirm new password"
                               class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:bg-white">
                    </div>

                    <button type="submit" class="w-full rounded-xl bg-green-600 py-3 text-sm font-bold text-white transition hover:bg-green-700">
                        <i class="fa-solid fa-lock mr-2"></i> Create Password
                    </button>
                </form>
            <?php endif; ?>
        </div>
    </div>
</main>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\BMDU Work\FarmSea-dashboard\resources\views\frontend\forgot-password.blade.php ENDPATH**/ ?>