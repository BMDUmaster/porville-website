<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | Porville</title>
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
            <div class="absolute inset-0 opacity-[0.07]"
                 style="background-image: radial-gradient(#d9a441 1px, transparent 1px); background-size: 22px 22px;"></div>
            <div class="relative z-10 flex flex-col items-center">
                <img src="{{ $brandLogoUrl }}" alt="Porville" class="mb-3 h-16 w-16 rounded-full object-cover ring-2 ring-amber-500/50 shadow-lg">
                <h1 class="font-classic text-2xl font-bold text-amber-300 tracking-wide">PORVILLE</h1>
                <p class="mt-1 text-[10px] font-bold uppercase tracking-[0.25em] text-stone-400">Admin Dashboard</p>
            </div>
        </div>

        <div class="bg-white px-8 py-7">
            @if(session('success'))
                <div class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 p-3 text-sm text-emerald-700">
                    {{ session('success') }}
                </div>
            @endif
            @if($errors->any())
                <div class="mb-4 rounded-xl border border-red-200 bg-red-50 p-3 text-sm text-red-600">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('dashboard.login.post') }}" class="space-y-5">
                @csrf

                <div>
                    <label class="mb-2 block text-[10px] font-extrabold uppercase tracking-widest text-slate-800">Email</label>
                    <div class="relative">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                            <i class="fas fa-envelope text-sm text-slate-400"></i>
                        </div>
                        <input type="email" name="email" value="{{ old('email') }}" required
                               placeholder="admin@porville.in"
                               class="w-full rounded-xl border border-slate-200 bg-slate-50 py-3.5 pl-11 pr-4 text-sm
                                      transition-all focus:border-amber-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-500/20">
                    </div>
                </div>

                <div>
                    <label class="mb-2 block text-[10px] font-extrabold uppercase tracking-widest text-slate-800">Password</label>
                    <div class="relative">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                            <i class="fas fa-lock text-sm text-slate-400"></i>
                        </div>
                        <input type="password" name="password" id="password" required placeholder="********"
                               class="w-full rounded-xl border border-slate-200 bg-slate-50 py-3.5 pl-11 pr-11 text-sm
                                      transition-all focus:border-amber-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-500/20">
                        <button type="button" onclick="togglePass()"
                                class="absolute inset-y-0 right-0 flex items-center pr-4 text-slate-400 transition-colors hover:text-amber-600">
                            <i class="fa-regular fa-eye text-sm" id="eyeIcon"></i>
                        </button>
                    </div>
                </div>

                <div class="flex items-center justify-between text-xs font-semibold">
                    <label class="flex cursor-pointer select-none items-center gap-2 text-slate-500">
                        <input type="checkbox" name="remember" class="h-3.5 w-3.5 accent-amber-600">
                        <span>Keep me logged in</span>
                    </label>
                    <a href="{{ route('dashboard.password.forgot') }}" class="text-amber-700 hover:underline">Forgot password?</a>
                </div>

                <button type="submit"
                        class="flex w-full items-center justify-center gap-2 rounded-xl border-2 border-amber-500 bg-black py-3.5
                               text-xs font-extrabold uppercase tracking-widest text-white shadow-lg transition-all duration-300
                               hover:bg-neutral-900 focus:outline-none focus:ring-4 focus:ring-amber-500/20">
                    <i class="fa-solid fa-right-to-bracket text-amber-400"></i>
                    Login to Panel
                </button>
            </form>
        </div>

        <div class="border-t border-amber-500/10 bg-[#0d0d0d] px-8 py-4 text-center">
            <p class="text-[9px] font-bold uppercase tracking-widest text-stone-500">
                &copy; {{ date('Y') }} Porville &bull; Admin Dashboard
            </p>
        </div>
    </div>

    <script>
        function togglePass() {
            const input = document.getElementById('password');
            const icon  = document.getElementById('eyeIcon');
            input.type  = input.type === 'password' ? 'text' : 'password';
            icon.className = input.type === 'password' ? 'fa-regular fa-eye text-sm' : 'fa-regular fa-eye-slash text-sm';
        }
    </script>
</body>
</html>
