<?php $__env->startSection('title', 'Page Not Found'); ?>
<?php $__env->startSection('styles'); ?>
<style>
    @keyframes bob {
        0%, 100% { transform: translateY(0) rotate(-3deg); }
        50% { transform: translateY(-14px) rotate(3deg); }
    }
    @keyframes bobSlow {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-8px); }
    }
    .float-fish { animation: bob 3.4s ease-in-out infinite; }
    .float-slow { animation: bobSlow 3.8s ease-in-out infinite; }
    @keyframes ripple {
        0% { transform: scale(0.9); opacity: .55; }
        100% { transform: scale(1.8); opacity: 0; }
    }
    .ripple-ring { animation: ripple 2.6s ease-out infinite; }
    .ripple-ring.delay { animation-delay: 1.3s; }
    @media (prefers-reduced-motion: reduce) {
        .float-fish, .float-slow, .ripple-ring { animation: none; }
    }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="flex items-center justify-center px-4 py-10 sm:py-16"
     style="background:
        radial-gradient(60% 50% at 12% 8%, rgba(37,99,235,0.10), transparent 60%),
        radial-gradient(55% 45% at 88% 10%, rgba(22,138,85,0.12), transparent 60%),
        radial-gradient(70% 55% at 50% 105%, rgba(37,99,235,0.08), transparent 60%),
        #f5f6fa;">
    <div class="w-full max-w-2xl text-center">

        <!-- Illustration -->
        <div class="relative mx-auto mb-6 flex h-40 w-40 items-center justify-center sm:h-48 sm:w-48">
            <span class="ripple-ring absolute inset-0 rounded-full border-2 border-blue-200"></span>
            <span class="ripple-ring delay absolute inset-0 rounded-full border-2 border-green-200"></span>
            <div class="relative flex h-28 w-28 items-center justify-center rounded-full bg-gradient-to-br from-blue-600 to-green-600 shadow-[0_20px_45px_rgba(37,99,235,0.35)] sm:h-32 sm:w-32">
                <i class="fa-solid fa-basket-shopping text-4xl text-white sm:text-5xl"></i>
            </div>
            <span class="float-fish absolute -right-1 -top-2 flex h-11 w-11 items-center justify-center rounded-2xl bg-white text-lg text-green-600 shadow-lg sm:h-12 sm:w-12">
                <i class="fa-solid fa-fish-fins"></i>
            </span>
            <span class="float-slow absolute -left-3 bottom-1 flex h-10 w-10 items-center justify-center rounded-2xl bg-white text-base text-amber-500 shadow-lg">
                <i class="fa-solid fa-drumstick-bite"></i>
            </span>
        </div>

        <p class="nunito mx-auto mb-3 inline-flex items-center gap-2 rounded-full border border-amber-200 bg-amber-50 px-4 py-1.5 text-xs font-bold uppercase tracking-[0.14em] text-amber-700">
            <i class="fa-solid fa-triangle-exclamation"></i> 404 — Page Not Found
        </p>

        <h1 class="nunito text-[64px] font-black leading-none text-slate-900 sm:text-[84px]">
            4<span class="bg-gradient-to-r from-blue-600 to-green-600 bg-clip-text text-transparent">0</span>4
        </h1>

        <h2 class="mt-3 text-xl font-extrabold text-slate-900 sm:text-2xl">Oops! This catch got away.</h2>
        <p class="mx-auto mt-3 max-w-md text-sm leading-relaxed text-slate-500 sm:text-[15px]">
            The page you're looking for may have been moved, delivered already, or the link might be incorrect.
            Let's get you back to something fresh.
        </p>

        <div class="mt-7 flex flex-col items-center justify-center gap-3 sm:flex-row">
            <a href="<?php echo e(route('frontend.home')); ?>"
               class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-blue-700 px-6 py-3.5 text-sm font-bold text-white shadow-lg shadow-blue-700/25 transition hover:bg-blue-800 sm:w-auto">
                <i class="fa-solid fa-house"></i> Back to Home
            </a>
            <a href="<?php echo e(route('frontend.products')); ?>"
               class="inline-flex w-full items-center justify-center gap-2 rounded-xl border border-green-200 bg-white px-6 py-3.5 text-sm font-bold text-green-700 shadow-sm transition hover:bg-green-50 sm:w-auto">
                <i class="fa-solid fa-bag-shopping"></i> Continue Shopping
            </a>
        </div>

        <!-- Popular pages -->
        <div class="mx-auto mt-9 max-w-lg rounded-3xl border border-slate-100 bg-white p-5 text-left shadow-[0_20px_45px_rgba(15,23,42,0.06)] sm:p-6">
            <p class="mb-3 flex items-center gap-2 text-xs font-bold uppercase tracking-[0.14em] text-slate-500">
                <i class="fa-solid fa-compass text-blue-600"></i> Try one of these popular pages
            </p>
            <div class="grid grid-cols-2 gap-2 sm:grid-cols-3">
                <a href="<?php echo e(route('frontend.home')); ?>" class="flex items-center gap-2 rounded-xl bg-slate-50 px-3 py-2.5 text-xs font-semibold text-slate-700 transition hover:bg-blue-50 hover:text-blue-700">
                    <i class="fa-solid fa-house text-blue-600"></i> Home
                </a>
                <a href="<?php echo e(route('frontend.products')); ?>" class="flex items-center gap-2 rounded-xl bg-slate-50 px-3 py-2.5 text-xs font-semibold text-slate-700 transition hover:bg-green-50 hover:text-green-700">
                    <i class="fa-solid fa-bag-shopping text-green-600"></i> Shop All
                </a>
                <a href="<?php echo e(route('frontend.cart')); ?>" class="flex items-center gap-2 rounded-xl bg-slate-50 px-3 py-2.5 text-xs font-semibold text-slate-700 transition hover:bg-blue-50 hover:text-blue-700">
                    <i class="fa-solid fa-cart-shopping text-blue-600"></i> Cart
                </a>
                <a href="<?php echo e(route('frontend.wishlist')); ?>" class="flex items-center gap-2 rounded-xl bg-slate-50 px-3 py-2.5 text-xs font-semibold text-slate-700 transition hover:bg-red-50 hover:text-red-600">
                    <i class="fa-regular fa-heart text-red-500"></i> Wishlist
                </a>
                <a href="<?php echo e(route('frontend.orders')); ?>" class="flex items-center gap-2 rounded-xl bg-slate-50 px-3 py-2.5 text-xs font-semibold text-slate-700 transition hover:bg-amber-50 hover:text-amber-700">
                    <i class="fa-solid fa-box text-amber-500"></i> Orders
                </a>
                <a href="<?php echo e(route('frontend.contact')); ?>" class="flex items-center gap-2 rounded-xl bg-slate-50 px-3 py-2.5 text-xs font-semibold text-slate-700 transition hover:bg-green-50 hover:text-green-700">
                    <i class="fa-regular fa-envelope text-green-600"></i> Contact
                </a>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\BMDU Work\FarmSea-dashboard\resources\views/errors/404.blade.php ENDPATH**/ ?>