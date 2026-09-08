@extends('frontend.layouts.app')
@section('title', 'Account Authentication')

@section('content')
<section class="relative overflow-hidden bg-[linear-gradient(135deg,#eef6ff_0%,#f7fbf5_52%,#ffffff_100%)] px-4 py-10 md:px-6 md:py-14">
    <div class="pointer-events-none absolute -left-24 top-16 h-72 w-72 rounded-full bg-blue-200/25 blur-3xl"></div>
    <div class="pointer-events-none absolute -right-20 bottom-0 h-72 w-72 rounded-full bg-green-200/30 blur-3xl"></div>

    <div class="relative mx-auto grid w-full max-w-5xl overflow-hidden rounded-[30px] border border-white/80 bg-white shadow-[0_28px_90px_rgba(15,23,42,0.13)] lg:min-h-[580px] lg:grid-cols-[1.05fr_0.95fr]">
        <!-- Left Banner Image (Desktop) -->
        <div class="relative hidden min-h-[580px] overflow-hidden lg:block">
            <img
                src="{{ asset('storage/products/HiVF3oTdVV5ivcPECY0aMpXFljIdua0hBYBlCAUW.webp') }}"
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
                    Sign in or create an account to order fresh sea products, track deliveries, and enjoy fast checkout.
                </p>
                <div class="mt-6 flex flex-wrap gap-3 text-[10px] font-bold uppercase tracking-[0.12em] text-white/90">
                    <span class="rounded-full border border-white/15 bg-black/15 px-3 py-2"><i class="fa-solid fa-shield-halved mr-2 text-green-300"></i>Secure account</span>
                    <span class="rounded-full border border-white/15 bg-black/15 px-3 py-2"><i class="fa-solid fa-truck-fast mr-2 text-green-300"></i>Fast delivery</span>
                </div>
            </div>
        </div>

        <!-- Right Dynamic Auth Container -->
        <div class="flex items-center px-6 py-9 sm:px-10 md:py-12 lg:px-12">
            <div class="mx-auto w-full max-w-[400px]">
                <a href="{{ route('frontend.home') }}" class="mb-6 inline-flex items-center">
                    <img src="{{ $brandLogoUrl }}" alt="FarmSea" class="h-14 w-auto object-contain">
                </a>

                <!-- Step Indicator -->
                <div class="flex items-center gap-2 text-[10px] font-extrabold uppercase tracking-[0.14em]">
                    <span id="stepBadge1" class="rounded-full bg-blue-50 px-3 py-1.5 text-blue-700">1. Email</span>
                    <span id="stepBadge2" class="rounded-full bg-slate-100 px-3 py-1.5 text-slate-400">2. Continue</span>
                </div>

                <h1 id="authTitle" class="mt-4 text-2xl font-extrabold tracking-tight text-slate-950 sm:text-3xl">Enter your email</h1>
                <p id="authDescription" class="mt-2 text-[13px] leading-6 text-slate-500">We'll check whether you need to sign in or create an account.</p>

                <!-- Server Errors/Success from standard redirects -->
                @if($errors->any())
                    <div class="mt-4 flex gap-3 rounded-xl border border-red-100 bg-red-50 p-3.5 text-[12px] leading-5 text-red-700">
                        <i class="fa-solid fa-circle-exclamation mt-0.5"></i>
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif

                @if(session('success'))
                    <div class="mt-4 flex gap-3 rounded-xl border border-green-100 bg-green-50 p-3.5 text-[12px] leading-5 text-green-700">
                        <i class="fa-solid fa-circle-check mt-0.5"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                <!-- Dynamic AJAX Alert Box -->
                <div id="ajaxAlert" class="hidden mt-4 flex gap-3 rounded-xl border p-3.5 text-[12px] leading-5 font-medium transition-all"></div>

                <!-- Active Email Badge (shown in step 2) -->
                <div id="activeEmailBadge" class="hidden mt-4 flex items-center justify-between rounded-xl bg-slate-100 px-3.5 py-2.5 text-[12px] font-semibold text-slate-700 border border-slate-200">
                    <span class="truncate"><i class="fa-regular fa-envelope mr-2 text-slate-400"></i><span id="activeEmailText"></span></span>
                    <button type="button" id="btnChangeEmail" class="ml-2 text-[11px] font-extrabold text-blue-600 hover:text-blue-800 hover:underline">Change</button>
                </div>

                <!-- ================= STEP 1: EMAIL ENTRY ================= -->
                <div id="stepEmailContainer" class="mt-6">
                    <form id="formCheckEmail" class="space-y-5" onsubmit="event.preventDefault(); handleCheckEmail();">
                        <div>
                            <label for="inputEmail" class="mb-2 block text-[12px] font-extrabold text-slate-700">Email Address</label>
                            <div class="relative">
                                <i class="fa-regular fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-sm text-slate-400"></i>
                                <input id="inputEmail" type="email" name="email" value="{{ old('email', request('email')) }}" required autocomplete="email"
                                       placeholder="you@example.com"
                                       class="w-full rounded-xl border border-slate-200 bg-slate-50 py-3.5 pl-11 pr-4 text-[13px] text-slate-900 outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-50">
                            </div>
                        </div>

                        <button type="submit" id="btnCheckEmail" class="flex w-full items-center justify-center gap-2 rounded-xl bg-blue-600 py-3.5 text-[12px] font-extrabold uppercase tracking-[0.12em] text-white shadow-[0_12px_25px_rgba(37,99,235,0.22)] transition hover:-translate-y-0.5 hover:bg-blue-700 active:translate-y-0">
                            <span>Continue</span> <i class="fa-solid fa-arrow-right text-[10px]"></i>
                        </button>
                    </form>
                </div>

                <!-- ================= STEP 2A: LOGIN (Password) ================= -->
                <div id="stepLoginContainer" class="hidden mt-6">
                    <form id="formLogin" class="space-y-5" onsubmit="event.preventDefault(); handleLogin();">
                        <div>
                            <label for="inputLoginPassword" class="mb-2 block text-[12px] font-extrabold text-slate-700">Password</label>
                            <div class="relative">
                                <i class="fa-solid fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-sm text-slate-400"></i>
                                <input id="inputLoginPassword" type="password" name="password" autocomplete="current-password"
                                       placeholder="Enter your password"
                                       class="w-full rounded-xl border border-slate-200 bg-slate-50 py-3.5 pl-11 pr-11 text-[13px] text-slate-900 outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-50">
                                <button type="button" onclick="togglePasswordVisibility('inputLoginPassword', this)" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                                    <i class="fa-regular fa-eye text-sm"></i>
                                </button>
                            </div>
                        </div>

                        <div class="flex items-center justify-between gap-4">
                            <label class="flex cursor-pointer items-center gap-2.5 text-[12px] font-medium text-slate-600">
                                <input type="checkbox" name="remember" checked class="h-4 w-4 rounded border-slate-300 accent-blue-600">
                                Remember Me
                            </label>
                            <button type="button" id="btnForgotPassword" onclick="handleStartForgotPassword();" class="text-[12px] font-extrabold text-blue-700 transition hover:text-blue-900 hover:underline">
                                Forgot password?
                            </button>
                        </div>

                        <button type="submit" id="btnLoginSubmit" class="flex w-full items-center justify-center gap-2 rounded-xl bg-blue-600 py-3.5 text-[12px] font-extrabold uppercase tracking-[0.12em] text-white shadow-[0_12px_25px_rgba(37,99,235,0.22)] transition hover:-translate-y-0.5 hover:bg-blue-700 active:translate-y-0">
                            <span>Sign In</span> <i class="fa-solid fa-arrow-right text-[10px]"></i>
                        </button>
                    </form>
                </div>

                <!-- ================= STEP 2B: REGISTER (OTP + Name + Password) ================= -->
                <div id="stepRegisterContainer" class="hidden mt-6">
                    <form id="formRegister" class="space-y-4" onsubmit="event.preventDefault(); handleRegister();">
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label for="inputRegisterOtp" class="text-[12px] font-extrabold text-slate-700">4-Digit Email OTP</label>
                                <button type="button" id="btnResendRegisterOtp" onclick="handleResendRegisterOtp()" class="text-[11px] font-bold text-blue-600 hover:underline disabled:opacity-50">Resend OTP</button>
                            </div>
                            <div class="relative">
                                <i class="fa-solid fa-key absolute left-4 top-1/2 -translate-y-1/2 text-sm text-slate-400"></i>
                                <input id="inputRegisterOtp" type="text" name="email_otp" maxlength="4" placeholder="Enter 4-digit OTP"
                                       class="w-full rounded-xl border border-slate-200 bg-slate-50 py-3 pl-11 pr-4 text-[13px] font-mono tracking-widest text-slate-900 outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-50">
                            </div>
                        </div>

                        <div>
                            <label for="inputRegisterName" class="mb-1.5 block text-[12px] font-extrabold text-slate-700">Full Name</label>
                            <div class="relative">
                                <i class="fa-regular fa-user absolute left-4 top-1/2 -translate-y-1/2 text-sm text-slate-400"></i>
                                <input id="inputRegisterName" type="text" name="name" placeholder="Rahul Sharma"
                                       class="w-full rounded-xl border border-slate-200 bg-slate-50 py-3 pl-11 pr-4 text-[13px] text-slate-900 outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-50">
                            </div>
                        </div>

                        <div>
                            <label for="inputRegisterPassword" class="mb-1.5 block text-[12px] font-extrabold text-slate-700">Password (min 6 chars)</label>
                            <div class="relative">
                                <i class="fa-solid fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-sm text-slate-400"></i>
                                <input id="inputRegisterPassword" type="password" name="password" minlength="6" placeholder="Create password"
                                       class="w-full rounded-xl border border-slate-200 bg-slate-50 py-3 pl-11 pr-11 text-[13px] text-slate-900 outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-50">
                                <button type="button" onclick="togglePasswordVisibility('inputRegisterPassword', this)" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                                    <i class="fa-regular fa-eye text-sm"></i>
                                </button>
                            </div>
                        </div>

                        <button type="submit" id="btnRegisterSubmit" class="mt-2 flex w-full items-center justify-center gap-2 rounded-xl bg-blue-600 py-3.5 text-[12px] font-extrabold uppercase tracking-[0.12em] text-white shadow-[0_12px_25px_rgba(37,99,235,0.22)] transition hover:-translate-y-0.5 hover:bg-blue-700 active:translate-y-0">
                            <span>Create Account & Sign In</span> <i class="fa-solid fa-arrow-right text-[10px]"></i>
                        </button>
                    </form>
                </div>

                <!-- ================= STEP 2C: FORGOT PASSWORD (OTP + New Password) ================= -->
                <div id="stepForgotPasswordContainer" class="hidden mt-6">
                    <form id="formForgotPassword" class="space-y-4" onsubmit="event.preventDefault(); handleResetPassword();">
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label for="inputForgotOtp" class="text-[12px] font-extrabold text-slate-700">4-Digit Reset OTP</label>
                                <button type="button" id="btnResendForgotOtp" onclick="handleResendForgotOtp()" class="text-[11px] font-bold text-blue-600 hover:underline disabled:opacity-50">Resend OTP</button>
                            </div>
                            <div class="relative">
                                <i class="fa-solid fa-key absolute left-4 top-1/2 -translate-y-1/2 text-sm text-slate-400"></i>
                                <input id="inputForgotOtp" type="text" name="email_otp" maxlength="4" placeholder="Enter 4-digit OTP"
                                       class="w-full rounded-xl border border-slate-200 bg-slate-50 py-3 pl-11 pr-4 text-[13px] font-mono tracking-widest text-slate-900 outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-50">
                            </div>
                        </div>

                        <div>
                            <label for="inputForgotNewPassword" class="mb-1.5 block text-[12px] font-extrabold text-slate-700">New Password (min 6 chars)</label>
                            <div class="relative">
                                <i class="fa-solid fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-sm text-slate-400"></i>
                                <input id="inputForgotNewPassword" type="password" name="password" minlength="6" placeholder="Enter new password"
                                       class="w-full rounded-xl border border-slate-200 bg-slate-50 py-3 pl-11 pr-11 text-[13px] text-slate-900 outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-50">
                                <button type="button" onclick="togglePasswordVisibility('inputForgotNewPassword', this)" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                                    <i class="fa-regular fa-eye text-sm"></i>
                                </button>
                            </div>
                        </div>

                        <div>
                            <label for="inputForgotConfirmPassword" class="mb-1.5 block text-[12px] font-extrabold text-slate-700">Confirm New Password</label>
                            <div class="relative">
                                <i class="fa-solid fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-sm text-slate-400"></i>
                                <input id="inputForgotConfirmPassword" type="password" name="password_confirmation" minlength="6" placeholder="Confirm new password"
                                       class="w-full rounded-xl border border-slate-200 bg-slate-50 py-3 pl-11 pr-11 text-[13px] text-slate-900 outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-50">
                                <button type="button" onclick="togglePasswordVisibility('inputForgotConfirmPassword', this)" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                                    <i class="fa-regular fa-eye text-sm"></i>
                                </button>
                            </div>
                        </div>

                        <button type="submit" id="btnResetPasswordSubmit" class="mt-2 flex w-full items-center justify-center gap-2 rounded-xl bg-blue-600 py-3.5 text-[12px] font-extrabold uppercase tracking-[0.12em] text-white shadow-[0_12px_25px_rgba(37,99,235,0.22)] transition hover:-translate-y-0.5 hover:bg-blue-700 active:translate-y-0">
                            <span>Reset & Update Password</span> <i class="fa-solid fa-arrow-right text-[10px]"></i>
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
const CSRF_TOKEN = '{{ csrf_token() }}';
let activeEmail = '';
let currentStep = 'email'; // 'email', 'login', 'register', 'forgot'

