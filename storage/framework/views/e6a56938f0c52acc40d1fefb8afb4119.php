<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width,initial-scale=1.0"/>
<title><?php echo $__env->yieldContent('title', 'FarmSea'); ?> — Fresh Meat & Seafood</title>
<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Nunito:wght@600;700;800&display=swap" rel="stylesheet">
<style>
* { font-family: 'Poppins', sans-serif; box-sizing: border-box; }
.nunito { font-family: 'Nunito', sans-serif; }
.sidebar-transition { transition: transform .3s ease; }
.sidebar-open { transform: translateX(0) !important; }
#sidebar-overlay { display:none; position:fixed; inset:0; background:rgba(0,0,0,.45); z-index:90; }
#sidebar-overlay.active { display:block; }
/* Cart drawer */
#cart-drawer { transform: translateX(100%); transition: transform .35s cubic-bezier(.4,0,.2,1); }
#cart-drawer.open { transform: translateX(0); }
/* Mobile responsive fixes */
@media (max-width: 640px) {
    .container { padding-left: 12px !important; padding-right: 12px !important; }
}
/* Prevent horizontal overflow */
html, body { overflow-x: hidden; max-width: 100vw; }
/* Scrollbar hide utility */
.scrollbar-hide::-webkit-scrollbar { display: none; }
.scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
.header-nav-dropdown {
    opacity: 0;
    visibility: hidden;
    pointer-events: none;
    transform: translateY(10px);
    transition: opacity .18s ease, transform .18s ease, visibility .18s ease;
}

.header-nav-group::after {
    content: "";
    position: absolute;
    left: 0;
    right: 0;
    top: 100%;
    height: 16px;
}

.header-nav-group:hover .header-nav-dropdown {
    opacity: 1;
    visibility: visible;
    pointer-events: auto;
    transform: translateY(0);
}
</style>
<?php echo $__env->yieldContent('styles'); ?>
</head>
<body class="min-h-screen flex flex-col" style="background:#f5f6fa;">

<div id="sidebar-overlay" onclick="toggleSidebar()"></div>

<!-- Mobile Sidebar -->
<div id="mobile-sidebar" class="fixed inset-y-0 left-0 z-[100] w-72 bg-white shadow-2xl transform -translate-x-full sidebar-transition">
    <div class="p-5 border-b flex justify-between items-center bg-green-700 text-white">
        <span class="font-bold uppercase tracking-wider">FarmSea Menu</span>
        <button onclick="toggleSidebar()"><i class="fa-solid fa-xmark text-2xl"></i></button>
    </div>
    <div class="p-4 overflow-y-auto h-full pb-20">
        <h3 class="text-green-700 font-bold mb-3 mt-2">Non-Veg Fresh</h3>
        <ul class="space-y-1 text-gray-700 text-sm mb-5">
            <li><a href="<?php echo e(route('frontend.products', ['category' => 'Chicken'])); ?>" class="block p-2 hover:bg-gray-100 rounded">Chicken</a></li>
            <li><a href="<?php echo e(route('frontend.products', ['category' => 'Mutton'])); ?>" class="block p-2 hover:bg-gray-100 rounded">Mutton</a></li>
            <li><a href="<?php echo e(route('frontend.products', ['category' => 'Fish'])); ?>" class="block p-2 hover:bg-gray-100 rounded">Fish</a></li>
            <li><a href="<?php echo e(route('frontend.products', ['category' => 'Seafood'])); ?>" class="block p-2 hover:bg-gray-100 rounded">Seafood</a></li>
            <li><a href="<?php echo e(route('frontend.products', ['category' => 'Eggs'])); ?>" class="block p-2 hover:bg-gray-100 rounded">Eggs</a></li>
        </ul>
        <h3 class="text-green-700 font-bold mb-3">Veg Fresh</h3>
        <ul class="space-y-1 text-gray-700 text-sm mb-5">
            <li><a href="<?php echo e(route('frontend.products', ['category' => 'Vegetables'])); ?>" class="block p-2 hover:bg-gray-100 rounded">Vegetables</a></li>
            <li><a href="<?php echo e(route('frontend.products', ['category' => 'Fruits'])); ?>" class="block p-2 hover:bg-gray-100 rounded">Fruits</a></li>
        </ul>
        <hr class="my-4">
        <a href="<?php echo e(route('frontend.track')); ?>" class="block p-2 font-bold text-blue-600">Track Order</a>
        <?php if(auth()->guard('web_frontend')->check()): ?>
            <a href="<?php echo e(route('frontend.profile')); ?>" class="block p-2 font-bold text-green-700">My Profile</a>
            <a href="<?php echo e(route('frontend.orders')); ?>" class="block p-2 font-bold text-green-700">My Orders</a>
        <?php else: ?>
            <a href="<?php echo e(route('frontend.login')); ?>" class="block p-2 font-bold text-blue-600">Login / Register</a>
        <?php endif; ?>
    </div>
