<?php $__env->startSection('title', 'Sign In'); ?>

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
            <div class="absolute inset-0 bg-[linear-gradient(180deg,rgba(15,23,42,0.08),rgba(15,23,42,0.86))]"></div>

            <div class="absolute inset-x-0 bottom-0 p-8 text-white md:p-10">
                <span class="inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-4 py-2 text-[10px] font-extrabold uppercase tracking-[0.18em] backdrop-blur">
                    <i class="fa-solid fa-leaf text-green-300"></i> Freshness you can trust
                </span>
                <h2 class="mt-5 max-w-md text-3xl font-extrabold leading-tight md:text-[40px]">
                    Fresh from FarmSea,
                    <span class="block text-green-300">delivered to your door.</span>
                </h2>
                <p class="mt-4 max-w-md text-[13px] leading-6 text-white/75">
                    Sign in to reorder favourites, track deliveries, save addresses, and enjoy a faster checkout.
                </p>
                <div class="mt-6 flex flex-wrap gap-3 text-[10px] font-bold uppercase tracking-[0.12em] text-white/90">
                    <span class="rounded-full border border-white/15 bg-black/15 px-3 py-2"><i class="fa-solid fa-shield-halved mr-2 text-green-300"></i>Secure account</span>
                    <span class="rounded-full border border-white/15 bg-black/15 px-3 py-2"><i class="fa-solid fa-truck-fast mr-2 text-green-300"></i>Fast delivery</span>
                </div>
            </div>
        </div>

        <div class="flex items-center px-6 py-9 sm:px-10 md:py-12 lg:px-12">
            <div class="mx-auto w-full max-w-[390px]">
                <a href="<?php echo e(route('frontend.home')); ?>" class="mb-8 inline-flex items-center">
                    <img src="<?php echo e($brandLogoUrl); ?>" alt="FarmSea" class="h-14 w-auto object-contain">
                </a>

                <div class="flex items-center gap-2 text-[10px] font-extrabold uppercase tracking-[0.14em]">
                    <span class="rounded-full bg-blue-50 px-3 py-1.5 text-blue-700">1. Email</span>
                    <span id="stepTwo" class="rounded-full bg-slate-100 px-3 py-1.5 text-slate-400">2. Continue</span>
                </div>
                <h1 id="authTitle" class="mt-4 text-3xl font-extrabold tracking-tight text-slate-950">Enter your email</h1>
                <p id="authDescription" class="mt-2 text-[13px] leading-6 text-slate-500">We'll check whether you need to sign in or create an account.</p>

                <?php if($errors->any()): ?>
                    <div class="mt-5 flex gap-3 rounded-xl border border-red-100 bg-red-50 p-3.5 text-[12px] leading-5 text-red-700">
                        <i class="fa-solid fa-circle-exclamation mt-0.5"></i>
                        <span><?php echo e($errors->first()); ?></span>
                    </div>
                <?php endif; ?>

                <?php if(session('success')): ?>
                    <div class="mt-5 flex gap-3 rounded-xl border border-green-100 bg-green-50 p-3.5 text-[12px] leading-5 text-green-700">
                        <i class="fa-solid fa-circle-check mt-0.5"></i>
                        <span><?php echo e(session('success')); ?></span>
                    </div>
                <?php endif; ?>

                <form method="POST" action="<?php echo e(route('frontend.login.post')); ?>" id="authForm" class="mt-7 space-y-5">
                    <?php echo csrf_field(); ?>
                    <div>
                        <label for="login-email" class="mb-2 block text-[12px] font-extrabold text-slate-700">Email Address</label>
                        <div class="relative">
                            <i class="fa-regular fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-sm text-slate-400"></i>
                            <input id="login-email" type="email" name="email" value="<?php echo e(old('email')); ?>" required autocomplete="email"
                                   placeholder="you@example.com"
                                   class="w-full rounded-xl border border-slate-200 bg-slate-50 py-3.5 pl-11 pr-4 text-[13px] text-slate-900 outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-50">
                        </div>
                    </div>

                    <div id="passwordStep" class="hidden">
                        <label for="login-password" class="mb-2 block text-[12px] font-extrabold text-slate-700">Password</label>
                        <div class="relative">
                            <i class="fa-solid fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-sm text-slate-400"></i>
                            <input id="login-password" type="password" name="password" autocomplete="current-password"
                                   placeholder="Enter your password"
                                   class="w-full rounded-xl border border-slate-200 bg-slate-50 py-3.5 pl-11 pr-4 text-[13px] text-slate-900 outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-50">
                        </div>
                    </div>

                    <div id="loginExtras" class="hidden flex items-center justify-between gap-4">
                        <label class="flex cursor-pointer items-center gap-2.5 text-[12px] font-medium text-slate-600">
                            <input type="checkbox" name="remember" class="h-4 w-4 rounded border-slate-300 accent-blue-600">
                            Remember Me
                        </label>
                        <a href="<?php echo e(route('frontend.password.forgot')); ?>" class="text-[12px] font-extrabold text-blue-700 transition hover:text-blue-900 hover:underline">Forgot password?</a>
                    </div>

                    <p id="emailError" class="hidden text-[12px] font-semibold text-red-600"></p>
                    <button type="button" id="continueButton" class="flex w-full items-center justify-center gap-2 rounded-xl bg-blue-600 py-3.5 text-[12px] font-extrabold uppercase tracking-[0.12em] text-white shadow-[0_12px_25px_rgba(37,99,235,0.22)] transition hover:-translate-y-0.5 hover:bg-blue-700">
                        Continue <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </button>
                    <button type="submit" id="signInButton" class="hidden w-full items-center justify-center gap-2 rounded-xl bg-blue-600 py-3.5 text-[12px] font-extrabold uppercase tracking-[0.12em] text-white shadow-[0_12px_25px_rgba(37,99,235,0.22)] transition hover:-translate-y-0.5 hover:bg-blue-700 hover:shadow-[0_16px_30px_rgba(37,99,235,0.28)]">
                        Sign In <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </button>

                    <button type="button" id="changeEmail" class="hidden w-full text-center text-[12px] font-extrabold text-slate-500 hover:text-blue-700">Use a different email</button>
                </form>
            </div>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