// DOM Elements
const authTitle = document.getElementById('authTitle');
const authDescription = document.getElementById('authDescription');
const stepBadge1 = document.getElementById('stepBadge1');
const stepBadge2 = document.getElementById('stepBadge2');
const ajaxAlert = document.getElementById('ajaxAlert');
const activeEmailBadge = document.getElementById('activeEmailBadge');
const activeEmailText = document.getElementById('activeEmailText');
const btnChangeEmail = document.getElementById('btnChangeEmail');

const stepEmailContainer = document.getElementById('stepEmailContainer');
const stepLoginContainer = document.getElementById('stepLoginContainer');
const stepRegisterContainer = document.getElementById('stepRegisterContainer');
const stepForgotPasswordContainer = document.getElementById('stepForgotPasswordContainer');

const inputEmail = document.getElementById('inputEmail');

function showAlert(message, isError = true) {
    ajaxAlert.classList.remove('hidden', 'border-red-100', 'bg-red-50', 'text-red-700', 'border-green-100', 'bg-green-50', 'text-green-700');
    if (isError) {
        ajaxAlert.classList.add('border-red-100', 'bg-red-50', 'text-red-700');
        ajaxAlert.innerHTML = `<i class="fa-solid fa-circle-exclamation mt-0.5"></i><span>${message}</span>`;
    } else {
        ajaxAlert.classList.add('border-green-100', 'bg-green-50', 'text-green-700');
        ajaxAlert.innerHTML = `<i class="fa-solid fa-circle-check mt-0.5"></i><span>${message}</span>`;
    }
}

