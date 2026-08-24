@extends('frontend.layouts.app')
@section('title', 'Sign In')

@section('content')
<section class="relative overflow-hidden bg-[linear-gradient(135deg,#eef6ff_0%,#f7fbf5_52%,#ffffff_100%)] px-4 py-10 md:px-6 md:py-14">
    <div class="pointer-events-none absolute -left-24 top-16 h-72 w-72 rounded-full bg-blue-200/25 blur-3xl"></div>
    <div class="pointer-events-none absolute -right-20 bottom-0 h-72 w-72 rounded-full bg-green-200/30 blur-3xl"></div>

    <div class="relative mx-auto grid w-full max-w-5xl overflow-hidden rounded-[30px] border border-white/80 bg-white shadow-[0_28px_90px_rgba(15,23,42,0.13)] lg:min-h-[560px] lg:grid-cols-[1.05fr_0.95fr]">
        <div class="relative hidden min-h-[560px] overflow-hidden lg:block">
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
                <a href="{{ route('frontend.home') }}" class="mb-8 inline-flex items-center">
                    <img src="{{ $brandLogoUrl }}" alt="FarmSea" class="h-14 w-auto object-contain">
                </a>

                <span class="inline-flex rounded-full bg-blue-50 px-3 py-1.5 text-[10px] font-extrabold uppercase tracking-[0.18em] text-blue-700">Welcome back</span>
                <h1 class="mt-4 text-3xl font-extrabold tracking-tight text-slate-950">Sign in to FarmSea</h1>
                <p class="mt-2 text-[13px] leading-6 text-slate-500">Enter your account details to continue shopping fresh.</p>

                @if($errors->any())
                    <div class="mt-5 flex gap-3 rounded-xl border border-red-100 bg-red-50 p-3.5 text-[12px] leading-5 text-red-700">
                        <i class="fa-solid fa-circle-exclamation mt-0.5"></i>
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif

                @if(session('success'))
                    <div class="mt-5 flex gap-3 rounded-xl border border-green-100 bg-green-50 p-3.5 text-[12px] leading-5 text-green-700">
                        <i class="fa-solid fa-circle-check mt-0.5"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                <form method="POST" action="{{ route('frontend.login.post') }}" class="mt-7 space-y-5">
                    @csrf
                    <div>
                        <label for="login-email" class="mb-2 block text-[12px] font-extrabold text-slate-700">Email Address</label>
                        <div class="relative">
                            <i class="fa-regular fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-sm text-slate-400"></i>
                            <input id="login-email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email"
                                   placeholder="you@example.com"
                                   class="w-full rounded-xl border border-slate-200 bg-slate-50 py-3.5 pl-11 pr-4 text-[13px] text-slate-900 outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-50">
                        </div>
                    </div>

                    <div>
                        <label for="login-password" class="mb-2 block text-[12px] font-extrabold text-slate-700">Password</label>
                        <div class="relative">
                            <i class="fa-solid fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-sm text-slate-400"></i>
                            <input id="login-password" type="password" name="password" required autocomplete="current-password"
                                   placeholder="Enter your password"
                                   class="w-full rounded-xl border border-slate-200 bg-slate-50 py-3.5 pl-11 pr-4 text-[13px] text-slate-900 outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-50">
                        </div>
                    </div>

                    <div class="flex items-center justify-between gap-4">
                        <label class="flex cursor-pointer items-center gap-2.5 text-[12px] font-medium text-slate-600">
                            <input type="checkbox" name="remember" class="h-4 w-4 rounded border-slate-300 accent-blue-600">
                            Remember Me
                        </label>
                        <a href="{{ route('frontend.password.forgot') }}" class="text-[12px] font-extrabold text-blue-700 transition hover:text-blue-900 hover:underline">Forgot password?</a>
                    </div>

                    <button type="submit" class="flex w-full items-center justify-center gap-2 rounded-xl bg-blue-600 py-3.5 text-[12px] font-extrabold uppercase tracking-[0.12em] text-white shadow-[0_12px_25px_rgba(37,99,235,0.22)] transition hover:-translate-y-0.5 hover:bg-blue-700 hover:shadow-[0_16px_30px_rgba(37,99,235,0.28)]">
                        Sign In <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </button>

                    <p class="text-center text-[12px] text-slate-500">
                        New to FarmSea?
                        <a href="{{ route('frontend.register') }}" class="ml-1 font-extrabold text-green-700 hover:text-green-900 hover:underline">Create an account</a>
                    </p>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
