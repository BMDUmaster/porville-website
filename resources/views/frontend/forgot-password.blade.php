@extends('frontend.layouts.app')
@section('title', 'Forgot Password')

@section('content')
<main class="flex flex-1 items-center justify-center bg-slate-50 px-4 py-12">
    <div class="w-full max-w-md overflow-hidden rounded-3xl bg-white shadow-lg">
        <div class="px-8 py-8">
            <a href="{{ route('frontend.login') }}" class="mb-6 inline-flex items-center gap-2 text-sm font-bold text-slate-500 transition hover:text-blue-700">
                <i class="fa-solid fa-arrow-left text-xs"></i>
                Back to login
            </a>

            <div class="mb-6">
                <h1 class="nunito text-2xl font-extrabold text-gray-800">
                    {{ $step === 'reset' ? 'CREATE NEW PASSWORD' : ($step === 'otp' ? 'VERIFY OTP' : 'FORGOT PASSWORD') }}
                </h1>
                <p class="mt-1 text-sm text-gray-400">
                    {{ $step === 'reset' ? 'Set a fresh password for your FarmSea account.' : ($step === 'otp' ? 'Enter the OTP sent for your email.' : 'Enter your email address to receive an OTP.') }}
                </p>
            </div>

            <div class="mb-6 grid grid-cols-3 gap-2">
                @foreach(['email' => 'Email', 'otp' => 'OTP', 'reset' => 'Reset'] as $key => $label)
                    <div class="h-2 rounded-full {{ $step === $key || ($step === 'otp' && $key === 'email') || ($step === 'reset' && in_array($key, ['email', 'otp'], true)) ? 'bg-blue-700' : 'bg-slate-200' }}"></div>
                @endforeach
            </div>

            @if($errors->any())
                <div class="mb-4 rounded-xl border border-red-200 bg-red-50 p-3 text-sm font-medium text-red-600">
                    {{ $errors->first() }}
                </div>
            @endif

            @if(session('success'))
                <div class="mb-4 rounded-xl border border-green-200 bg-green-50 p-3 text-sm font-medium text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            @if($step === 'email')
                <form method="POST" action="{{ route('frontend.password.otp') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="mb-1 block text-sm font-semibold text-gray-700">Email Address</label>
                        <input type="email" name="email" value="{{ old('email') }}" required
                               placeholder="Enter your registered email"
                               class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:bg-white">
                    </div>

                    <button type="submit" class="w-full rounded-xl bg-blue-700 py-3 text-sm font-bold text-white transition hover:bg-blue-800">
                        <i class="fa-solid fa-paper-plane mr-2"></i> Send OTP
                    </button>
                </form>
            @elseif($step === 'otp')
                <form method="POST" action="{{ route('frontend.password.verify') }}" class="space-y-4">
                    @csrf
                    <div class="rounded-2xl border border-blue-100 bg-blue-50 px-4 py-3 text-sm font-semibold text-blue-800">
                        {{ session('password_reset_otp_email') }}
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-semibold text-gray-700">Enter OTP</label>
                        <input type="text" name="email_otp" value="{{ old('email_otp') }}" inputmode="numeric" maxlength="4" required
                               placeholder="4 digit OTP"
                               class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-center text-lg font-extrabold tracking-[0.45em] outline-none transition focus:border-blue-500 focus:bg-white">
                    </div>

                    <button type="submit" class="w-full rounded-xl bg-blue-700 py-3 text-sm font-bold text-white transition hover:bg-blue-800">
                        <i class="fa-solid fa-check mr-2"></i> Submit OTP
                    </button>
                </form>
            @else
                <form method="POST" action="{{ route('frontend.password.reset') }}" class="space-y-4">
                    @csrf
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
            @endif
        </div>
    </div>
</main>
@endsection