function hideAlert() {
    ajaxAlert.classList.add('hidden');
    ajaxAlert.innerHTML = '';
}

function togglePasswordVisibility(fieldId, btn) {
    const input = document.getElementById(fieldId);
    const icon = btn.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'fa-regular fa-eye-slash text-sm';
    } else {
        input.type = 'password';
        icon.className = 'fa-regular fa-eye text-sm';
    }
}

function showStep(stepName) {
    currentStep = stepName;
    hideAlert();

    stepEmailContainer.classList.add('hidden');
    stepLoginContainer.classList.add('hidden');
    stepRegisterContainer.classList.add('hidden');
    stepForgotPasswordContainer.classList.add('hidden');
    activeEmailBadge.classList.add('hidden');

    if (stepName === 'email') {
        authTitle.textContent = 'Enter your email';
        authDescription.textContent = "We'll check whether you need to sign in or create an account.";
        stepBadge1.className = 'rounded-full bg-blue-50 px-3 py-1.5 text-blue-700';
        stepBadge2.className = 'rounded-full bg-slate-100 px-3 py-1.5 text-slate-400';
        stepEmailContainer.classList.remove('hidden');
        inputEmail.focus();
    } else {
        stepBadge1.className = 'rounded-full bg-slate-100 px-3 py-1.5 text-slate-400';
        stepBadge2.className = 'rounded-full bg-blue-50 px-3 py-1.5 text-blue-700';
        activeEmailText.textContent = activeEmail;
        activeEmailBadge.classList.remove('hidden');

        if (stepName === 'login') {
            authTitle.textContent = 'Welcome back';
            authDescription.textContent = 'Enter your password to sign in.';
            stepLoginContainer.classList.remove('hidden');
            document.getElementById('inputLoginPassword').focus();
        } else if (stepName === 'register') {
            authTitle.textContent = 'Create your account';
            authDescription.textContent = 'Enter the OTP code sent to your email to register.';
            stepRegisterContainer.classList.remove('hidden');
            document.getElementById('inputRegisterOtp').focus();
        } else if (stepName === 'forgot') {
            authTitle.textContent = 'Reset your password';
            authDescription.textContent = 'Enter the OTP sent to your email to set a new password.';
            stepForgotPasswordContainer.classList.remove('hidden');
            document.getElementById('inputForgotOtp').focus();
        }
    }
}