</div>

<!-- Cart Drawer -->
<div id="cart-overlay" class="hidden fixed inset-0 bg-black/40 z-[9998]" onclick="closeCart()"></div>
<div id="cart-drawer" class="fixed top-0 right-0 h-full w-[420px] max-w-[95vw] bg-white shadow-2xl z-[9999] flex flex-col">
    <div class="flex items-center justify-between px-5 py-4 border-b">
        <div class="flex items-center gap-2">
            <i class="fa-solid fa-cart-shopping text-blue-700"></i>
            <span class="nunito font-extrabold text-base">Your Cart</span>
            <span id="cart-badge-drawer" class="bg-blue-700 text-white text-xs font-bold px-2 py-0.5 rounded-full">0</span>
        </div>
        <button onclick="closeCart()" class="text-gray-400 hover:text-gray-700 text-xl">&times;</button>
    </div>
    <div id="cart-items-drawer" class="flex-1 overflow-y-auto px-5 py-3 space-y-3">
        <p class="text-center text-gray-400 py-8">Your cart is empty</p>
    </div>
    <div class="px-5 py-4 border-t bg-white">
        <div class="flex justify-between text-sm font-semibold mb-3">
            <span>Subtotal</span>
            <span id="cart-subtotal-drawer">₹0.00</span>
        </div>
        <a href="<?php echo e(route('frontend.checkout')); ?>"
           class="flex items-center justify-center gap-2 w-full bg-blue-700 hover:bg-blue-800 text-white font-bold py-3 rounded-xl text-sm mb-2">
            <i class="fa-solid fa-lock text-xs"></i> Proceed to Checkout
        </a>
        <a href="<?php echo e(route('frontend.cart')); ?>"
           class="flex items-center justify-center gap-2 w-full border border-gray-200 text-blue-700 font-semibold py-2.5 rounded-xl text-sm hover:bg-gray-50">
            View Full Cart
        </a>
    </div>
</div>

