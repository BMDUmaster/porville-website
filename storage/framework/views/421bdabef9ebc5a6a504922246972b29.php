<?php $__env->startSection('title', 'Create Account'); ?>

<?php $__env->startSection('styles'); ?>
<style>
.field-label { display:block; font-size:13px; font-weight:600; color:#1f2937; margin-bottom:6px; }
.field-label .req { color:#ef4444; margin-left:2px; }
.field-input {
    width:100%; padding:12px 14px 12px 42px;
    border:1.5px solid #e5e7eb; border-radius:10px;
    font-size:13px; outline:none; background:#f9fafb;
    color:#1f2937; transition:border-color .2s, box-shadow .2s;
    font-family:'Poppins',sans-serif;
}
.field-input:focus { border-color:#2B5BA8; box-shadow:0 0 0 3px rgba(43,91,168,.1); background:#fff; }
.field-input.error { border-color:#ef4444; background:#fef2f2; }
.field-icon { position:absolute; left:14px; top:50%; transform:translateY(-50%); color:#9ca3af; font-size:14px; pointer-events:none; }
.eye-btn { position:absolute; right:12px; top:50%; transform:translateY(-50%); background:none; border:none; cursor:pointer; color:#9ca3af; font-size:14px; padding:4px; }
.eye-btn:hover { color:#2B5BA8; }
.error-msg { margin-top:5px; font-size:11px; color:#ef4444; display:none; align-items:center; gap:4px; }
.error-msg.show { display:flex; }
.str-bar { height:3px; border-radius:3px; flex:1; background:#e5e7eb; transition:background .25s; }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<section class="relative overflow-hidden bg-[linear-gradient(135deg,#eef6ff_0%,#f7fbf5_52%,#ffffff_100%)] px-4 py-10 md:px-6 md:py-14">
<div class="pointer-events-none absolute -left-24 top-16 h-72 w-72 rounded-full bg-blue-200/25 blur-3xl"></div>
<div class="pointer-events-none absolute -right-20 bottom-0 h-72 w-72 rounded-full bg-green-200/30 blur-3xl"></div>

<div class="relative mx-auto grid w-full max-w-5xl overflow-hidden rounded-[30px] border border-white/80 bg-white shadow-[0_28px_90px_rgba(15,23,42,0.13)] lg:min-h-[650px] lg:grid-cols-[1.05fr_0.95fr]">
    <div class="relative hidden min-h-[650px] overflow-hidden lg:block">
        <img
            src="<?php echo e(asset('storage/products/HiVF3oTdVV5ivcPECY0aMpXFljIdua0hBYBlCAUW.webp')); ?>"
            alt="Fresh FarmSea food prepared for serving"
            class="absolute inset-0 h-full w-full object-cover"
        >
        <div class="absolute inset-0 bg-[linear-gradient(180deg,rgba(15,23,42,0.08),rgba(15,23,42,0.88))]"></div>

        <div class="absolute inset-x-0 bottom-0 p-8 text-white md:p-10">
            <span class="inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-4 py-2 text-[10px] font-extrabold uppercase tracking-[0.18em] backdrop-blur">
                <i class="fa-solid fa-leaf text-green-300"></i> Join the FarmSea family
            </span>
            <h2 class="mt-5 max-w-md text-3xl font-extrabold leading-tight md:text-[40px]">
                Fresh choices start
                <span class="block text-green-300">with your account.</span>
            </h2>
            <p class="mt-4 max-w-md text-[13px] leading-6 text-white/75">
                Create your account to enjoy quicker checkout, easy order tracking, and fresh favourites delivered to your door.
            </p>
            <div class="mt-6 flex flex-wrap gap-3 text-[10px] font-bold uppercase tracking-[0.12em] text-white/90">
                <span class="rounded-full border border-white/15 bg-black/15 px-3 py-2"><i class="fa-solid fa-shield-halved mr-2 text-green-300"></i>Secure signup</span>
                <span class="rounded-full border border-white/15 bg-black/15 px-3 py-2"><i class="fa-solid fa-truck-fast mr-2 text-green-300"></i>Fast delivery</span>
            </div>
        </div>
    </div>

    <div class="flex items-center px-6 py-9 sm:px-10 md:py-12 lg:px-12">
    <div class="mx-auto w-full max-w-[420px]">

    <?php if($errors->any()): ?>
    <div data-auto-dismiss="3000" class="mb-5 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-600 transition-all duration-500">
        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <p class="flex items-center gap-2"><i class="fa-solid fa-circle-exclamation text-xs"></i><?php echo e($error); ?></p>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
    <?php endif; ?>

        <div class="mb-7">
            <span class="inline-flex rounded-full bg-blue-50 px-3 py-1.5 text-[10px] font-extrabold uppercase tracking-[0.18em] text-blue-700">Fresh start</span>
            <h1 class="mt-4 text-3xl font-extrabold tracking-tight text-slate-950">Create your account</h1>
            <p class="mt-2 text-[13px] leading-6 text-slate-500">Phone and address details will be collected at checkout.</p>
        </div>

            <form method="POST" action="<?php echo e(route('frontend.register.post')); ?>" id="signupForm" novalidate>
                <?php echo csrf_field(); ?>

                <div class="mb-5">
                    <label class="field-label">Full Name <span class="req">*</span></label>
                    <div class="relative">
                        <i class="fa-regular fa-user field-icon"></i>
                        <input type="text" name="name" id="name" value="<?php echo e(old('name')); ?>"
                               placeholder="John Doe" class="field-input" required>
                    </div>
                    <div class="error-msg" id="nameErr"><i class="fa-solid fa-circle-exclamation text-xs"></i><span>Full name is required</span></div>
                </div>

                <div class="mb-5">
                    <label class="field-label">Email Address <span class="req">*</span></label>
                    <div class="relative">
                        <i class="fa-regular fa-envelope field-icon"></i>
                        <input type="email" name="email" id="email" value="<?php echo e(old('email')); ?>"
                               placeholder="john@example.com" class="field-input" required>
                    </div>
                    <div class="error-msg" id="emailErr"><i class="fa-solid fa-circle-exclamation text-xs"></i><span>Enter a valid email address</span></div>
                </div>

                <div class="mb-5">
                    <label class="field-label">Email OTP <span class="req">*</span></label>
                    <div class="flex flex-col gap-3 sm:flex-row">
                        <div class="relative flex-1">
                            <i class="fa-solid fa-shield-halved field-icon"></i>
                            <input type="text" name="email_otp" id="emailOtp" value="<?php echo e(old('email_otp')); ?>"
                                   inputmode="numeric" maxlength="4" placeholder="Enter 4 digit OTP"
                                   class="field-input" required>
                        </div>
                        <button type="button" id="sendOtpBtn" onclick="sendRegisterOtp()"
                                class="rounded-xl bg-green-600 px-5 py-3 text-sm font-bold text-white transition hover:bg-green-700 sm:w-36">
                            Send OTP
                        </button>
                    </div>
                    <p id="otpStatus" class="mt-2 hidden text-xs font-semibold"></p>
                    <div class="error-msg" id="otpErr"><i class="fa-solid fa-circle-exclamation text-xs"></i><span>Enter the 4 digit OTP</span></div>
                </div>

                <div class="mb-6">
                    <label class="field-label">Password <span class="req">*</span></label>
                    <div class="relative">
                        <i class="fa-solid fa-lock field-icon"></i>
                        <input type="password" name="password" id="pw"
                               placeholder="Min. 8 chars" class="field-input pr-10"
                               oninput="checkStrength(this.value)" required>
                        <button type="button" class="eye-btn" onclick="togglePw('pw','ei1')">
                            <i id="ei1" class="fa-regular fa-eye"></i>
                        </button>
                    </div>
                    <div class="mt-2 flex gap-1">
                        <div class="str-bar" id="sb1"></div>
                        <div class="str-bar" id="sb2"></div>
                        <div class="str-bar" id="sb3"></div>
                        <div class="str-bar" id="sb4"></div>
                    </div>
                    <div class="error-msg" id="pwErr"><i class="fa-solid fa-circle-exclamation text-xs"></i><span>Min. 8 characters required</span></div>
                </div>

                <button type="submit" onclick="return validateForm()"
                        class="mb-4 flex items-center justify-center gap-2 rounded-xl bg-[#2B5BA8] px-8 py-3.5 text-sm font-bold text-white shadow-sm transition-all hover:bg-[#1e4080] hover:shadow-lg hover:shadow-blue-200/50 active:scale-[.99]">
                    <i class="fa-solid fa-user-plus text-sm"></i>
                    Create Account
                </button>

                <p class="text-sm text-gray-500">
                    Already have an account?
                    <a href="<?php echo e(route('frontend.login')); ?>" class="font-bold text-green-600 hover:underline">Sign in here</a>
                </p>
            </form>
    </div>
    </div>
</div>
</section>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
function togglePw(inputId, iconId) {
    const input = document.getElementById(inputId);
    const icon = document.getElementById(iconId);

    if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'fa-regular fa-eye-slash';
    } else {
        input.type = 'password';
        icon.className = 'fa-regular fa-eye';
    }
}

function checkStrength(value) {
    const bars = [1, 2, 3, 4].map((index) => document.getElementById('sb' + index));
    const colors = ['#ef4444', '#f97316', '#eab308', '#16a34a'];
    let score = 0;

    if (value.length >= 8) score++;
    if (/[A-Z]/.test(value)) score++;
    if (/[0-9]/.test(value)) score++;
    if (/[^A-Za-z0-9]/.test(value)) score++;

    bars.forEach((bar, index) => {
        bar.style.background = index < score ? colors[score - 1] : '#e5e7eb';
    });
}

function showErr(id, show) {
    const el = document.getElementById(id);
    if (el) {
        el.classList.toggle('show', show);
    }
}

function setInputError(id, hasError) {
    const el = document.getElementById(id);
    if (el) {
        el.classList.toggle('error', hasError);
    }
}

function validateForm() {
    let valid = true;
    const emailRe = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    const name = document.getElementById('name').value.trim();
    const email = document.getElementById('email').value.trim();
    const otp = document.getElementById('emailOtp').value.trim();
    const password = document.getElementById('pw').value;

    showErr('nameErr', !name);
    setInputError('name', !name);
    if (!name) valid = false;

    showErr('emailErr', !emailRe.test(email));
    setInputError('email', !emailRe.test(email));
    if (!emailRe.test(email)) valid = false;

    showErr('otpErr', !/^\d{4}$/.test(otp));
    setInputError('emailOtp', !/^\d{4}$/.test(otp));
    if (!/^\d{4}$/.test(otp)) valid = false;

    showErr('pwErr', password.length < 8);
    setInputError('pw', password.length < 8);
    if (password.length < 8) valid = false;

    return valid;
}

function setOtpStatus(message, isSuccess) {
    const status = document.getElementById('otpStatus');
    status.textContent = message;
    status.classList.remove('hidden', 'text-green-600', 'text-red-600', 'text-slate-500');
    status.classList.add(isSuccess ? 'text-green-600' : 'text-red-600');
}

function sendRegisterOtp() {
    const emailInput = document.getElementById('email');
    const email = emailInput.value.trim();
    const emailRe = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    const button = document.getElementById('sendOtpBtn');

    showErr('emailErr', !emailRe.test(email));
    setInputError('email', !emailRe.test(email));

    if (!emailRe.test(email)) {
        return;
    }

    button.disabled = true;
    button.textContent = 'Sending...';
    button.classList.add('opacity-70');
    setOtpStatus('Preparing OTP...', true);

    fetch('<?php echo e(route('frontend.register.otp')); ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
            'Accept': 'application/json',
        },
        body: JSON.stringify({ email }),
    })
    .then(async (response) => {
        const data = await response.json().catch(() => ({}));

        if (!response.ok) {
            const message = data.message || Object.values(data.errors || {})?.[0]?.[0] || 'Could not send OTP.';
            throw new Error(message);
        }

        setOtpStatus(data.message || 'OTP sent successfully. Please check your email.', true);
        document.getElementById('emailOtp').focus();
    })
    .catch((error) => {
        setOtpStatus(error.message || 'Could not send OTP.', false);
    })
    .finally(() => {
        button.disabled = false;
        button.textContent = 'Send OTP';
        button.classList.remove('opacity-70');
    });
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\BMDU Work\FarmSea-dashboard\resources\views/frontend/signup.blade.php ENDPATH**/ ?>