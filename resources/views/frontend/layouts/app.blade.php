<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width,initial-scale=1.0"/>
<title>@yield('title', 'FarmSea') — Fresh Meat & Seafood</title>
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="icon" type="image/webp" href="{{ $brandLogoUrl }}">
<link rel="shortcut icon" href="{{ $brandLogoUrl }}">
<link rel="apple-touch-icon" href="{{ $brandLogoUrl }}">
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
    #cart-drawer { width: 100%; max-width: 100vw; }
    #cart-drawer > div { padding-left: 14px; padding-right: 14px; }
    #accountMenu { position: fixed; top: 64px; right: 10px; width: min(14rem, calc(100vw - 20px)); }
}
@media (min-width: 1024px) {
    #mobile-sidebar { display: none !important; }
    #sidebar-overlay { display: none !important; }
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
.announcement-track {
    display: flex;
    width: max-content;
    animation: announcement-scroll 24s linear infinite;
}
.announcement-track:hover { animation-play-state: paused; }
@keyframes announcement-scroll {
    from { transform: translateX(0); }
    to { transform: translateX(-50%); }
}
@media (prefers-reduced-motion: reduce) {
    .announcement-track { animation: none; }
}
</style>
@yield('styles')
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
        <h3 class="text-green-700 font-bold mb-3 mt-2">Categories</h3>
        <ul class="space-y-1 text-gray-700 text-sm mb-5">
            @forelse($frontendNavCategories ?? collect() as $category)
                <li>
                    @if($category->children->isNotEmpty())
                        <button
                            type="button"
                            onclick="toggleMobileCategory('{{ $category->id }}')"
                            class="flex w-full items-center justify-between rounded p-2 text-left font-semibold hover:bg-gray-100"
                        >
                            <span>{{ $category->name }}</span>
                            <i id="mobile-category-icon-{{ $category->id }}" class="fa-solid fa-chevron-down text-[10px] text-green-700 transition-transform"></i>
                        </button>
                        <ul id="mobile-category-{{ $category->id }}" class="ml-3 hidden border-l border-green-100 pl-2">
                            <li>
                                <a href="{{ route('frontend.products', ['category' => $category->slug]) }}" class="block rounded p-2 text-xs font-semibold text-green-700 hover:bg-gray-100">
                                    View all {{ $category->name }}
                                </a>
                            </li>
                            @foreach($category->children as $subcategory)
                                <li>
                                    <a href="{{ route('frontend.products', ['category' => $category->slug, 'subcategory' => $subcategory->slug]) }}" class="block p-2 text-xs text-gray-500 hover:bg-gray-100 hover:text-green-700 rounded">{{ $subcategory->name }}</a>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <a href="{{ route('frontend.products', ['category' => $category->slug]) }}" class="block p-2 font-semibold hover:bg-gray-100 rounded">{{ $category->name }}</a>
                    @endif
                </li>
            @empty
                <li class="p-2 text-gray-400">No categories available</li>
            @endforelse
        </ul>
        <hr class="my-4">
        <a href="{{ route('frontend.track') }}" class="block p-2 font-bold text-blue-600">Track Order</a>
        @auth('web_frontend')
            <a href="{{ route('frontend.profile') }}" class="block p-2 font-bold text-green-700">My Profile</a>
            <a href="{{ route('frontend.orders') }}" class="block p-2 font-bold text-green-700">My Orders</a>
        @else
            <a href="{{ route('frontend.login') }}" class="block p-2 font-bold text-blue-600">Login / Register</a>
        @endauth
    </div>
</div>

@php
    $drawerCart = session('cart', []);
    $drawerCartDays = collect($drawerCart)->pluck('pricing_day')->filter();
    $drawerUsesTomorrowDelivery = $drawerCartDays->contains('tomorrow') && ! $drawerCartDays->contains('today');
    $drawerDeliveryDayLabel = $drawerUsesTomorrowDelivery ? 'Tomorrow' : 'Today';
    $drawerDeliverySlotOptions = $drawerUsesTomorrowDelivery
        ? \App\Support\DeliverySlotManager::options()
        : \App\Support\DeliverySlotManager::availableOptions();
    $drawerSelectedDeliverySlot = session('selected_delivery_slot');

    if (! in_array($drawerSelectedDeliverySlot, array_column($drawerDeliverySlotOptions, 'value'), true)) {
        $drawerSelectedDeliverySlot = $drawerDeliverySlotOptions[0]['value'] ?? null;
        session(['selected_delivery_slot' => $drawerSelectedDeliverySlot]);
    }
@endphp
<!-- Cart Drawer -->
<div id="cart-overlay" class="hidden fixed inset-0 bg-black/40 z-[9998]" onclick="closeCart()"></div>
<div id="cart-drawer" class="fixed top-0 right-0 h-full w-[420px] max-w-[95vw] bg-white shadow-2xl z-[9999] flex flex-col">
    <div class="flex items-center justify-between px-5 py-4 border-b">
        <div class="flex items-center gap-2">
            <i class="fa-solid fa-cart-shopping text-blue-700"></i>
            <span class="nunito font-extrabold text-base">Your Cart</span>
            <span id="cart-badge-drawer" class="bg-blue-700 text-white text-xs font-bold px-2 py-0.5 rounded-full">0</span>
            <div id="cart-day-tabs" class="hidden items-center rounded-full bg-slate-100 p-1">
                <button type="button" data-cart-day-tab="today" onclick="selectCartDrawerDay('today')" class="rounded-full px-2.5 py-1 text-[10px] font-black uppercase">Today</button>
                <button type="button" data-cart-day-tab="tomorrow" onclick="selectCartDrawerDay('tomorrow')" class="rounded-full px-2.5 py-1 text-[10px] font-black uppercase">Tomorrow</button>
            </div>
        </div>
        <button onclick="closeCart()" class="text-gray-400 hover:text-gray-700 text-xl">&times;</button>
    </div>
    <div id="cart-items-drawer" class="flex-1 overflow-y-auto px-5 py-3 space-y-3">
        <p class="text-center text-gray-400 py-8">Your cart is empty</p>
    </div>
    <div class="px-5 py-4 border-t bg-white">
        <div id="cart-coupon-panel" class="mb-3 hidden rounded-2xl border border-blue-100 bg-blue-50/60 p-3"></div>
        <div class="mb-4 rounded-2xl border border-green-100 bg-green-50/60 p-3">
            <div class="mb-2 flex items-center justify-between gap-3">
                <span class="text-xs font-black uppercase tracking-[0.16em] text-slate-600">
                    Delivery Slot
                    <span id="drawer-delivery-day-label" class="ml-1 rounded-full bg-white px-2 py-1 text-[9px] tracking-[0.12em] text-green-700">
                        {{ $drawerDeliveryDayLabel }}
                    </span>
                </span>
                <span id="drawer-delivery-slot-label" class="text-[10px] font-bold text-green-700">
                    {{ \App\Support\DeliverySlotManager::label($drawerSelectedDeliverySlot) }}
                </span>
            </div>
            <div id="drawer-delivery-slot-options" class="flex flex-wrap gap-2">
                @forelse($drawerDeliverySlotOptions as $slot)
                    <button
                        type="button"
                        onclick="updateCartDrawerDeliverySlot('{{ $slot['value'] }}')"
                        data-drawer-delivery-slot="{{ $slot['value'] }}"
                        class="drawer-delivery-slot-chip {{ $drawerSelectedDeliverySlot === $slot['value'] ? 'border-green-600 bg-white text-green-700 shadow-sm' : 'border-green-100 bg-white/80 text-slate-600' }} rounded-full border px-2.5 py-2 text-[10px] font-bold leading-none transition hover:border-green-400 hover:text-green-700"
                    >
                        {{ $slot['label'] }}
                    </button>
                @empty
                    <span class="rounded-full border border-amber-200 bg-amber-50 px-2.5 py-2 text-[10px] font-bold leading-none text-amber-700">
                        No slots left for today
                    </span>
                @endforelse
            </div>
        </div>
        <div class="flex justify-between text-sm font-semibold mb-2">
            <span>Subtotal</span>
            <span id="cart-subtotal-drawer">₹0.00</span>
        </div>
        <div id="cart-delivery-charge-row" class="mb-2 flex justify-between text-xs text-slate-500">
            <span>Delivery</span>
            <span id="cart-delivery-charge-drawer">Rs0.00</span>
        </div>
        <div id="cart-service-charge-row" class="mb-2 flex justify-between text-xs text-slate-500">
            <span id="cart-service-charge-label">&#8505;&#65039; Service Charge</span>
            <span id="cart-service-charge-drawer">Rs0.00</span>
        </div>
        <div id="cart-discount-row" class="mb-2 hidden justify-between text-xs font-bold text-emerald-600">
            <span>Discount</span>
            <span id="cart-discount-drawer">-Rs0.00</span>
        </div>
        <div class="mb-3 flex justify-between text-sm font-bold text-slate-900">
            <span>Total</span>
            <span id="cart-total-drawer">Rs0.00</span>
        </div>
        <a id="cart-checkout-link" href="{{ route('frontend.checkout') }}"
           class="flex items-center justify-center gap-2 w-full bg-blue-700 hover:bg-blue-800 text-white font-bold py-3 rounded-xl text-sm mb-2">
            <i class="fa-solid fa-lock text-xs"></i> Proceed to Checkout
        </a>
        <a href="{{ route('frontend.cart') }}"
           class="flex items-center justify-center gap-2 w-full border border-gray-200 text-blue-700 font-semibold py-2.5 rounded-xl text-sm hover:bg-gray-50">
            View Full Cart
        </a>
    </div>
</div>

<!-- Header -->
<header class="fixed inset-x-0 top-0 z-[9997] bg-white shadow-sm">
    <div class="hidden h-8 overflow-hidden bg-blue-900 text-white md:flex md:items-center">
        <div class="announcement-track items-center whitespace-nowrap text-[11px] font-bold">
            @for($copy = 0; $copy < 2; $copy++)
                <div class="flex items-center gap-10 pr-10">
                    <span><i class="fa-solid fa-leaf mr-2 text-green-300"></i>Farm Fresh. Ocean Fresh. Delivered to Your Door.</span>
                    @if($frontendTickerOffer ?? null)
                        <a href="{{ route('frontend.products', ['offer' => 'flash_deal']) }}" class="text-yellow-300 hover:text-yellow-200">
                            <i class="fa-solid fa-bolt mr-2"></i>{{ $frontendTickerOffer->title ?: $frontendTickerOffer->description ?: 'Special offer available now' }}
                        </a>
                    @else
                        <span class="text-yellow-300"><i class="fa-solid fa-bolt mr-2"></i>Fresh deals available every day</span>
                    @endif
                    @if($frontendTickerProduct ?? null)
                        <a href="{{ route('frontend.product.show', $frontendTickerProduct->slug) }}" class="hover:text-green-200">
                            <i class="fa-solid fa-star mr-2 text-yellow-300"></i>New Arrival: {{ $frontendTickerProduct->name }} — Shop Now
                        </a>
                    @endif
                </div>
            @endfor
        </div>
    </div>
    <div class="w-full px-3 py-2.5 sm:px-3 md:px-4 md:py-3 flex items-center justify-between gap-2 md:gap-4">
        <button onclick="toggleSidebar()" aria-label="Open menu" class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-lg text-gray-700 text-lg lg:hidden">
            <i class="fa-solid fa-bars"></i>
        </button>
        <a href="{{ route('frontend.home') }}" class="flex-shrink-0 md:ml-[100px] flex items-center">
            <img src="{{ $brandLogoUrl }}"
                 alt="FarmSea"
                 class="h-10 w-auto object-contain sm:h-12 md:h-20"
                 onerror="this.style.display='none'; document.getElementById('header-logo-fallback').style.display='inline-flex'">
            <span id="header-logo-fallback" class="hidden text-xl font-extrabold text-green-700">FarmSea</span>
        </a>
        <form action="{{ route('frontend.products') }}" method="GET" class="hidden md:flex flex-grow max-w-lg mx-4 relative">
            <input type="text" name="search" placeholder="Search Ready to Cook Items"
                   class="w-full border border-gray-200 rounded-xl px-5 py-2.5 text-sm focus:outline-none focus:border-green-500 transition">
            <button type="submit" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-green-600">
                <i class="fa-solid fa-magnifying-glass"></i>
            </button>
        </form>
        <div class="flex items-center gap-3 md:gap-4 md:pr-7">
            <!-- Account -->
            <div class="relative">
                @auth('web_frontend')
                    @php
                        $frontendTotalNotifications = \App\Models\Notification::query()
                            ->forUser(auth('web_frontend')->id())
                            ->count();
                        $frontendUnreadNotifications = \App\Models\Notification::query()
                            ->where('recipient_id', auth('web_frontend')->id())
                            ->whereNull('read_at')
                            ->count();
                    @endphp
                @endauth
                <button onclick="toggleAccountMenu()" class="text-gray-700 flex flex-col items-center group">
                    <span class="relative">
                        <i class="fa-regular fa-user text-xl group-hover:text-blue-600"></i>
                        @auth('web_frontend')
                            @if($frontendUnreadNotifications > 0)
                                <span class="absolute -right-2 -top-2 flex h-4 min-w-4 items-center justify-center rounded-full bg-red-500 px-1 text-[9px] font-black leading-none text-white ring-2 ring-white">
                                    {{ $frontendUnreadNotifications > 99 ? '99+' : $frontendUnreadNotifications }}
                                </span>
                            @endif
                        @endauth
                    </span>
                    <span class="text-[10px] font-bold mt-0.5 hidden md:block">
                        @auth('web_frontend') Hello, {{ Str::limit(auth('web_frontend')->user()->name, 12) }} @else Sign in/Account @endauth
                    </span>
                </button>
                <div id="accountMenu" class="hidden absolute right-0 mt-3 w-56 bg-white rounded-xl shadow-xl border border-gray-100 py-3 z-50">
                    @auth('web_frontend')
                        <a href="{{ route('frontend.profile') }}" class="flex items-center gap-3 px-5 py-2 hover:bg-gray-100 text-sm">
                            <i class="fa-regular fa-user text-gray-500"></i> My Profile
                        </a>
                        <a href="{{ route('frontend.profile') }}#notifications-panel" class="flex items-center gap-3 px-5 py-2 hover:bg-gray-100 text-sm">
                            <i class="fa-regular fa-bell text-gray-500"></i>
                            Notifications
                            @if($frontendTotalNotifications > 0)
                                <span class="ml-auto rounded-full {{ $frontendUnreadNotifications > 0 ? 'bg-red-500 text-white' : 'bg-slate-100 text-slate-600' }} px-2 py-0.5 text-[10px] font-bold">
                                    {{ $frontendTotalNotifications > 99 ? '99+' : $frontendTotalNotifications }}
                                </span>
                            @endif
                        </a>
                        <a href="{{ route('frontend.orders') }}" class="flex items-center gap-3 px-5 py-2 hover:bg-gray-100 text-sm">
                            <i class="fa-solid fa-box text-gray-500"></i> My Orders
                        </a>
                        <hr class="my-2">
                        <form method="POST" action="{{ route('frontend.logout') }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-3 px-5 py-2 text-red-500 hover:bg-red-50 text-sm">
                                <i class="fa-solid fa-right-from-bracket"></i> Logout
                            </button>
                        </form>
                    @else
                        <a href="{{ route('frontend.login') }}" class="flex items-center gap-3 px-5 py-2 hover:bg-gray-100 text-sm">
                            <i class="fa-solid fa-right-to-bracket text-gray-500"></i> Sign In
                        </a>
                        <a href="{{ route('frontend.register') }}" class="flex items-center gap-3 px-5 py-2 hover:bg-gray-100 text-sm">
                            <i class="fa-solid fa-user-plus text-gray-500"></i> Register
                        </a>
                    @endauth
                </div>
            </div>
            <!-- Wishlist -->
            @php
                $initialWishlist = session('wishlist', []);
                $initialWishlistCount = count($initialWishlist);
            @endphp
            <a href="{{ route('frontend.wishlist') }}" class="text-gray-700 relative flex flex-col items-center group transition hover:text-red-500">
                <div class="relative">
                    <i class="fa-regular fa-heart text-xl group-hover:text-red-500 transition"></i>
                    <span id="header-wishlist-badge"
                          class="absolute -top-2 -right-2 bg-red-500 text-white text-[9px] w-4 h-4 rounded-full flex items-center justify-center font-black border border-white {{ $initialWishlistCount > 0 ? '' : 'hidden' }}">
                        {{ $initialWishlistCount }}
                    </span>
                </div>
                <span class="text-[10px] font-bold mt-0.5 hidden md:block">Wishlist</span>
            </a>
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
    <div class="px-3 pb-2.5 md:hidden">
        <form action="{{ route('frontend.products') }}" method="GET" class="relative">
            <input type="text" name="search" placeholder="Search fresh items"
                   class="w-full rounded-xl border border-gray-200 px-3.5 py-2 pr-10 text-xs focus:border-green-500 focus:outline-none transition">
            <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-green-600">
                <i class="fa-solid fa-magnifying-glass"></i>
            </button>
        </form>
    </div>
    <!-- Nav strip -->
    <div class="border-t border-gray-100 hidden md:block overflow-visible bg-white">
        <div class="max-w-screen-xl mx-auto px-4 flex items-center gap-1 h-10 text-sm scrollbar-hide overflow-visible">
            <a href="{{ route('frontend.products') }}" class="px-3 py-1.5 bg-gray-100 text-gray-600 font-semibold whitespace-nowrap rounded-md flex-shrink-0">All Products</a>
            @foreach($frontendNavCategories ?? collect() as $category)
                @if($category->children->isNotEmpty())
                    <div class="header-nav-group relative mr-2 flex-shrink-0">
                        <a href="{{ route('frontend.products', ['category' => $category->slug]) }}" class="inline-flex items-center gap-2 rounded-md bg-green-50 px-3 py-1.5 font-medium text-green-700 transition hover:bg-green-100">
                            {{ $category->name }}
                            <i class="fa-solid fa-angle-down text-xs"></i>
                        </a>
                        <div class="header-nav-dropdown absolute left-0 top-full z-[10010] mt-3 min-w-[230px] space-y-1 rounded-2xl border border-green-100 bg-white p-2 shadow-[0_20px_40px_rgba(15,23,42,0.14)]">
                            <a href="{{ route('frontend.products', ['category' => $category->slug]) }}" class="block rounded-xl px-4 py-3 text-[15px] font-semibold text-green-700 transition hover:bg-green-50">All {{ $category->name }}</a>
                            @foreach($category->children as $subcategory)
                                <a href="{{ route('frontend.products', ['category' => $category->slug, 'subcategory' => $subcategory->slug]) }}" class="block rounded-xl px-4 py-3 text-[15px] font-medium text-gray-700 transition hover:bg-green-50 hover:text-green-700">{{ $subcategory->name }}</a>
                            @endforeach
                        </div>
                    </div>
                @else
                    <a href="{{ route('frontend.products', ['category' => $category->slug]) }}" class="px-3 py-1.5 text-gray-700 whitespace-nowrap hover:bg-green-50 rounded-md flex-shrink-0">{{ $category->name }}</a>
                @endif
            @endforeach
            <span class="ml-auto"></span>
            <a href="{{ route('frontend.orders') }}" class="inline-flex items-center gap-2 px-3 py-1.5 font-extrabold text-green-700 whitespace-nowrap rounded-md transition hover:bg-green-50">
                <i class="fa-solid fa-rotate-left text-[13px]"></i>
                view order
            </a>
            <a href="{{ route('frontend.products', ['sort' => 'latest']) }}" class="px-3 py-1.5 text-slate-700 font-semibold whitespace-nowrap rounded-md transition hover:bg-slate-100">Latest</a>
        </div>
    </div>
</header>

<div class="pt-[103px] md:pt-[178px]">
    <!-- Flash Messages -->
    @if(session('success'))
        <div data-auto-dismiss="3000" class="mx-4 mt-3 flex items-center gap-2 rounded-lg bg-green-100 p-3 text-sm text-green-700 transition-all duration-500">
            <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div data-auto-dismiss="3000" class="mx-4 mt-3 flex items-center gap-2 rounded-lg bg-red-100 p-3 text-sm text-red-700 transition-all duration-500">
            <i class="fa-solid fa-circle-xmark"></i> {{ session('error') }}
        </div>
    @endif

    <!-- Main Content -->
    <main class="flex-1">
        @yield('content')
    </main>
</div>

<!-- Footer -->
<footer class="relative overflow-hidden bg-[#0f172b] pt-10 md:pt-12 lg:pt-14 pb-5 md:pb-6 lg:pb-7 text-white" style="font-family:'Poppins',sans-serif;">
    <div class="relative z-10 mx-auto max-w-[1220px] px-5 sm:px-6 lg:px-8">
        <div class="border-b border-white/6 pb-5 lg:pb-7">
            <div class="max-w-[430px]">
                <h4 class="mb-3 text-[14px] font-semibold text-white">About FarmSea</h4>
                <p class="text-[13px] leading-[1.5] text-[#94a7c6]">
                    FarmSea brings farm-fresh chicken, premium mutton, and fresh seafood directly to your doorstep.
                    We ensure hygienic processing, quality cuts, and same-day delivery for the freshest experience.
                </p>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-x-6 gap-y-7 py-6 lg:grid-cols-[1fr_1fr_1fr_1fr_180px] lg:gap-x-8 lg:gap-y-6 lg:py-7">
            <div>
                <h4 class="mb-4 text-[12px] font-bold uppercase tracking-[0.22em] text-white">Info</h4>
                <ul class="space-y-3.5">
                    <li><a href="{{ route('frontend.about') }}" class="text-[14px] text-[#9bb0cf] transition hover:text-white">About Us</a></li>
                    <li><a href="{{ route('frontend.contact') }}" class="text-[14px] text-[#9bb0cf] transition hover:text-white">Contact Us</a></li>
                    <li><a href="{{ route('frontend.about') }}#faq" class="text-[14px] text-[#9bb0cf] transition hover:text-white">FAQ</a></li>
                </ul>
            </div>

            <div>
                <h4 class="mb-4 text-[12px] font-bold uppercase tracking-[0.22em] text-white">Useful Links</h4>
                <ul class="space-y-3.5">
                    <li><a href="{{ route('frontend.contact') }}" class="text-[14px] text-[#9bb0cf] transition hover:text-white">Help Center</a></li>
                    <li><a href="{{ route('frontend.shipping') }}" class="text-[14px] text-[#9bb0cf] transition hover:text-white">Shipping Info</a></li>
                    <li><a href="{{ route('frontend.returns') }}" class="text-[14px] text-[#9bb0cf] transition hover:text-white">Return Policy</a></li>
                </ul>
            </div>

            <div>
                <h4 class="mb-4 text-[12px] font-bold uppercase tracking-[0.22em] text-white">Categories</h4>
                <ul class="space-y-3.5">
                    <li><a href="{{ route('frontend.products', ['category' => 'Chicken']) }}" class="text-[14px] text-[#9bb0cf] transition hover:text-white">Chicken</a></li>
                    <li><a href="{{ route('frontend.products', ['category' => 'Mutton']) }}" class="text-[14px] text-[#9bb0cf] transition hover:text-white">Mutton</a></li>
                    <li><a href="{{ route('frontend.products', ['category' => 'Fish']) }}" class="text-[14px] text-[#9bb0cf] transition hover:text-white">Fish &amp; Seafood</a></li>
                    <li><a href="{{ route('frontend.products') }}" class="text-[14px] text-[#9bb0cf] transition hover:text-white">Ready to Cook</a></li>
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
                &copy; {{ date('Y') }} FarmSea - Fresh Meat &amp; Seafood Delivered
            </p>
            <div class="flex min-h-7 flex-wrap items-center justify-center gap-4 md:justify-end">
                <span class="inline-flex h-7 w-[58px] items-center justify-center rounded bg-white px-2 shadow-sm ring-1 ring-white/20" aria-label="Visa">
                    <span class="font-sans text-[18px] font-black italic leading-none tracking-[-0.02em] text-[#1434cb]">VISA</span>
                </span>
                <img src="https://upload.wikimedia.org/wikipedia/commons/2/2a/Mastercard-logo.svg" class="block h-6 w-[78px] object-contain opacity-80" alt="Mastercard">
                <img src="https://upload.wikimedia.org/wikipedia/commons/f/f2/Google_Pay_Logo.svg" class="block h-6 w-[72px] object-contain opacity-90" alt="Google Pay">
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

function toggleMobileCategory(categoryId) {
    const panel = document.getElementById(`mobile-category-${categoryId}`);
    const icon = document.getElementById(`mobile-category-icon-${categoryId}`);

    if (!panel) {
        return;
    }

    panel.classList.toggle('hidden');
    icon?.classList.toggle('rotate-180');
}
// Account menu
function toggleAccountMenu() {
    document.getElementById('accountMenu').classList.toggle('hidden');
}
document.querySelectorAll('[data-auto-dismiss]').forEach((notice) => {
    const delay = Number(notice.dataset.autoDismiss || 3000);

    window.setTimeout(() => {
        notice.classList.add('opacity-0', '-translate-y-2');

        window.setTimeout(() => {
            notice.remove();
        }, 500);
    }, delay);
});

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

let cartToastTimeout;

function showCartAddedAlert(message = 'Add to Cart') {
    let toast = document.getElementById('cart-added-toast');

    if (!toast) {
        toast = document.createElement('div');
        toast.id = 'cart-added-toast';
        toast.className = 'pointer-events-none fixed right-4 top-[88px] z-[10050] opacity-0 transition-all duration-300';
        toast.style.transform = 'translateY(-14px) scale(0.96)';
        toast.innerHTML = `
            <div class="flex min-w-[240px] items-center gap-3 rounded-2xl border border-emerald-200 bg-white px-4 py-3.5 shadow-[0_24px_50px_rgba(15,23,42,0.22)] ring-1 ring-emerald-50">
                <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-600 text-white shadow-sm">
                    <i class="fa-solid fa-check text-sm"></i>
                </div>
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-[0.18em] text-emerald-600">Success</p>
                    <p id="cart-added-toast-message" class="mt-0.5 text-sm font-bold text-slate-900">Add to Cart</p>
                </div>
            </div>
        `;
        document.body.appendChild(toast);
    }

    const messageNode = document.getElementById('cart-added-toast-message');
    if (messageNode) {
        messageNode.textContent = message;
    }

    clearTimeout(cartToastTimeout);
    toast.classList.remove('opacity-0');
    toast.classList.add('opacity-100');
    toast.style.transform = 'translateY(0) scale(1)';

    cartToastTimeout = setTimeout(() => {
        toast.classList.remove('opacity-100');
        toast.classList.add('opacity-0');
        toast.style.transform = 'translateY(-14px) scale(0.96)';
    }, 1800);
}
// Add to cart (AJAX)
function addToCart(productId, variantIndex, pricingDay = 'today') {
    fetch('{{ route("frontend.cart.add") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        },
        body: JSON.stringify({ product_id: productId, quantity: 1, variant_index: variantIndex ?? null, pricing_day: pricingDay })
    })
    .then(r => r.json())
    .then(data => {
        if (!data.success) {
            alert(data.message || 'This product is currently unavailable.');
            return;
        }

        document.getElementById('header-cart-badge').textContent = data.cart_count;
        showCartAddedAlert('Add to Cart');
    });
}
function formatCartCurrency(amount) {
    return 'Rs' + Number(amount || 0).toFixed(2);
}
let selectedCartDrawerDay = 'today';