<!-- Header -->
<header class="fixed inset-x-0 top-0 z-[9997] bg-white shadow-sm">
    <div class="hidden md:flex bg-blue-900 text-white text-xs py-2 px-8 items-center justify-between font-medium h-8">
        <div class="flex items-center gap-3">
           
            <span>FarmSea Premium Meat & Seafood</span>
        </div>
        <div class="flex gap-6 text-white/90">
            <a href="<?php echo e(route('frontend.track')); ?>" class="hover:text-blue-300 transition">Track Orders</a>
           
        </div>
    </div>
    <div class="w-full px-2 sm:px-3 md:px-4 py-3 flex items-center justify-between gap-4">
        <button onclick="toggleSidebar()" class="text-gray-700 text-xl lg:hidden">
            <i class="fa-solid fa-bars"></i>
        </button>
        <a href="<?php echo e(route('frontend.home')); ?>" class="flex-shrink-0 md:-ml-2">
            <?php if(file_exists(public_path('images/Farmsea.webp'))): ?>
                <img src="<?php echo e(asset('images/Farmsea.webp')); ?>" alt="FarmSea" class="h-10 w-auto object-contain">
            <?php else: ?>
                <span class="text-xl font-extrabold text-green-700"><img src="<?php echo e(asset('images/Farmsea.webp')); ?>"
                     alt="FarmSea"
                     class="h-14 w-auto object-contain"
                     onerror="this.style.display='none'; document.getElementById('logo-fallback').style.display='flex'"></span>
            <?php endif; ?>
        </a>
        <form action="<?php echo e(route('frontend.products')); ?>" method="GET" class="hidden md:flex flex-grow max-w-lg mx-4 relative">
            <input type="text" name="search" placeholder="Search Ready to Cook Items"
                   class="w-full border border-gray-200 rounded-xl px-5 py-2.5 text-sm focus:outline-none focus:border-green-500 transition">
            <button type="submit" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-green-600">
                <i class="fa-solid fa-magnifying-glass"></i>
            </button>
        </form>
        <div class="flex items-center gap-4">
            <a href="<?php echo e(route('frontend.orders')); ?>" class="hidden md:flex flex-col items-center text-center text-gray-700 transition hover:text-blue-700">
                <span class="text-[10px] font-extrabold uppercase tracking-[0.18em] text-gray-400">Returns</span>
                <span class="text-[12px] font-extrabold leading-none mt-1">&amp; Orders</span>
            </a>
            <!-- Account -->
            <div class="relative">
                <button onclick="toggleAccountMenu()" class="text-gray-700 flex flex-col items-center group">
                    <i class="fa-regular fa-user text-xl group-hover:text-blue-600"></i>
                    <span class="text-[10px] font-bold mt-0.5 hidden md:block">
                        <?php if(auth()->guard('web_frontend')->check()): ?> <?php echo e(Str::limit(auth('web_frontend')->user()->name, 12)); ?> <?php else: ?> Sign in/Account <?php endif; ?>
                    </span>
                </button>
                <div id="accountMenu" class="hidden absolute right-0 mt-3 w-56 bg-white rounded-xl shadow-xl border border-gray-100 py-3 z-50">
                    <?php if(auth()->guard('web_frontend')->check()): ?>
                        <a href="<?php echo e(route('frontend.profile')); ?>" class="flex items-center gap-3 px-5 py-2 hover:bg-gray-100 text-sm">
                            <i class="fa-regular fa-user text-gray-500"></i> My Profile
                        </a>
                        <a href="<?php echo e(route('frontend.orders')); ?>" class="flex items-center gap-3 px-5 py-2 hover:bg-gray-100 text-sm">
                            <i class="fa-solid fa-box text-gray-500"></i> My Orders
                        </a>
                        <hr class="my-2">
                        <form method="POST" action="<?php echo e(route('frontend.logout')); ?>">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="w-full flex items-center gap-3 px-5 py-2 text-red-500 hover:bg-red-50 text-sm">
                                <i class="fa-solid fa-right-from-bracket"></i> Logout
                            </button>
                        </form>
                    <?php else: ?>
                        <a href="<?php echo e(route('frontend.login')); ?>" class="flex items-center gap-3 px-5 py-2 hover:bg-gray-100 text-sm">
                            <i class="fa-solid fa-right-to-bracket text-gray-500"></i> Sign In
                        </a>
                        <a href="<?php echo e(route('frontend.register')); ?>" class="flex items-center gap-3 px-5 py-2 hover:bg-gray-100 text-sm">
                            <i class="fa-solid fa-user-plus text-gray-500"></i> Register
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            <!-- Cart -->
            <button onclick="openCart()" class="text-blue-700 relative flex flex-col items-center group">
                <div class="relative">
                    <i class="fa-solid fa-cart-shopping text-xl group-hover:text-blue-800"></i>
                    <span id="header-cart-badge"
                          class="absolute -top-2 -right-2 bg-yellow-500 text-white text-[9px] w-4 h-4 rounded-full flex items-center justify-center font-black border border-white">0</span>
                </div>
                <span class="text-[10px] font-bold mt-0.5 hidden md:block">Cart</span>
            </button>
        </div>
    </div>
    <div class="px-3 pb-3 md:hidden">
        <form action="<?php echo e(route('frontend.products')); ?>" method="GET" class="relative">
            <input type="text" name="search" placeholder="Search fresh items"
                   class="w-full rounded-xl border border-gray-200 px-4 py-2.5 pr-11 text-sm focus:border-green-500 focus:outline-none transition">
            <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-green-600">
                <i class="fa-solid fa-magnifying-glass"></i>
            </button>
        </form>
    </div>
    <!-- Nav strip -->
    <div class="border-t border-gray-100 hidden md:block overflow-visible bg-white">
        <div class="max-w-screen-xl mx-auto px-4 flex items-center gap-1 h-10 text-sm scrollbar-hide overflow-visible">
            <a href="<?php echo e(route('frontend.products')); ?>" class="px-3 py-1.5 bg-gray-100 text-gray-600 font-semibold whitespace-nowrap rounded-md flex-shrink-0">All Products</a>
            <div class="header-nav-group relative mr-2 flex-shrink-0">
                <button type="button" class="inline-flex items-center gap-2 rounded-md bg-green-50 px-3 py-1.5 font-medium text-green-700 transition hover:bg-green-100">
                    Veg Fresh
                    <i class="fa-solid fa-angle-down text-xs"></i>
                </button>
                <div class="header-nav-dropdown absolute left-0 top-full z-[10010] mt-3 min-w-[235px] space-y-1 rounded-2xl border border-green-100 bg-white p-2 shadow-[0_20px_40px_rgba(15,23,42,0.14)]">
                    <a href="<?php echo e(route('frontend.products', ['category' => 'Vegetables', 'search' => 'Leafy'])); ?>" class="block rounded-xl px-4 py-3 text-[15px] font-medium text-gray-700 transition hover:bg-green-50 hover:text-green-700">Leafy Vegetables</a>
                    <a href="<?php echo e(route('frontend.products', ['category' => 'Vegetables', 'search' => 'Root'])); ?>" class="block rounded-xl px-4 py-3 text-[15px] font-medium text-gray-700 transition hover:bg-green-50 hover:text-green-700">Root Vegetables</a>
                    <a href="<?php echo e(route('frontend.products', ['category' => 'Vegetables', 'search' => 'Seasonal'])); ?>" class="block rounded-xl px-4 py-3 text-[15px] font-medium text-gray-700 transition hover:bg-green-50 hover:text-green-700">Seasonal Vegetables</a>
                    <a href="<?php echo e(route('frontend.products', ['category' => 'Vegetables', 'search' => 'Organic'])); ?>" class="block rounded-xl px-4 py-3 text-[15px] font-medium text-gray-700 transition hover:bg-green-50 hover:text-green-700">Organic Vegetables</a>
                    <a href="<?php echo e(route('frontend.products', ['category' => 'Fruits'])); ?>" class="block rounded-xl px-4 py-3 text-[15px] font-medium text-gray-700 transition hover:bg-green-50 hover:text-green-700">Fresh Fruits</a>
                </div>
            </div>
            <div class="header-nav-group relative mr-2 flex-shrink-0">
                <button type="button" class="inline-flex items-center gap-2 rounded-md bg-green-50 px-3 py-1.5 font-medium text-green-700 transition hover:bg-green-100">
                    Non-Veg Fresh
                    <i class="fa-solid fa-angle-down text-xs"></i>
                </button>
                <div class="header-nav-dropdown absolute left-0 top-full z-[10010] mt-3 min-w-[230px] space-y-1 rounded-2xl border border-amber-100 bg-white p-2 shadow-[0_20px_40px_rgba(15,23,42,0.14)]">
                    <a href="<?php echo e(route('frontend.products', ['category' => 'Chicken'])); ?>" class="block rounded-xl px-4 py-3 text-[15px] font-medium text-gray-700 transition hover:bg-amber-50 hover:text-green-700">Chicken</a>
                    <a href="<?php echo e(route('frontend.products', ['category' => 'Mutton'])); ?>" class="block rounded-xl px-4 py-3 text-[15px] font-medium text-gray-700 transition hover:bg-amber-50 hover:text-green-700">Mutton</a>
                    <a href="<?php echo e(route('frontend.products', ['category' => 'Fish'])); ?>" class="block rounded-xl px-4 py-3 text-[15px] font-medium text-gray-700 transition hover:bg-amber-50 hover:text-green-700">Fish</a>
                    <a href="<?php echo e(route('frontend.products', ['category' => 'Eggs'])); ?>" class="block rounded-xl px-4 py-3 text-[15px] font-medium text-gray-700 transition hover:bg-amber-50 hover:text-green-700">Eggs</a>
                    <a href="<?php echo e(route('frontend.products', ['category' => 'Seafood'])); ?>" class="block rounded-xl px-4 py-3 text-[15px] font-medium text-gray-700 transition hover:bg-amber-50 hover:text-green-700">Seafood</a>
                </div>
            </div>
            <span class="w-px h-5 bg-gray-200 mx-1 shrink-0"></span>
            <a href="<?php echo e(route('frontend.products', ['category' => 'Vegetables'])); ?>" class="px-3 py-1.5 text-gray-700 whitespace-nowrap hover:bg-green-50 rounded-md flex-shrink-0">Vegetables</a>
            <a href="<?php echo e(route('frontend.products', ['category' => 'Fruits'])); ?>" class="px-3 py-1.5 text-gray-700 whitespace-nowrap hover:bg-green-50 rounded-md flex-shrink-0">Fruits</a>
            <span class="w-px h-5 bg-gray-200 mx-1 shrink-0"></span>
            <a href="<?php echo e(route('frontend.products', ['category' => 'Chicken'])); ?>" class="px-3 py-1.5 text-gray-700 whitespace-nowrap hover:bg-red-50 rounded-md flex-shrink-0">Chicken</a>
            <a href="<?php echo e(route('frontend.products', ['category' => 'Fish'])); ?>" class="px-3 py-1.5 text-gray-700 whitespace-nowrap hover:bg-red-50 rounded-md flex-shrink-0">Fish</a>
            <a href="<?php echo e(route('frontend.products', ['category' => 'Seafood'])); ?>" class="px-3 py-1.5 text-gray-700 whitespace-nowrap hover:bg-red-50 rounded-md flex-shrink-0">Seafood</a>
            <span class="w-px h-5 bg-gray-200 mx-1 shrink-0"></span>
            <span class="ml-auto"></span>
            <a href="<?php echo e(route('frontend.orders')); ?>" class="inline-flex items-center gap-2 px-3 py-1.5 font-extrabold text-green-700 whitespace-nowrap rounded-md transition hover:bg-green-50">
                <i class="fa-solid fa-rotate-left text-[13px]"></i>
                Reorder
            </a>
            <a href="<?php echo e(route('frontend.products')); ?>" class="px-3 py-1.5 text-slate-700 font-semibold whitespace-nowrap rounded-md transition hover:bg-slate-100">Recommended</a>
        </div>
    </div>
