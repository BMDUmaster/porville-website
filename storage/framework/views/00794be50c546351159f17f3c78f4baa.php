<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | FarmSea</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: { 'mayview-blue': '#1e40af', 'mayview-dark': '#0f172a', 'mayview-light': '#3b82f6' },
                    fontFamily: { sans: ['Poppins', 'sans-serif'] }
                }
            }
        }
    </script>
</head>
<body class="min-h-screen w-full overflow-auto flex items-center justify-center font-sans py-8 px-4 bg-cover bg-center bg-no-repeat"
      style="background-image: linear-gradient(rgba(15,23,42,0.80), rgba(30,64,175,0.50)), url('https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&q=80&w=1600');">

    <div class="w-full max-w-md rounded-3xl shadow-2xl overflow-hidden border border-white/10">

        <div class="bg-mayview-dark px-8 pt-8 pb-7 text-center relative overflow-hidden">
            <div class="absolute inset-0 opacity-10"
                 style="background-image: radial-gradient(#fff 1px, transparent 1px); background-size: 22px 22px;"></div>
            <div class="relative z-10 flex flex-col items-center">
                <div class="mb-3 flex h-20 w-20 items-center justify-center rounded-2xl bg-white p-3 shadow-lg shadow-black/10">
                    <img src="<?php echo e(asset('images/Farmsea.webp')); ?>" alt="FarmSea" class="max-h-full max-w-full object-contain">
                </div>
                <h1 class="text-xl font-extrabold text-white tracking-tight">FarmSea Portal</h1>
                <div class="mt-2 h-0.5 w-10 rounded-full bg-mayview-light"></div>
            </div>
        </div>

        <div class="bg-white px-8 py-7">
            <?php if($errors->any()): ?>
                <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-xl text-sm text-red-600">
                    <?php echo e($errors->first()); ?>

                </div>
            <?php endif; ?>

            <form method="POST" action="<?php echo e(route('dashboard.login.post')); ?>" class="space-y-5">
                <?php echo csrf_field(); ?>

                <div>
                    <label class="block text-[10px] font-extrabold text-mayview-dark uppercase tracking-widest mb-2">Email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-envelope text-slate-400 text-sm"></i>
                        </div>
                        <input type="email" name="email" value="<?php echo e(old('email')); ?>" required
                               placeholder="admin@farmsea.in"
                               class="w-full pl-11 pr-4 py-3.5 border border-slate-200 rounded-xl bg-slate-50 text-sm
                                      focus:outline-none focus:ring-2 focus:ring-mayview-light/20 focus:border-mayview-light focus:bg-white transition-all">
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-extrabold text-mayview-dark uppercase tracking-widest mb-2">Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-slate-400 text-sm"></i>
                        </div>
                        <input type="password" name="password" id="password" required placeholder="********"
                               class="w-full pl-11 pr-11 py-3.5 border border-slate-200 rounded-xl bg-slate-50 text-sm
                                      focus:outline-none focus:ring-2 focus:ring-mayview-light/20 focus:border-mayview-light focus:bg-white transition-all">
                        <button type="button" onclick="togglePass()"
                                class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-mayview-blue transition-colors">
                            <i class="fa-regular fa-eye text-sm" id="eyeIcon"></i>
                        </button>
                    </div>
                </div>

                <div class="flex items-center justify-between text-xs font-semibold">
                    <label class="flex items-center gap-2 text-slate-500 cursor-pointer select-none">
                        <input type="checkbox" name="remember" class="accent-mayview-blue w-3.5 h-3.5">
                        <span>Keep me logged in</span>
                    </label>
                </div>

                <button type="submit"
                        class="w-full flex items-center justify-center gap-2 py-3.5 bg-mayview-blue hover:bg-mayview-dark
                               text-white text-xs font-extrabold uppercase tracking-widest rounded-xl shadow-lg
                               shadow-blue-700/25 transition-all duration-300 focus:outline-none focus:ring-4 focus:ring-mayview-blue/20">
                    <i class="fa-solid fa-right-to-bracket"></i>
                    Login to Panel
                </button>
            </form>
        </div>

        <div class="bg-slate-50 border-t border-slate-100 px-8 py-4 text-center">
            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">
                &copy; <?php echo e(date('Y')); ?> FarmSea &bull; Admin Dashboard
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
<?php /**PATH C:\FarmSea-dashboard\resources\views/dashboard/auth/login.blade.php ENDPATH**/ ?>