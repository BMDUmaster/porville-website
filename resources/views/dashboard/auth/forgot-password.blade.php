<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Admin Password | Porville</title>
    <link rel="icon" type="image/jpeg" href="{{ $brandLogoUrl }}">
    <link rel="shortcut icon" href="{{ $brandLogoUrl }}">
    <link rel="apple-touch-icon" href="{{ $brandLogoUrl }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&family=Playfair+Display:wght@700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Poppins', sans-serif; box-sizing: border-box; }
        .font-classic { font-family: 'Playfair Display', serif; }
    </style>
</head>
<body class="flex min-h-screen w-full items-center justify-center bg-black px-4 py-8">

    <div class="w-full max-w-md overflow-hidden rounded-3xl border border-amber-500/20 bg-[#0d0d0d] shadow-2xl">

        <div class="relative overflow-hidden border-b border-amber-500/20 px-8 pb-7 pt-8 text-center">
            <div class="absolute inset-0 opacity-[0.07]" style="background-image: radial-gradient(#d9a441 1px, transparent 1px); background-size: 22px 22px;"></div>
            <div class="relative z-10 flex flex-col items-center">
                <img src="{{ $brandLogoUrl }}" alt="Porville" class="mb-3 h-16 w-16 rounded-full object-cover ring-2 ring-amber-500/50 shadow-lg">
                <h1 class="font-classic text-2xl font-bold text-amber-300 tracking-wide">PORVILLE</h1>
                <p class="mt-1 text-[10px] font-bold uppercase tracking-[0.25em] text-stone-400">Reset Admin Password</p>
            </div>
        </div>

        <div class="bg-white px-8 py-7">
            @if(session('success'))
                <div class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 p-3 text-sm text-emerald-700">{{ session('success') }}</div>
            @endif
            @if($errors->any())
                <div class="mb-4 rounded-xl border border-red-200 bg-red-50 p-3 text-sm text-red-600">{{ $errors->first() }}</div>
            @endif

            @if(! $otpSent)
                {{-- Step 1: email --}}
                <p class="mb-5 text-sm text-slate-500">Enter your admin email. We'll send a 6-digit code to reset your password.</p>
                <form method="POST" action="{{ route('dashboard.password.send-otp') }}" class="space-y-5">
                    @csrf
                    <div>
                        <label class="mb-2 block text-[10px] font-extrabold uppercase tracking-widest text-slate-800">Admin Email</label>
                        <div class="relative">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4"><i class="fas fa-envelope text-sm text-slate-400"></i></div>
                            <input type="email" name="email" value="{{ old('email', $email) }}" required placeholder="admin@porville.in"
                                   class="w-full rounded-xl border border-slate-200 bg-slate-50 py-3.5 pl-11 pr-4 text-sm transition-all focus:border-amber-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-500/20">
                        </div>
                    </div>
                    <button type="submit" class="flex w-full items-center justify-center gap-2 rounded-xl border-2 border-amber-500 bg-black py-3.5 text-xs font-extrabold uppercase tracking-widest text-white shadow-lg transition hover:bg-neutral-900">
                        <i class="fa-solid fa-paper-plane text-amber-400"></i> Send Reset Code
                    </button>
                </form>
            @else
                {{-- Step 2: code + new password --}}
                <p class="mb-5 text-sm text-slate-500">Enter the code sent to <strong class="text-slate-800">{{ $email }}</strong> and choose a new password.</p>
                <form method="POST" action="{{ route('dashboard.password.reset') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="mb-2 block text-[10px] font-extrabold uppercase tracking-widest text-slate-800">6-digit Code</label>
                        <input type="text" name="otp" inputmode="numeric" maxlength="6" pattern="\d{6}" required autocomplete="one-time-code"
                               class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3.5 text-center font-mono text-xl tracking-[0.5em] transition focus:border-amber-500 focus:bg-white focus:outline-none">
                    </div>
                    <div>
                        <label class="mb-2 block text-[10px] font-extrabold uppercase tracking-widest text-slate-800">New Password</label>
                        <input type="password" name="password" required minlength="8" autocomplete="new-password" placeholder="At least 8 characters"
                               class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3.5 text-sm transition focus:border-amber-500 focus:bg-white focus:outline-none">
                    </div>
                    <div>
                        <label class="mb-2 block text-[10px] font-extrabold uppercase tracking-widest text-slate-800">Confirm New Password</label>
                        <input type="password" name="password_confirmation" required minlength="8" autocomplete="new-password"
                               class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3.5 text-sm transition focus:border-amber-500 focus:bg-white focus:outline-none">
                    </div>
                    <button type="submit" class="flex w-full items-center justify-center gap-2 rounded-xl border-2 border-amber-500 bg-black py-3.5 text-xs font-extrabold uppercase tracking-widest text-white shadow-lg transition hover:bg-neutral-900">
                        <i class="fa-solid fa-key text-amber-400"></i> Reset Password
                    </button>
                </form>
                <form method="POST" action="{{ route('dashboard.password.restart') }}" class="mt-4 text-center">
                    @csrf
                    <button type="submit" class="text-xs font-semibold text-slate-500 hover:text-amber-700">Use a different email / send a new code</button>
                </form>
            @endif

            <div class="mt-6 text-center">
                <a href="{{ auth()->check() ? route('dashboard.profile') : route('dashboard.login') }}" class="text-xs font-bold text-amber-700 hover:underline">
                    <i class="fa-solid fa-arrow-left mr-1"></i> {{ auth()->check() ? 'Back to Profile' : 'Back to Login' }}
                </a>
            </div>
        </div>

        <div class="border-t border-amber-500/10 bg-[#0d0d0d] px-8 py-4 text-center">
            <p class="text-[9px] font-bold uppercase tracking-widest text-stone-500">&copy; {{ date('Y') }} Porville &bull; Admin Dashboard</p>
        </div>
    </div>
</body>
</html>