</header>

<div class="pt-[74px] md:pt-[138px]">
    <!-- Flash Messages -->
    <?php if(session('success')): ?>
        <div class="mx-4 mt-3 p-3 bg-green-100 text-green-700 rounded-lg text-sm flex items-center gap-2">
            <i class="fa-solid fa-circle-check"></i> <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>
    <?php if(session('error')): ?>
        <div class="mx-4 mt-3 p-3 bg-red-100 text-red-700 rounded-lg text-sm flex items-center gap-2">
            <i class="fa-solid fa-circle-xmark"></i> <?php echo e(session('error')); ?>

        </div>
    <?php endif; ?>

    <!-- Main Content -->
    <main class="flex-1">
        <?php echo $__env->yieldContent('content'); ?>
    </main>
</div>

<!-- Footer -->
<footer class="relative overflow-hidden bg-[#0f172b] pt-10 md:pt-12 lg:pt-14 pb-5 md:pb-6 lg:pb-7 text-white" style="font-family:'Poppins',sans-serif;">
    <div class="relative z-10 mx-auto max-w-[1220px] px-5 sm:px-6 lg:px-8">
        <div class="grid items-start gap-5 border-b border-white/6 pb-5 lg:grid-cols-[1.03fr_1.37fr] lg:gap-10 lg:pb-7">
            <div class="lg:max-w-[430px]">
                <h4 class="mb-3 text-[14px] font-semibold text-white">About FarmSea</h4>
                <p class="text-[13px] leading-[1.5] text-[#94a7c6]">
                    FarmSea brings farm-fresh chicken, premium mutton, and fresh seafood directly to your doorstep.
                    We ensure hygienic processing, quality cuts, and same-day delivery for the freshest experience.
                </p>
            </div>

            <div class="rounded-[26px] border border-white/6 bg-[#151f33] px-5 py-5 shadow-[inset_0_1px_0_rgba(255,255,255,0.02)] md:px-6">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                    <div class="min-w-0">
                        <h3 class="mb-1 text-[18px] font-semibold leading-none text-white">Fresh Deals in Your Inbox</h3>
                        <p class="text-[10px] font-semibold uppercase tracking-[0.14em] text-[#9ba8bb]">
                            Get offers on chicken, mutton &amp; seafood
                        </p>
                    </div>
                    <div class="flex w-full flex-col gap-2.5 sm:flex-row lg:max-w-[400px]">
                        <input
                            type="email"
                            placeholder="Enter your email"
                            class="h-[46px] w-full rounded-[14px] border border-[#33425f] bg-[#151f33] px-5 text-[13px] text-white placeholder:text-[#8d98ac] focus:border-[#22c55e] focus:outline-none"
                        >
                        <button
                            type="button"
                            aria-label="Subscribe"
                            class="flex h-[46px] w-full items-center justify-center rounded-[14px] bg-[#19b34a] px-5 text-base text-white transition hover:bg-[#24c256] sm:w-[54px] sm:min-w-[54px]"
                        >
                            <i class="fa-solid fa-paper-plane"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-x-6 gap-y-7 py-6 lg:grid-cols-[1fr_1fr_1fr_1fr_180px] lg:gap-x-8 lg:gap-y-6 lg:py-7">
            <div>
                <h4 class="mb-4 text-[12px] font-bold uppercase tracking-[0.22em] text-white">Company</h4>
                <ul class="space-y-3.5">
                    <li><a href="<?php echo e(route('frontend.about')); ?>" class="text-[14px] text-[#9bb0cf] transition hover:text-white">About Us</a></li>
                    <li><a href="<?php echo e(route('frontend.contact')); ?>" class="text-[14px] text-[#9bb0cf] transition hover:text-white">Contact Us</a></li>
                    <li><a href="<?php echo e(route('frontend.farms')); ?>" class="text-[14px] text-[#9bb0cf] transition hover:text-white">Our Farms</a></li>
                    <li><a href="<?php echo e(route('frontend.blog')); ?>" class="text-[14px] text-[#9bb0cf] transition hover:text-white">Blog</a></li>
                </ul>
            </div>

            <div>
                <h4 class="mb-4 text-[12px] font-bold uppercase tracking-[0.22em] text-white">Support</h4>
                <ul class="space-y-3.5">
                    <li><a href="<?php echo e(route('frontend.contact')); ?>" class="text-[14px] text-[#9bb0cf] transition hover:text-white">Help Center</a></li>
                    <li><a href="<?php echo e(route('frontend.track')); ?>" class="text-[14px] text-[#9bb0cf] transition hover:text-white">Track Order</a></li>
                    <li><a href="<?php echo e(route('frontend.returns')); ?>" class="text-[14px] text-[#9bb0cf] transition hover:text-white">Returns &amp; Refunds</a></li>
                    <li><a href="<?php echo e(route('frontend.shipping')); ?>" class="text-[14px] text-[#9bb0cf] transition hover:text-white">Shipping Info</a></li>
                </ul>
            </div>

            <div>
                <h4 class="mb-4 text-[12px] font-bold uppercase tracking-[0.22em] text-white">Categories</h4>
                <ul class="space-y-3.5">
                    <li><a href="<?php echo e(route('frontend.products', ['category' => 'Chicken'])); ?>" class="text-[14px] text-[#9bb0cf] transition hover:text-white">Chicken</a></li>
                    <li><a href="<?php echo e(route('frontend.products', ['category' => 'Mutton'])); ?>" class="text-[14px] text-[#9bb0cf] transition hover:text-white">Mutton</a></li>
                    <li><a href="<?php echo e(route('frontend.products', ['category' => 'Fish'])); ?>" class="text-[14px] text-[#9bb0cf] transition hover:text-white">Fish &amp; Seafood</a></li>
                    <li><a href="<?php echo e(route('frontend.products')); ?>" class="text-[14px] text-[#9bb0cf] transition hover:text-white">Ready to Cook</a></li>
                </ul>
            </div>

            <div>
                <h4 class="mb-4 text-[12px] font-bold uppercase tracking-[0.22em] text-white">Reach Us</h4>
                <p class="mb-3 text-[14px] text-[#9bb0cf]">Delhi NCR, India</p>
                <a href="tel:+919876543210" class="mb-2 block text-[15px] font-semibold text-white transition hover:text-[#26c95a]">+91 98765 43210</a>
                <a href="mailto:support@farmsea.com" class="mb-4 block text-[14px] text-[#9bb0cf] transition hover:text-white">support@farmsea.com</a>
                <div class="flex items-center gap-3.5 text-[15px] text-[#9bb0cf]">
                    <a href="#" class="transition hover:text-white" aria-label="WhatsApp"><i class="fa-brands fa-whatsapp"></i></a>
                    <a href="#" class="transition hover:text-white" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a>
                </div>
            </div>

            <div class="col-span-2 lg:col-span-1">
                <div class="flex h-full min-h-[140px] flex-col items-center justify-center rounded-[14px] border border-[#175f49] bg-[#112d35] px-5 py-6 text-center">
                    <i class="fa-solid fa-drumstick-bite mb-3 text-[22px] text-[#1dd15a]"></i>
                    <h5 class="text-[13px] font-bold uppercase leading-none text-white">Fresh &amp; Hygienic</h5>
                    <p class="mt-2 text-[12px] text-[#8ba7ba]">Farm to Home Delivery</p>
                </div>
            </div>
        </div>

        <div class="flex flex-col gap-4 border-t border-white/6 pt-4 text-center sm:pt-5 md:flex-row md:items-center md:justify-between md:text-left">
            <p class="text-[10px] uppercase tracking-[0.2em] text-[#84a0c3]">
                &copy; <?php echo e(date('Y')); ?> FarmSea - Fresh Meat &amp; Seafood Delivered
            </p>
            <div class="flex items-center justify-center gap-4 md:justify-end">
                <img src="https://upload.wikimedia.org/wikipedia/commons/5/5e/Visa_Inc._logo.svg" class="h-3 opacity-70" alt="Visa">
                <img src="https://upload.wikimedia.org/wikipedia/commons/2/2a/Mastercard-logo.svg" class="h-5 opacity-70" alt="Mastercard">
                <img src="https://upload.wikimedia.org/wikipedia/commons/b/b5/PayPal.svg" class="h-4 opacity-70" alt="PayPal">
            </div>
        </div>
    </div>