function selectCartDrawerDay(day) {
    selectedCartDrawerDay = day === 'tomorrow' ? 'tomorrow' : 'today';
    refreshCartDrawer();
}
function applyCartCoupon(code) {
    fetch('{{ url("cart/coupon") }}', {
        method: 'POST',
        headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json'},
        body: JSON.stringify({code, delivery_day: selectedCartDrawerDay})
    }).then(async r => {
        const data = await r.json();
        if (!r.ok) throw new Error(data.message || 'Could not apply discount.');
        refreshCartDrawer();
    }).catch(error => alert(error.message));
}
function removeCartCoupon() {
    fetch('{{ url("cart/coupon") }}', {
        method: 'DELETE',
        headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json'},
        body: JSON.stringify({delivery_day: selectedCartDrawerDay})
    }).then(() => refreshCartDrawer());
}
function renderCartCoupons(data) {
    const panel = document.getElementById('cart-coupon-panel');
    if (!panel) return;
    const applied = data.applied_coupon;
    const available = data.available_coupons || [];
    panel.classList.toggle('hidden', !applied && !available.length);
    if (applied) {
        panel.innerHTML = `<div class="flex items-center justify-between gap-3">
            <div><p class="text-[10px] font-black uppercase tracking-wider text-emerald-600">Applied</p><p class="text-xs font-bold text-slate-800">${escapeHtml(applied.title)}</p></div>
            <button type="button" onclick="removeCartCoupon()" class="text-[11px] font-black text-red-500">Remove</button>
        </div>`;
        return;
    }
    panel.innerHTML = `<p class="mb-2 text-[10px] font-black uppercase tracking-wider text-blue-700">Available offers</p>` +
        available.map(coupon => `<div class="mb-2 flex items-center justify-between gap-2 rounded-xl bg-white p-2 last:mb-0">
            <div class="min-w-0"><p class="truncate text-xs font-bold text-slate-800">${escapeHtml(coupon.title)}</p><p class="text-[10px] text-slate-500">${coupon.type === 'percent' ? coupon.value + '%' : 'Rs' + coupon.value} off</p></div>
            <button type="button" onclick="applyCartCoupon('${escapeHtml(coupon.code)}')" class="rounded-lg bg-blue-700 px-3 py-1.5 text-[10px] font-black text-white">Apply</button>
        </div>`).join('');
}
function renderCartDrawerItems(items) {
    const container = document.getElementById('cart-items-drawer');

    if (!items || !items.length) {
        container.innerHTML = '<p class="text-center text-gray-400 py-8">Your cart is empty</p>';
        return;
    }

    container.innerHTML = items.map((item) => `
        <div class="rounded-xl border border-gray-100 bg-white p-2 shadow-sm">
            <div class="flex gap-2">
                <a href="${item.product_url}" class="h-16 w-16 overflow-hidden rounded-lg bg-gray-100 flex-shrink-0">
                    ${item.image_url
                        ? `<img src="${item.image_url}" alt="${item.name}" class="h-full w-full object-cover">`
                        : '<div class="flex h-full w-full items-center justify-center text-xl text-gray-300"><i class="fa-solid fa-drumstick-bite"></i></div>'}
                </a>
                <div class="min-w-0 flex-1">
                    <div class="flex items-start justify-between gap-2">
                        <a href="${item.product_url}" class="line-clamp-2 text-[13px] font-bold leading-4 text-slate-900 hover:text-blue-700">
                            ${item.name}
                        </a>
                        <button onclick="removeCartDrawerItem('${item.key}')" class="flex h-6 w-6 items-center justify-center rounded-full text-gray-400 transition hover:bg-red-50 hover:text-red-500">
                            <i class="fa-solid fa-xmark text-[11px]"></i>
                        </button>
                    </div>
                    <div class="mt-0.5 flex flex-wrap items-center gap-1">
                        <span class="text-[10px] font-semibold uppercase tracking-[0.12em] text-slate-400">
                            ${item.variant_label || item.unit || 'Fresh Cut'}
                        </span>
                        <span class="inline-flex items-center rounded border px-1 py-0.5 text-[8px] font-bold uppercase tracking-wider ${
                            item.pricing_day === 'tomorrow'
                                ? 'bg-amber-50 text-amber-700 border-amber-100'
                                : 'bg-emerald-50 text-emerald-700 border-emerald-100'
                        }">
                            ${item.pricing_day === 'tomorrow' ? 'Tomorrow' : 'Today'}
                        </span>
                    </div>
                    <div class="mt-2 flex items-end justify-between gap-2">
                        <div>
                            <p class="text-xs font-black text-slate-900">${formatCartCurrency(item.price)}</p>
                            <div class="mt-1 inline-flex items-center overflow-hidden rounded-md border border-gray-200 bg-white">
                                <button onclick="updateCartDrawerQty('${item.key}', ${item.quantity - 1})" class="flex h-7 w-7 items-center justify-center text-gray-500 transition hover:bg-gray-50 hover:text-red-500">
                                    <i class="fa-solid fa-minus text-[8px]"></i>
                                </button>
                                <span class="flex h-7 min-w-[28px] items-center justify-center border-x border-gray-200 px-1.5 text-[11px] font-bold text-slate-700">
                                    ${item.quantity}
                                </span>
                                <button onclick="updateCartDrawerQty('${item.key}', ${item.quantity + 1})" class="flex h-7 w-7 items-center justify-center text-gray-500 transition hover:bg-gray-50 hover:text-green-600">
                                    <i class="fa-solid fa-plus text-[8px]"></i>
                                </button>
                            </div>
                        </div>
                        <p class="text-xs font-extrabold text-blue-700">${formatCartCurrency(item.subtotal)}</p>
                    </div>
                </div>
            </div>
        </div>
    `).join('');
}
function removeCartDrawerItem(key) {
    fetch('{{ route("frontend.cart.remove") }}', {
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
    fetch('{{ route("frontend.cart.update") }}', {
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
function updateCartDrawerDeliverySlot(value) {
    fetch('{{ route("frontend.cart.delivery-slot") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        },
        body: JSON.stringify({ delivery_slot: value, delivery_day: selectedCartDrawerDay })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            refreshCartDrawer();
        }
    });
}

function escapeHtml(value) {
    return String(value ?? '').replace(/[&<>"']/g, (char) => ({
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    }[char]));
}

function renderDrawerDeliverySlots(options = [], selectedValue = '') {
    const container = document.getElementById('drawer-delivery-slot-options');

    if (!container) {
        return;
    }

    if (!options.length) {
        container.innerHTML = `
            <span class="rounded-full border border-amber-200 bg-amber-50 px-2.5 py-2 text-[10px] font-bold leading-none text-amber-700">
                No slots left for today
            </span>
        `;
        return;
    }

    container.innerHTML = options.map((slot) => {
        const isActive = slot.value === selectedValue;
        const classes = isActive
            ? 'border-green-600 bg-white text-green-700 shadow-sm'
            : 'border-green-100 bg-white/80 text-slate-600';

        return `
            <button
                type="button"
                onclick="updateCartDrawerDeliverySlot('${escapeHtml(slot.value)}')"
                data-drawer-delivery-slot="${escapeHtml(slot.value)}"
                class="drawer-delivery-slot-chip ${classes} rounded-full border px-2.5 py-2 text-[10px] font-bold leading-none transition hover:border-green-400 hover:text-green-700"
            >
                ${escapeHtml(slot.label)}
            </button>
        `;
    }).join('');
}

// Refresh cart drawer
function refreshCartDrawer() {
    fetch('{{ route("frontend.cart.count") }}?delivery_day=' + encodeURIComponent(selectedCartDrawerDay))
        .then(r => r.json())
        .then(data => {
            selectedCartDrawerDay = data.delivery_day || selectedCartDrawerDay;
            document.getElementById('header-cart-badge').textContent = data.count;
            document.getElementById('cart-badge-drawer').textContent = data.count + ' item' + (data.count !== 1 ? 's' : '');
            document.getElementById('cart-subtotal-drawer').textContent = formatCartCurrency(data.subtotal);
            const chargePercent = Number(data.service_charge_percent || 0);
            const chargePercentText = Number.isInteger(chargePercent)
                ? String(chargePercent)
                : chargePercent.toFixed(2).replace(/0+$/, '').replace(/\.$/, '');
            document.getElementById('cart-delivery-charge-drawer').textContent = formatCartCurrency(data.delivery_charge);
            document.getElementById('cart-service-charge-label').textContent = '\u2139\uFE0F Service Charge';
            document.getElementById('cart-service-charge-drawer').textContent = formatCartCurrency(data.service_charge);
            document.getElementById('cart-delivery-charge-row')?.classList.toggle('hidden', Number(data.delivery_charge || 0) <= 0);
            document.getElementById('cart-service-charge-row')?.classList.toggle('hidden', Number(data.service_charge || 0) <= 0);
            const discountRow = document.getElementById('cart-discount-row');
            discountRow?.classList.toggle('hidden', Number(data.discount || 0) <= 0);
            discountRow?.classList.toggle('flex', Number(data.discount || 0) > 0);
            document.getElementById('cart-discount-drawer').textContent = '-' + formatCartCurrency(data.discount);
            document.getElementById('cart-total-drawer').textContent = formatCartCurrency(data.total);
            renderCartCoupons(data);
            const drawerSlotLabel = document.getElementById('drawer-delivery-slot-label');
            if (drawerSlotLabel) {
                drawerSlotLabel.textContent = data.selected_delivery_slot_label || '';
            }
            const drawerDayLabel = document.getElementById('drawer-delivery-day-label');
            if (drawerDayLabel) {
                drawerDayLabel.textContent = data.delivery_day_label || 'Today';
            }
            const availableDays = data.available_days || [];
            const dayTabs = document.getElementById('cart-day-tabs');
            dayTabs?.classList.toggle('hidden', availableDays.length < 2);
            dayTabs?.classList.toggle('flex', availableDays.length >= 2);
            document.querySelectorAll('[data-cart-day-tab]').forEach((tab) => {
                const isActive = tab.dataset.cartDayTab === selectedCartDrawerDay;
                tab.classList.toggle('bg-blue-700', isActive);
                tab.classList.toggle('text-white', isActive);
                tab.classList.toggle('text-slate-500', !isActive);
            });
            const checkoutLink = document.getElementById('cart-checkout-link');
            if (checkoutLink) {
                checkoutLink.href = '{{ route("frontend.checkout") }}?delivery_day=' + encodeURIComponent(selectedCartDrawerDay);
            }
            renderDrawerDeliverySlots(data.delivery_slot_options || [], data.selected_delivery_slot || '');
            document.querySelectorAll('[data-drawer-delivery-slot]').forEach((chip) => {
                const isActive = chip.dataset.drawerDeliverySlot === data.selected_delivery_slot;
                chip.classList.toggle('border-green-600', isActive);
                chip.classList.toggle('bg-white', isActive);
                chip.classList.toggle('text-green-700', isActive);
                chip.classList.toggle('shadow-sm', isActive);
                chip.classList.toggle('border-green-100', !isActive);
                chip.classList.toggle('bg-white/80', !isActive);
                chip.classList.toggle('text-slate-600', !isActive);
            });
            renderCartDrawerItems(data.items || []);
        });
}
// Wishlist helper functions
function toggleWishlist(productId, btnElement) {
    fetch('{{ route("frontend.wishlist.toggle") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ product_id: productId })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            const badge = document.getElementById('header-wishlist-badge');
            if (badge) {
                badge.textContent = data.count;
                if (data.count > 0) {
                    badge.classList.remove('hidden');
                } else {
                    badge.classList.add('hidden');
                }
            }

            document.querySelectorAll(`.wishlist-btn[data-product-id="${productId}"]`).forEach(btn => {
                const icon = btn.querySelector('i');
                if (icon) {
                    if (data.action === 'added') {
                        icon.className = 'fa-solid fa-heart text-lg text-red-500';
                        btn.classList.add('active');
                    } else {
                        icon.className = 'fa-regular fa-heart text-lg text-slate-500 hover:text-red-500';
                        btn.classList.remove('active');
                    }
                }
            });

            const wishlistItem = document.getElementById(`wishlist-item-${productId}`);
            if (wishlistItem && data.action === 'removed') {
                wishlistItem.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                wishlistItem.style.opacity = '0';
                wishlistItem.style.transform = 'scale(0.9)';
                setTimeout(() => {
                    wishlistItem.remove();
                    if (document.querySelectorAll('[id^="wishlist-item-"]').length === 0) {
                        location.reload();
                    }
                }, 300);
            }

            showToast(data.message, data.action === 'added' ? 'success' : 'info');
        }
    })
    .catch(err => {
        console.error('Wishlist error:', err);
    });
}

function showToast(msg, type = 'success') {
    const toast = document.createElement('div');
    toast.className = `fixed bottom-5 right-5 z-[9999] flex items-center gap-2.5 rounded-2xl px-5 py-3.5 text-xs font-bold text-white shadow-2xl transition-all duration-300 transform translate-y-5 opacity-0 ${type === 'success' ? 'bg-slate-900 border border-red-500/40 shadow-red-500/10' : 'bg-slate-800'}`;
    toast.innerHTML = `<i class="fa-solid ${type === 'success' ? 'fa-heart text-red-500 text-sm' : 'fa-circle-info text-blue-400 text-sm'}"></i> <span>${msg}</span>`;
    document.body.appendChild(toast);
    setTimeout(() => {
        toast.classList.remove('translate-y-5', 'opacity-0');
    }, 10);
    setTimeout(() => {
        toast.classList.add('translate-y-5', 'opacity-0');
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

// Init badge
refreshCartDrawer();
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeCart(); });
</script>
@yield('scripts')
</body>
</html>