const authEmail = document.getElementById('login-email');
const continueButton = document.getElementById('continueButton');
const passwordStep = document.getElementById('passwordStep');
const signInButton = document.getElementById('signInButton');
const changeEmail = document.getElementById('changeEmail');
const emailError = document.getElementById('emailError');

function resetEmailStep() {
    passwordStep.classList.add('hidden'); document.getElementById('loginExtras').classList.add('hidden'); signInButton.classList.add('hidden'); changeEmail.classList.add('hidden'); continueButton.classList.remove('hidden');
    authEmail.readOnly = false; document.getElementById('login-password').required = false; emailError.classList.add('hidden');
    document.getElementById('authTitle').textContent = 'Enter your email'; document.getElementById('authDescription').textContent = "We'll check whether you need to sign in or create an account.";
    document.getElementById('stepTwo').className = 'rounded-full bg-slate-100 px-3 py-1.5 text-slate-400';
}

continueButton.addEventListener('click', async () => {
    if (!authEmail.reportValidity()) return;
    continueButton.disabled = true; continueButton.textContent = 'Checking...'; emailError.classList.add('hidden');
    try {
        const response = await fetch('<?php echo e(route('frontend.email.check')); ?>', {method: 'POST', headers: {'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'}, body: JSON.stringify({email: authEmail.value.trim()})});
        const data = await response.json();
        if (!response.ok) throw new Error(data.message || 'Please enter a valid email address.');
        if (!data.exists) { window.location.href = '<?php echo e(route('frontend.register')); ?>?email=' + encodeURIComponent(data.email); return; }
        authEmail.value = data.email; authEmail.readOnly = true; passwordStep.classList.remove('hidden'); document.getElementById('loginExtras').classList.remove('hidden'); signInButton.classList.remove('hidden'); changeEmail.classList.remove('hidden'); continueButton.classList.add('hidden'); document.getElementById('login-password').required = true;
        document.getElementById('authTitle').textContent = 'Welcome back'; document.getElementById('authDescription').textContent = 'Enter your password to sign in.'; document.getElementById('stepTwo').className = 'rounded-full bg-blue-50 px-3 py-1.5 text-blue-700'; document.getElementById('login-password').focus();
    } catch (error) { emailError.textContent = error.message; emailError.classList.remove('hidden'); }
    finally { continueButton.disabled = false; continueButton.innerHTML = 'Continue <i class="fa-solid fa-arrow-right text-[10px]"></i>'; }
});
authEmail.addEventListener('input', resetEmailStep); changeEmail.addEventListener('click', () => { resetEmailStep(); authEmail.focus(); });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\BMDU Work\FarmSea-dashboard\resources\views/frontend/login.blade.php ENDPATH**/ ?>