</footer>

<script>
// Sidebar
function toggleSidebar() {
    document.getElementById('mobile-sidebar').classList.toggle('sidebar-open');
    document.getElementById('sidebar-overlay').classList.toggle('active');
}
// Account menu
function toggleAccountMenu() {
    document.getElementById('accountMenu').classList.toggle('hidden');
}
document.addEventListener('click', function(e) {
    const menu = document.getElementById('accountMenu');
    if (menu && !menu.contains(e.target) && !e.target.closest('[onclick="toggleAccountMenu()"]')) {
        menu.classList.add('hidden');
    }
});
// Cart drawer
function openCart() {
    document.getElementById('cart-overlay').classList.remove('hidden');
    document.getElementById('cart-drawer').classList.add('open');
    document.body.style.overflow = 'hidden';
    refreshCartDrawer();
}
function closeCart() {
    document.getElementById('cart-overlay').classList.add('hidden');
    document.getElementById('cart-drawer').classList.remove('open');
    document.body.style.overflow = '';
}

function showCartAddedAlert(message = 'Product added to cart successfully.') {
    alert(message);
}
// Add to cart (AJAX)
function addToCart(productId, variantIndex) {
    fetch('<?php echo e(route("frontend.cart.add")); ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        },
        body: JSON.stringify({ product_id: productId, quantity: 1, variant_index: variantIndex ?? null })
    })
    .then(r => r.json())
    .then(data => {
        if (!data.success) {
            alert(data.message || 'This product is currently unavailable.');
            return;
        }

        document.getElementById('header-cart-badge').textContent = data.cart_count;
        showCartAddedAlert('Product cart me add ho gaya.');
    });
}
function formatCartCurrency(amount) {
    return 'Rs' + Number(amount || 0).toFixed(2);
}
function renderCartDrawerItems(items) {
    const container = document.getElementById('cart-items-drawer');

    if (!items || !items.length) {
        container.innerHTML = '<p class="text-center text-gray-400 py-8">Your cart is empty</p>';
        return;
    }

    container.innerHTML = items.map((item) => `
        <div class="rounded-2xl border border-gray-100 bg-white p-3 shadow-sm">
            <div class="flex gap-3">
                <a href="${item.product_url}" class="h-20 w-20 overflow-hidden rounded-xl bg-gray-100 flex-shrink-0">
                    ${item.image_url
                        ? `<img src="${item.image_url}" alt="${item.name}" class="h-full w-full object-cover">`
                        : '<div class="flex h-full w-full items-center justify-center text-2xl text-gray-300"><i class="fa-solid fa-drumstick-bite"></i></div>'}
                </a>
                <div class="min-w-0 flex-1">
                    <div class="flex items-start justify-between gap-3">
                        <a href="${item.product_url}" class="line-clamp-2 text-sm font-bold leading-5 text-slate-900 hover:text-blue-700">
                            ${item.name}
                        </a>
                        <button onclick="removeCartDrawerItem('${item.key}')" class="flex h-7 w-7 items-center justify-center rounded-full text-gray-400 transition hover:bg-red-50 hover:text-red-500">
                            <i class="fa-solid fa-xmark text-xs"></i>
                        </button>
                    </div>
                    <p class="mt-1 text-[11px] font-semibold uppercase tracking-[0.14em] text-slate-400">
                        ${item.variant_label || item.unit || 'Fresh Cut'}
                    </p>
                    <div class="mt-3 flex items-end justify-between gap-3">
                        <div>
                            <p class="text-sm font-black text-slate-900">${formatCartCurrency(item.price)}</p>
                            <div class="mt-2 inline-flex items-center overflow-hidden rounded-lg border border-gray-200 bg-white">
                                <button onclick="updateCartDrawerQty('${item.key}', ${item.quantity - 1})" class="flex h-8 w-8 items-center justify-center text-gray-500 transition hover:bg-gray-50 hover:text-red-500">
                                    <i class="fa-solid fa-minus text-[10px]"></i>
                                </button>
                                <span class="flex h-8 min-w-[34px] items-center justify-center border-x border-gray-200 px-2 text-xs font-bold text-slate-700">
                                    ${item.quantity}
                                </span>
                                <button onclick="updateCartDrawerQty('${item.key}', ${item.quantity + 1})" class="flex h-8 w-8 items-center justify-center text-gray-500 transition hover:bg-gray-50 hover:text-green-600">
                                    <i class="fa-solid fa-plus text-[10px]"></i>
                                </button>
                            </div>
                        </div>
                        <p class="text-sm font-extrabold text-blue-700">${formatCartCurrency(item.subtotal)}</p>
                    </div>
                </div>
            </div>
        </div>
    `).join('');
}
function removeCartDrawerItem(key) {
    fetch('<?php echo e(route("frontend.cart.remove")); ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        },
        body: JSON.stringify({ key })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            refreshCartDrawer();
        }
    });
}
function updateCartDrawerQty(key, quantity) {
    fetch('<?php echo e(route("frontend.cart.update")); ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        },
        body: JSON.stringify({ key, quantity })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            refreshCartDrawer();
        }
    });
}
// Refresh cart drawer
function refreshCartDrawer() {
    fetch('<?php echo e(route("frontend.cart.count")); ?>')
        .then(r => r.json())
        .then(data => {
            document.getElementById('header-cart-badge').textContent = data.count;
            document.getElementById('cart-badge-drawer').textContent = data.count + ' item' + (data.count !== 1 ? 's' : '');
            document.getElementById('cart-subtotal-drawer').textContent = formatCartCurrency(data.subtotal);
            renderCartDrawerItems(data.items || []);
        });
}
// Init badge
refreshCartDrawer();
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeCart(); });
</script>
<?php echo $__env->yieldContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\FarmSea-dashboard\resources\views/frontend/layouts/app.blade.php ENDPATH**/ ?>