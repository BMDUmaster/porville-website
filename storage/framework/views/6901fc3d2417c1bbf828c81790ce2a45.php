<?php $__env->startSection('title', 'Forgot Password'); ?>

<?php $__env->startSection('content'); ?>
<section class="relative overflow-hidden bg-[linear-gradient(135deg,#eef6ff_0%,#f7fbf5_52%,#ffffff_100%)] px-4 py-10 md:px-6 md:py-14">
    <div class="pointer-events-none absolute -left-24 top-16 h-72 w-72 rounded-full bg-blue-200/25 blur-3xl"></div>
    <div class="pointer-events-none absolute -right-20 bottom-0 h-72 w-72 rounded-full bg-green-200/30 blur-3xl"></div>

    <div class="relative mx-auto grid w-full max-w-5xl overflow-hidden rounded-[30px] border border-white/80 bg-white shadow-[0_28px_90px_rgba(15,23,42,0.13)] lg:min-h-[560px] lg:grid-cols-[1.05fr_0.95fr]">
        <div class="relative hidden min-h-[560px] overflow-hidden lg:block">
            <img
                src="<?php echo e(asset('storage/products/HiVF3oTdVV5ivcPECY0aMpXFljIdua0hBYBlCAUW.webp')); ?>"
                alt="Fresh FarmSea food prepared for serving"
                class="absolute inset-0 h-full w-full object-cover"
            >
            <div class="absolute inset-0 bg-[linear-gradient(180deg,rgba(15,23,42,0.08),rgba(15,23,42,0.88))]"></div>

            <div class="absolute inset-x-0 bottom-0 p-8 text-white md:p-10">
                <span class="inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-4 py-2 text-[10px] font-extrabold uppercase tracking-[0.18em] backdrop-blur">
                    <i class="fa-solid fa-shield-halved text-green-300"></i> Secure account recovery
                </span>
                <h2 class="mt-5 max-w-md text-3xl font-extrabold leading-tight md:text-[39px]">
                    Get back to your
                    <span class="block text-green-300">fresh favourites.</span>
                </h2>
                <p class="mt-4 max-w-md text-[13px] leading-6 text-white/75">
                    Verify your email securely and create a new password in three quick steps.
                </p>
                <div class="mt-6 grid grid-cols-3 gap-2">
                    <?php $__currentLoopData = ['email' => 'Email', 'otp' => 'Verify', 'reset' => 'Reset']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="rounded-xl border px-3 py-3 text-center <?php echo e($step === $key ? 'border-green-300/60 bg-green-400/20 text-white' : 'border-white/15 bg-black/15 text-white/65'); ?>">
                            <i class="fa-solid <?php echo e($key === 'email' ? 'fa-envelope' : ($key === 'otp' ? 'fa-key' : 'fa-lock')); ?> block text-sm <?php echo e($step === $key ? 'text-green-300' : ''); ?>"></i>
                            <span class="mt-2 block text-[9px] font-extrabold uppercase tracking-[0.12em]"><?php echo e($label); ?></span>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>

        <div class="flex items-center px-6 py-9 sm:px-10 md:py-12 lg:px-12">
        <div class="mx-auto w-full max-w-[390px]">
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
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\BMDU Work\FarmSea-dashboard\resources\views\frontend\forgot-password.blade.php ENDPATH**/ ?>