btnChangeEmail.addEventListener('click', () => {
    showStep('email');
});

// 1. STEP 1: CHECK EMAIL
async function handleCheckEmail() {
    const email = inputEmail.value.trim();
    if (!email) {
        showAlert('Please enter a valid email address.');
        return;
    }

    const btn = document.getElementById('btnCheckEmail');
    btn.disabled = true;
    btn.innerHTML = `<i class="fa-solid fa-circle-notch fa-spin text-sm"></i> <span>Checking...</span>`;
    hideAlert();

    try {
        const response = await fetch('{{ route('frontend.email.check') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': CSRF_TOKEN
            },
            body: JSON.stringify({ email })
        });

        const data = await response.json();

        if (!response.ok) {
            throw new Error(data.message || 'Validation error. Please check your email.');
        }

        activeEmail = data.email;

        if (data.exists) {
            showStep('login');
        } else {
            // Unregistered user -> send OTP then show register step
            await sendRegisterOtp(data.email);
            showStep('register');
        }
    } catch (err) {
        showAlert(err.message || 'An error occurred. Please try again.');
    } finally {
        btn.disabled = false;
        btn.innerHTML = `<span>Continue</span> <i class="fa-solid fa-arrow-right text-[10px]"></i>`;
    }
}

// 2. STEP 2A: LOGIN SUBMIT
async function handleLogin() {
    const password = document.getElementById('inputLoginPassword').value;
    const remember = document.querySelector('input[name="remember"]').checked;

    if (!password) {
        showAlert('Please enter your password.');
        return;
    }

    const btn = document.getElementById('btnLoginSubmit');
    btn.disabled = true;
    btn.innerHTML = `<i class="fa-solid fa-circle-notch fa-spin text-sm"></i> <span>Signing in...</span>`;
    hideAlert();

    try {
        const response = await fetch('{{ route('frontend.login.post') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': CSRF_TOKEN
            },
            body: JSON.stringify({
                email: activeEmail,
                password: password,
                remember: remember ? 1 : 0
            })
        });

        const data = await response.json();

        if (!response.ok || !data.success) {
            throw new Error(data.message || 'Invalid email or password.');
        }

        showAlert(data.message || 'Login successful! Redirecting...', false);
        setTimeout(() => {
            window.location.href = data.redirect_url || '{{ route('frontend.profile') }}';
        }, 500);
    } catch (err) {
        showAlert(err.message || 'Invalid credentials. Please try again.');
        btn.disabled = false;
        btn.innerHTML = `<span>Sign In</span> <i class="fa-solid fa-arrow-right text-[10px]"></i>`;
    }
}

// 3. STEP 2B: REGISTER SUBMIT & OTP RESEND
async function sendRegisterOtp(email) {
    const response = await fetch('{{ route('frontend.register.otp') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': CSRF_TOKEN
        },
        body: JSON.stringify({ email })
    });

    const data = await response.json();
    if (!response.ok || !data.success) {
        throw new Error(data.message || 'Could not send OTP.');
    }
    showAlert(data.message || 'OTP sent to your email inbox.', false);
}

async function handleResendRegisterOtp() {
    const btn = document.getElementById('btnResendRegisterOtp');
    btn.disabled = true;
    try {
        await sendRegisterOtp(activeEmail);
    } catch (err) {
        showAlert(err.message);
    } finally {
        setTimeout(() => { btn.disabled = false; }, 10000);
    }
}

async function handleRegister() {
    const otp = document.getElementById('inputRegisterOtp').value.trim();
    const name = document.getElementById('inputRegisterName').value.trim();
    const password = document.getElementById('inputRegisterPassword').value.trim();

    if (!otp || otp.length !== 4) {
        showAlert('Please enter the 4-digit OTP sent to your email.');
        return;
    }
    if (!name || name.length < 3) {
        showAlert('Please enter your full name (minimum 3 characters).');
        return;
    }
    if (!password || password.length < 6) {
        showAlert('Password must be at least 6 characters.');
        return;
    }

    const btn = document.getElementById('btnRegisterSubmit');
    btn.disabled = true;
    btn.innerHTML = `<i class="fa-solid fa-circle-notch fa-spin text-sm"></i> <span>Creating Account...</span>`;
    hideAlert();

    try {
        const response = await fetch('{{ route('frontend.register.post') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': CSRF_TOKEN
            },
            body: JSON.stringify({
                email: activeEmail,
                email_otp: otp,
                name: name,
                password: password
            })
        });

        const data = await response.json();

        if (!response.ok || !data.success) {
            throw new Error(data.message || 'Registration failed. Please check your OTP and details.');
        }

        showAlert(data.message || 'Account created successfully! Redirecting...', false);
        setTimeout(() => {
            window.location.href = data.redirect_url || '{{ route('frontend.profile') }}';
        }, 500);
    } catch (err) {
        showAlert(err.message || 'Registration error. Please check your details.');
        btn.disabled = false;
        btn.innerHTML = `<span>Create Account & Sign In</span> <i class="fa-solid fa-arrow-right text-[10px]"></i>`;
    }
}

// 4. STEP 2C: FORGOT PASSWORD SUBMIT & OTP RESEND
async function sendForgotPasswordOtp(email) {
    const response = await fetch('{{ route('frontend.password.otp') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': CSRF_TOKEN
        },
        body: JSON.stringify({ email })
    });

    const data = await response.json();
    if (!response.ok || !data.success) {
        throw new Error(data.message || 'Could not send reset OTP.');
    }
    showAlert(data.message || 'OTP sent to your email inbox.', false);
}

async function handleStartForgotPassword() {
    const btn = document.getElementById('btnForgotPassword');
    btn.disabled = true;
    hideAlert();

    try {
        await sendForgotPasswordOtp(activeEmail);
        showStep('forgot');
    } catch (err) {
        showAlert(err.message);
    } finally {
        btn.disabled = false;
    }
}

async function handleResendForgotOtp() {
    const btn = document.getElementById('btnResendForgotOtp');
    btn.disabled = true;
    try {
        await sendForgotPasswordOtp(activeEmail);
    } catch (err) {
        showAlert(err.message);
    } finally {
        setTimeout(() => { btn.disabled = false; }, 10000);
    }
}

async function handleResetPassword() {
    const otp = document.getElementById('inputForgotOtp').value.trim();
    const newPassword = document.getElementById('inputForgotNewPassword').value.trim();
    const confirmPassword = document.getElementById('inputForgotConfirmPassword').value.trim();

    if (!otp || otp.length !== 4) {
        showAlert('Please enter the 4-digit reset OTP.');
        return;
    }
    if (!newPassword || newPassword.length < 6) {
        showAlert('New password must be at least 6 characters.');
        return;
    }
    if (newPassword !== confirmPassword) {
        showAlert('Passwords do not match. Please check and try again.');
        return;
    }

    const btn = document.getElementById('btnResetPasswordSubmit');
    btn.disabled = true;
    btn.innerHTML = `<i class="fa-solid fa-circle-notch fa-spin text-sm"></i> <span>Updating Password...</span>`;
    hideAlert();

    try {
        // Step A: verify OTP
        const verifyRes = await fetch('{{ route('frontend.password.verify') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': CSRF_TOKEN
            },
            body: JSON.stringify({ email_otp: otp })
        });

        const verifyData = await verifyRes.json();
        if (!verifyRes.ok || !verifyData.success) {
            throw new Error(verifyData.message || 'Invalid or expired OTP.');
        }

        // Step B: reset password
        const resetRes = await fetch('{{ route('frontend.password.reset') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': CSRF_TOKEN
            },
            body: JSON.stringify({
                password: newPassword,
                password_confirmation: confirmPassword
            })
        });

        const resetData = await resetRes.json();
        if (!resetRes.ok || !resetData.success) {
            throw new Error(resetData.message || 'Password reset failed.');
        }

        // Return to login step with success message
        showStep('login');
        showAlert(resetData.message || 'Password updated successfully! Please log in with your new password.', false);
        document.getElementById('inputLoginPassword').value = '';
    } catch (err) {
        showAlert(err.message || 'Reset failed. Please check your details.');
    } finally {
        btn.disabled = false;
        btn.innerHTML = `<span>Reset & Update Password</span> <i class="fa-solid fa-arrow-right text-[10px]"></i>`;
    }
}

// Check initial query parameters on page load
document.addEventListener('DOMContentLoaded', () => {
    const urlParams = new URLSearchParams(window.location.search);
    const initialEmail = urlParams.get('email');
    if (initialEmail) {
        inputEmail.value = initialEmail;
        handleCheckEmail();
    }
});
</script>
@endsection
