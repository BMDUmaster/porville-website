<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width,initial-scale=1.0"/>
@include('frontend.partials.seo-meta')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="icon" type="image/jpeg" href="{{ $brandLogoUrl }}">
<link rel="shortcut icon" href="{{ $brandLogoUrl }}">
<link rel="apple-touch-icon" href="{{ $brandLogoUrl }}">
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Nunito:wght@600;700;800&family=Playfair+Display:wght@600;700;800;900&display=swap" rel="stylesheet">
<style>
* { font-family: 'Poppins', sans-serif; box-sizing: border-box; }
.nunito { font-family: 'Nunito', sans-serif; }
.font-classic, h1, h2, .brand-wordmark { font-family: 'Playfair Display', serif; }
.sidebar-transition { transition: transform .3s ease; }
.sidebar-open { transform: translateX(0) !important; }
#sidebar-overlay { display:none; position:fixed; inset:0; background:rgba(0,0,0,.45); z-index:90; }
#sidebar-overlay.active { display:block; }
/* Cart drawer */
#cart-drawer { transform: translateX(100%); transition: transform .35s cubic-bezier(.4,0,.2,1); }
#cart-drawer.open { transform: translateX(0); }
#cart-added-popup { transform: translateX(calc(100% + 24px)); transition: transform .28s cubic-bezier(.4,0,.2,1), opacity .28s ease; opacity: 0; pointer-events: none; }
#cart-added-popup.open { transform: translateX(0); opacity: 1; pointer-events: auto; }
@keyframes runningBorderAnim {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}
.running-color-border {
    background: linear-gradient(135deg, #ff0055, #ff7700, #ffeb00, #00e5ff, #7a00ff, #ff0055);
    background-size: 300% 300%;
    animation: runningBorderAnim 2s linear infinite;
}
.running-color-line {
    background: linear-gradient(90deg, #ff0055, #ff7700, #ffeb00, #00e5ff, #7a00ff, #ff0055);
    background-size: 300% 300%;
    animation: runningBorderAnim 2s linear infinite;
}
/* Mobile responsive fixes */
@media (max-width: 640px) {
    .container { padding-left: 12px !important; padding-right: 12px !important; }
    #cart-drawer { width: 100%; max-width: 100vw; }
    #cart-drawer > div { padding-left: 14px; padding-right: 14px; }
    #cart-added-popup { top: 72px; right: 8px; width: calc(100vw - 16px); max-height: calc(100vh - 84px); }
    #accountMenu { position: fixed; top: 64px; right: 10px; width: min(14rem, calc(100vw - 20px)); }
}
@media (min-width: 1024px) {
    #mobile-sidebar { display: none !important; }
    #sidebar-overlay { display: none !important; }
}
/* Prevent horizontal overflow */
html, body { overflow-x: clip; max-width: 100vw; }
html, body { scrollbar-width: none; -ms-overflow-style: none; }
html::-webkit-scrollbar, body::-webkit-scrollbar { width: 0; height: 0; display: none; }
@media (min-width: 1024px) {
    main h1 { font-size: 2.25rem !important; line-height: 1.18 !important; }
    main h2 { font-size: 1.75rem !important; line-height: 1.25 !important; }
    main h3 { font-size: 1.1rem !important; line-height: 1.35 !important; }
}
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
.logo-fish-flight {
    position: absolute;
    left: -12px;
    top: 2px;
    z-index: 2;
    color: #49a942;
    font-size: 18px;
    line-height: 1;
    opacity: 0;
    filter: drop-shadow(0 2px 2px rgba(20, 83, 45, .2));
    pointer-events: none;
    animation: logo-fish-swim 4.2s ease-in-out infinite;
}
.logo-fish-flight::before,
.logo-fish-flight::after {
    content: "";
    position: absolute;
    width: 5px;
    height: 5px;
    border: 1.5px solid #60a5fa;
    border-radius: 999px;
    opacity: 0;
}
.logo-fish-flight::before { left: -8px; top: 2px; }
.logo-fish-flight::after { left: -14px; top: -5px; width: 3px; height: 3px; }
@keyframes logo-fish-swim {
    0%, 10% { opacity: 0; transform: translate3d(-8px, 16px, 0) rotate(-12deg) scale(.72); }
    16% { opacity: 1; }
    36% { transform: translate3d(58px, -9px, 0) rotate(-4deg) scale(1); }
    58% { opacity: 1; transform: translate3d(126px, -13px, 0) rotate(5deg) scale(.96); }
    72% { opacity: .9; transform: translate3d(172px, 1px, 0) rotate(18deg) scale(.82); }
    82%, 100% { opacity: 0; transform: translate3d(194px, 24px, 0) rotate(35deg) scale(.45); }
}
@keyframes logo-fish-bubble {
    0%, 12%, 76%, 100% { opacity: 0; transform: translateY(5px) scale(.5); }
    22% { opacity: .85; }
    58% { opacity: 0; transform: translateY(-13px) scale(1.15); }
}
.logo-fish-flight::before { animation: logo-fish-bubble 4.2s ease-out infinite; }
.logo-fish-flight::after { animation: logo-fish-bubble 4.2s .18s ease-out infinite; }
.logo-fish-flight--second {
    top: 7px;
    color: #168a55;
    font-size: 15px;
    animation-delay: 2.1s;
}
.logo-fish-flight--second::before { animation-delay: 2.1s; }
.logo-fish-flight--second::after { animation-delay: 2.28s; }
@media (max-width: 767px) {
    .logo-fish-flight { display: none; }
}
@media (prefers-reduced-motion: reduce) {
    .announcement-track { animation: none; }
    .logo-fish-flight { display: none; }
}
</style>
@yield('styles')
</head>
<body class="min-h-screen flex flex-col" style="background:#f5f6fa;">

<div id="sidebar-overlay" onclick="toggleSidebar()"></div>

<!-- Mobile Sidebar -->
<div id="mobile-sidebar" class="fixed inset-y-0 left-0 z-[100] w-72 bg-white shadow-2xl transform -translate-x-full sidebar-transition">
    <div class="p-5 border-b flex justify-between items-center bg-amber-700 text-white">
        <span class="font-bold uppercase tracking-wider">Porville Menu</span>
        <button onclick="toggleSidebar()"><i class="fa-solid fa-xmark text-2xl"></i></button>
    </div>
    <div class="p-4 overflow-y-auto h-full pb-20">
        <h3 class="text-amber-500 font-bold mb-3 mt-2">Categories</h3>
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
                            <i id="mobile-category-icon-{{ $category->id }}" class="fa-solid fa-chevron-down text-[10px] text-amber-500 transition-transform"></i>
                        </button>
                        <ul id="mobile-category-{{ $category->id }}" class="ml-3 hidden border-l border-amber-100 pl-2">
                            <li>
                                <a href="{{ route('frontend.products', ['category' => $category->slug]) }}" class="block rounded p-2 text-xs font-semibold text-amber-500 hover:bg-gray-100">
                                    View all {{ $category->name }}
                                </a>
                            </li>
                            @foreach($category->children as $subcategory)
                                <li>
                                    <a href="{{ route('frontend.products', ['category' => $category->slug, 'subcategory' => $subcategory->slug]) }}" class="block p-2 text-xs text-gray-500 hover:bg-gray-100 hover:text-amber-500 rounded">{{ $subcategory->name }}</a>
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
        <a href="{{ route('frontend.track') }}" class="block p-2 font-bold text-amber-600">Track Order</a>
        @auth('web_frontend')
            <a href="{{ route('frontend.profile') }}" class="block p-2 font-bold text-amber-500">My Profile</a>
            <a href="{{ route('frontend.orders') }}" class="block p-2 font-bold text-amber-500">My Orders</a>
        @else
            <a href="{{ route('frontend.login') }}" class="block p-2 font-bold text-amber-600">Login / Register</a>
        @endauth
    </div>
</div>

@php
    $drawerCart = session('cart', []);
    $initialCartItemCount = count($drawerCart);
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
            <i class="fa-solid fa-cart-shopping text-amber-500"></i>
            <span class="nunito font-extrabold text-base">Your Cart</span>
            <span id="cart-badge-drawer" class="bg-amber-600 text-white text-xs font-bold px-2 py-0.5 rounded-full">0</span>
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
        <div id="cart-coupon-wrap" class="mb-3 hidden rounded-2xl border border-amber-100 bg-amber-50/60">
            <button type="button" onclick="toggleCartDropdown('cart-coupon-panel', 'cart-coupon-chevron')" class="flex w-full items-center justify-between gap-3 px-3 py-3 text-left">
                <span class="text-xs font-black uppercase tracking-[0.16em] text-amber-500">
                    <i class="fa-solid fa-tag mr-1"></i> Offers &amp; Coupons
                </span>
                <i id="cart-coupon-chevron" class="fa-solid fa-chevron-down text-[10px] text-amber-500 transition"></i>
            </button>
            <div id="cart-coupon-panel" class="hidden border-t border-amber-100 px-3 py-3"></div>
        </div>
        <div class="mb-3 rounded-2xl border border-slate-200 bg-slate-50/70">
            <button type="button" onclick="toggleCartDropdown('cart-amount-details', 'cart-amount-chevron')" class="flex w-full items-center justify-between gap-3 px-3 py-3 text-left">
                <span class="text-xs font-black uppercase tracking-[0.16em] text-slate-600">Amount Details</span>
                <i id="cart-amount-chevron" class="fa-solid fa-chevron-down text-[10px] text-slate-500 transition"></i>
            </button>
            <div id="cart-amount-details" class="hidden border-t border-slate-200 px-3 py-3">
        <div class="flex justify-between text-sm font-semibold mb-2">
            <span>Subtotal</span>
            <span id="cart-subtotal-drawer">₹0.00</span>
        </div>
        <div id="cart-delivery-charge-row" class="mb-2 flex justify-between text-xs text-slate-500">
            <span>Delivery</span>
            <span id="cart-delivery-charge-drawer">₹0.00</span>
        </div>
        <div id="cart-service-charge-row" class="mb-2 flex justify-between text-xs text-slate-500">
            <span id="cart-service-charge-label">&#8505;&#65039; Service Charge</span>
            <span id="cart-service-charge-drawer">₹0.00</span>
        </div>
        <div id="cart-discount-row" class="mb-2 hidden justify-between text-xs font-bold text-emerald-600">
            <span>Discount</span>
            <span id="cart-discount-drawer">-₹0.00</span>
        </div>
        <div class="mb-3 flex justify-between text-sm font-bold text-slate-900">
            <span>Total</span>
            <span id="cart-total-drawer">₹0.00</span>
        </div>
            </div>
        </div>
        <a href="{{ route('frontend.checkout') }}"
           class="flex items-center justify-center gap-2 w-full bg-amber-600 hover:bg-amber-700 text-white font-bold py-3 rounded-xl text-sm">
            <i class="fa-solid fa-lock text-xs"></i> Proceed to Checkout
        </a>
    </div>
</div>

<!-- Compact add-to-cart preview: this is intentionally separate from the full cart drawer. -->
<aside id="cart-added-popup" class="fixed right-4 top-[88px] z-[10020] flex max-h-[calc(100vh-104px)] w-[440px] max-w-[calc(100vw-32px)] flex-col rounded-2xl p-[3px] shadow-[0_24px_60px_rgba(15,23,42,0.25)] running-color-border" aria-live="polite">
    <div class="flex h-full w-full flex-col overflow-hidden rounded-[13px] bg-white">
        <div class="h-1 w-full running-color-line"></div>
        <div class="flex items-center justify-between border-b border-slate-100 px-4 py-3">
            <div class="flex items-center gap-2 text-emerald-700"><i class="fa-solid fa-circle-check"></i><span class="text-sm font-black">Added to Cart</span></div>
            <button type="button" onclick="closeCartAddedPopup()" class="text-lg leading-none text-slate-400 hover:text-slate-700" aria-label="Close cart preview">&times;</button>
        </div>
        <div id="cart-added-popup-items" class="max-h-[420px] space-y-3 overflow-y-auto px-4 py-3"></div>
        <button type="button" onclick="openCartFromPreview()" class="m-3 mt-0 rounded-xl border border-amber-200 px-4 py-3 text-sm font-black text-amber-500 transition hover:bg-amber-50">Go to Cart <i class="fa-solid fa-angle-right ml-1"></i></button>
    </div>
</aside>

<!-- Header -->
<header id="siteHeader" class="fixed inset-x-0 top-0 z-[9997] bg-black shadow-sm">
    <div class="hidden relative flex h-8 items-center overflow-hidden bg-neutral-950 pr-[108px] text-white sm:pr-[162px]">
        <div class="announcement-track items-center whitespace-nowrap text-[10px] font-bold sm:text-[11px]">
            @for($copy = 0; $copy < 2; $copy++)
                <div class="flex items-center gap-10 pr-10">
                    <span><i class="fa-solid fa-leaf mr-2 text-amber-400"></i>Fresh Cut. Pure Standards. Delivered to Your Door.</span>
                    @if($frontendTickerOffer ?? null)
                        <a href="{{ route('frontend.products', ['offer' => 'flash_deal']) }}" class="text-amber-300 hover:text-amber-200">
                            <i class="fa-solid fa-bolt mr-2"></i>{{ $frontendTickerOffer->title ?: $frontendTickerOffer->description ?: 'Special offer available now' }}
                        </a>
                    @else
                        <span class="text-amber-300"><i class="fa-solid fa-bolt mr-2"></i>Fresh deals available every day</span>
                    @endif
                    @if($frontendTickerProduct ?? null)
                        <a href="{{ route('frontend.product.show', $frontendTickerProduct->slug) }}" class="hover:text-amber-200">
                            <i class="fa-solid fa-star mr-2 text-amber-300"></i>New Arrival: {{ $frontendTickerProduct->name }} — Shop Now
                        </a>
                    @endif
                </div>
            @endfor
        </div>
        <a href="{{ route('frontend.deals') }}" class="absolute inset-y-0 right-0 z-10 flex items-center gap-1.5 border-l border-white/20 bg-gradient-to-r from-amber-700 to-amber-900 px-3 text-[9px] font-black uppercase tracking-wider text-amber-100 shadow-[-10px_0_18px_rgba(0,0,0,0.6)] transition hover:text-white sm:px-4 sm:text-[10px]">
            <i class="fa-solid fa-ticket"></i>
            <span class="sm:hidden">Deals</span>
            <span class="hidden sm:inline">Coupons &amp; Offers</span>
        </a>
    </div>
    <div class="mx-auto flex w-full max-w-7xl items-center justify-between gap-2 px-3 py-2 sm:px-3 md:px-4 lg:gap-4 lg:py-2.5">
        <button onclick="toggleSidebar()" aria-label="Open menu" class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-lg text-stone-200 text-lg lg:hidden">
            <i class="fa-solid fa-bars"></i>
        </button>
        <a href="{{ route('frontend.home') }}" class="relative flex flex-shrink-0 items-center gap-2.5 sm:gap-3">
            <img src="{{ $brandLogoUrl }}"
                 alt="Porville"
                 class="h-9 w-9 flex-shrink-0 rounded-full object-cover ring-1 ring-amber-500/40 sm:h-10 sm:w-10 md:h-12 md:w-12"
                 onerror="this.style.display='none'">
            <span class="flex flex-col leading-none">
                <span class="font-classic text-lg font-bold tracking-wide text-amber-300 sm:text-xl md:text-2xl">PORVILLE</span>
                <span class="mt-1 hidden text-[8px] font-semibold uppercase tracking-[0.22em] text-stone-300 sm:block md:text-[9px]">Fresh Cut Pure Standards</span>
            </span>
        </a>
        <form action="{{ route('frontend.products') }}" method="GET" class="relative mx-2 hidden max-w-lg flex-grow md:flex lg:mx-4">
            <input type="text" name="search" placeholder="Search Chicken Mutton Fish Items"
                   class="w-full border border-neutral-700 bg-neutral-900 text-white placeholder:text-neutral-400 rounded-xl px-5 py-2.5 text-sm focus:outline-none focus:border-amber-500 transition">
            <button type="submit" class="absolute right-4 top-1/2 -translate-y-1/2 text-neutral-400 hover:text-amber-400">
                <i class="fa-solid fa-magnifying-glass"></i>
            </button>
        </form>
        <div class="flex items-center gap-3 md:gap-3 lg:gap-4 lg:pr-7">
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
                <button onclick="toggleAccountMenu()" class="text-stone-200 flex flex-col items-center group">
                    <span class="relative">
                        <i class="fa-regular fa-user text-xl group-hover:text-amber-400"></i>
                        @auth('web_frontend')
                            @if($frontendUnreadNotifications > 0)
                                <span class="absolute -right-2 -top-2 flex h-4 min-w-4 items-center justify-center rounded-full bg-red-500 px-1 text-[9px] font-black leading-none text-white ring-2 ring-black">
                                    {{ $frontendUnreadNotifications > 99 ? '99+' : $frontendUnreadNotifications }}
                                </span>
                            @endif
                        @endauth
                    </span>
                    <span class="text-[10px] font-bold mt-0.5 hidden md:block">
                        @auth('web_frontend') Hello, {{ Str::limit(auth('web_frontend')->user()->name, 12) }} @else Sign in/Account @endauth
                    </span>
                </button>
                <div id="accountMenu" class="hidden absolute right-0 mt-3 w-60 overflow-hidden rounded-2xl border border-amber-500/30 bg-neutral-950 py-2 shadow-[0_20px_50px_rgba(0,0,0,0.5)] z-50">
                    @auth('web_frontend')
                        <div class="border-b border-white/10 px-5 py-3">
                            <p class="text-[10px] font-bold uppercase tracking-[0.16em] text-stone-400">Signed in as</p>
                            <p class="mt-0.5 truncate text-sm font-bold text-amber-300">{{ auth('web_frontend')->user()->name }}</p>
                        </div>
                        <a href="{{ route('frontend.profile') }}" class="flex items-center gap-3 px-5 py-2.5 text-sm font-semibold text-stone-200 transition hover:bg-amber-500/10 hover:text-amber-300">
                            <i class="fa-regular fa-user w-4 text-amber-500"></i> My Profile
                        </a>
                        <a href="{{ route('frontend.notifications') }}" class="flex items-center gap-3 px-5 py-2.5 text-sm font-semibold text-stone-200 transition hover:bg-amber-500/10 hover:text-amber-300">
                            <i class="fa-regular fa-bell w-4 text-amber-500"></i>
                            Notifications
                            @if($frontendTotalNotifications > 0)
                                <span class="ml-auto rounded-full {{ $frontendUnreadNotifications > 0 ? 'bg-red-500 text-white' : 'bg-white/10 text-stone-300' }} px-2 py-0.5 text-[10px] font-bold">
                                    {{ $frontendTotalNotifications > 99 ? '99+' : $frontendTotalNotifications }}
                                </span>
                            @endif
                        </a>
                        <a href="{{ route('frontend.orders') }}" class="flex items-center gap-3 px-5 py-2.5 text-sm font-semibold text-stone-200 transition hover:bg-amber-500/10 hover:text-amber-300">
                            <i class="fa-solid fa-box w-4 text-amber-500"></i> My Orders
                        </a>
                        <hr class="my-2 border-white/10">
                        <form method="POST" action="{{ route('frontend.logout') }}">
                            @csrf
                            <button type="submit" class="flex w-full items-center gap-3 px-5 py-2.5 text-sm font-semibold text-red-400 transition hover:bg-red-500/10">
                                <i class="fa-solid fa-right-from-bracket w-4"></i> Logout
                            </button>
                        </form>
                    @else
                        <a href="{{ route('frontend.login') }}" class="flex items-center gap-3 px-5 py-2.5 text-sm font-semibold text-stone-200 transition hover:bg-amber-500/10 hover:text-amber-300">
                            <i class="fa-solid fa-right-to-bracket w-4 text-amber-500"></i> Sign In
                        </a>
                        <a href="{{ route('frontend.register') }}" class="flex items-center gap-3 px-5 py-2.5 text-sm font-semibold text-stone-200 transition hover:bg-amber-500/10 hover:text-amber-300">
                            <i class="fa-solid fa-user-plus w-4 text-amber-500"></i> Register
                        </a>
                    @endauth
                </div>
            </div>
            <!-- Wishlist -->
            @php
                $initialWishlist = session('wishlist', []);
                $initialWishlistCount = count($initialWishlist);
            @endphp
            <a href="{{ route('frontend.wishlist') }}" class="text-stone-200 relative flex flex-col items-center group transition hover:text-red-400">
                <div class="relative">
                    <i class="fa-regular fa-heart text-xl group-hover:text-red-400 transition"></i>
                    <span id="header-wishlist-badge"
                          class="absolute -top-2 -right-2 bg-red-500 text-white text-[9px] w-4 h-4 rounded-full flex items-center justify-center font-black border border-black {{ $initialWishlistCount > 0 ? '' : 'hidden' }}">
                        {{ $initialWishlistCount }}
                    </span>
                </div>
                <span class="text-[10px] font-bold mt-0.5 hidden md:block">Wishlist</span>
            </a>
            <!-- Cart -->
            <button onclick="openCart()" class="text-amber-400 relative flex flex-col items-center group">
                <div class="relative">
                    <i class="fa-solid fa-cart-shopping text-xl group-hover:text-amber-300"></i>
                    <span id="header-cart-badge"
                          class="absolute -top-2 -right-2 bg-amber-500 text-black text-[9px] w-4 h-4 rounded-full flex items-center justify-center font-black border border-black {{ $initialCartItemCount > 0 ? '' : 'hidden' }}">{{ $initialCartItemCount }}</span>
                </div>
                <span class="text-[10px] font-bold mt-0.5 hidden md:block">Cart</span>
            </button>
        </div>
    </div>
    <div class="mx-auto max-w-7xl px-3 pb-2.5 md:hidden">
        <form action="{{ route('frontend.products') }}" method="GET" class="relative">
            <input type="text" name="search" placeholder="Search fresh items"
                   class="w-full rounded-xl border border-neutral-700 bg-neutral-900 text-white placeholder:text-neutral-400 px-3.5 py-2 pr-10 text-xs focus:border-amber-500 focus:outline-none transition">
            <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2 text-neutral-400 hover:text-amber-400">
                <i class="fa-solid fa-magnifying-glass"></i>
            </button>
        </form>
    </div>
    <!-- Nav strip -->
    <div class="border-t border-neutral-800 hidden md:block overflow-visible bg-black">
        <div class="mx-auto flex h-10 w-full max-w-7xl items-center gap-1 overflow-visible px-3 text-sm scrollbar-hide sm:px-3 md:px-4">
            <a href="{{ route('frontend.products') }}" class="px-3 py-1.5 bg-neutral-900 text-stone-200 font-semibold whitespace-nowrap rounded-md flex-shrink-0">All Products</a>
            @foreach($frontendNavCategories ?? collect() as $category)
                @if($category->children->isNotEmpty())
                    <div class="header-nav-group relative mr-2 flex-shrink-0">
                        <a href="{{ route('frontend.products', ['category' => $category->slug]) }}" class="inline-flex items-center gap-2 rounded-md bg-neutral-900 px-3 py-1.5 font-medium text-amber-400 transition hover:bg-neutral-800">
                            {{ $category->name }}
                            <i class="fa-solid fa-angle-down text-xs"></i>
                        </a>
                        <div class="header-nav-dropdown absolute left-0 top-full z-[10010] mt-3 min-w-[230px] space-y-1 rounded-2xl border border-amber-900/30 bg-white p-2 shadow-[0_20px_40px_rgba(15,23,42,0.14)]">
                            <a href="{{ route('frontend.products', ['category' => $category->slug]) }}" class="block rounded-xl px-4 py-3 text-[15px] font-semibold text-amber-700 transition hover:bg-amber-50">All {{ $category->name }}</a>
                            @foreach($category->children as $subcategory)
                                <a href="{{ route('frontend.products', ['category' => $category->slug, 'subcategory' => $subcategory->slug]) }}" class="block rounded-xl px-4 py-3 text-[15px] font-medium text-gray-700 transition hover:bg-amber-50 hover:text-amber-700">{{ $subcategory->name }}</a>
                            @endforeach
                        </div>
                    </div>
                @else
                    <a href="{{ route('frontend.products', ['category' => $category->slug]) }}" class="px-3 py-1.5 text-stone-200 whitespace-nowrap hover:bg-neutral-900 hover:text-amber-400 rounded-md flex-shrink-0">{{ $category->name }}</a>
                @endif
            @endforeach
            <span class="ml-auto"></span>
            <a href="{{ route('frontend.orders') }}" class="inline-flex items-center gap-2 px-3 py-1.5 font-extrabold text-amber-400 whitespace-nowrap rounded-md transition hover:bg-neutral-900">
                <i class="fa-solid fa-rotate-left text-[13px]"></i>
                View Order
            </a>
            <a href="{{ route('frontend.products', ['sort' => 'latest']) }}" class="px-3 py-1.5 text-stone-200 font-semibold whitespace-nowrap rounded-md transition hover:bg-neutral-900">Latest</a>
        </div>
    </div>
</header>

<div id="pageContentWrapper" class="pt-[95px] md:pt-[106px] lg:pt-[118px]">
    <!-- Flash Messages -->
    @if(session('success'))
        <div data-auto-dismiss="3000" class="mx-4 mt-3 flex items-center gap-2 rounded-lg bg-amber-100 p-3 text-sm text-amber-500 transition-all duration-500">
            <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div data-auto-dismiss="3000" class="mx-4 mt-3 flex items-center gap-2 rounded-lg bg-red-100 p-3 text-sm text-red-700 transition-all duration-500">
            <i class="fa-solid fa-circle-xmark"></i> {{ session('error') }}
        </div>
    @endif

    @if(session('account_blocked'))
        <div id="accountBlockedModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-slate-950/60 px-4 backdrop-blur-sm" role="dialog" aria-modal="true" aria-labelledby="accountBlockedTitle">
            <div class="w-full max-w-md overflow-hidden rounded-3xl bg-white text-center shadow-2xl">
                <div class="bg-gradient-to-br from-red-500 to-rose-600 px-6 pb-12 pt-8 text-white">
                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-white/20 ring-8 ring-white/10"><i class="fa-solid fa-lock text-2xl"></i></div>
                    <p class="mt-5 text-xs font-bold uppercase tracking-[0.2em] text-red-100">Order unavailable</p>
                </div>
                <div class="-mt-5 rounded-t-3xl bg-white px-7 pb-7 pt-6">
                    <h2 id="accountBlockedTitle" class="text-xl font-extrabold text-slate-900">Your account is blocked</h2>
                    <p class="mt-3 text-sm leading-6 text-slate-600">You cannot place an order while your account is blocked. Please contact our support team for help.</p>
                    <div class="mt-6 grid grid-cols-1 gap-3 sm:grid-cols-2">
                        <a href="{{ route('frontend.contact') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-amber-600 px-4 py-3 text-sm font-bold text-white transition hover:bg-amber-700"><i class="fa-solid fa-headset"></i> Contact Us</a>
                        <button type="button" data-close-account-blocked class="rounded-xl border border-slate-200 px-4 py-3 text-sm font-bold text-slate-700 transition hover:bg-slate-50">Go Back</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Main Content -->
    <main class="flex-1">
        @yield('content')
    </main>
</div>

<!-- Footer -->
<footer class="relative overflow-hidden bg-black pt-10 md:pt-12 lg:pt-14 pb-5 md:pb-6 lg:pb-7 text-white" style="font-family:'Poppins',sans-serif;">
    <div class="relative z-10 mx-auto max-w-[1220px] px-5 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 gap-x-6 gap-y-7 pb-6 lg:grid-cols-[1fr_1fr_1fr_1fr_180px] lg:gap-x-8 lg:gap-y-6 lg:pb-7">
            <div>
                <h4 class="mb-4 text-[12px] font-bold uppercase tracking-[0.22em] text-amber-400">Info</h4>
                <ul class="space-y-3.5">
                    <li><a href="{{ route('frontend.about') }}" class="text-[14px] text-[#c9b896] transition hover:text-white">About Us</a></li>
                    <li><a href="{{ route('frontend.contact') }}" class="text-[14px] text-[#c9b896] transition hover:text-white">Contact Us</a></li>
                    <li><a href="{{ route('frontend.faq') }}" class="text-[14px] text-[#c9b896] transition hover:text-white">FAQ</a></li>
                </ul>
            </div>

            <div>
                <h4 class="mb-4 text-[12px] font-bold uppercase tracking-[0.22em] text-amber-400">Useful Links</h4>
                <ul class="space-y-3.5">
                    <li><a href="{{ route('frontend.contact') }}" class="text-[14px] text-[#c9b896] transition hover:text-white">Help Center</a></li>
                    <li><a href="{{ route('frontend.shipping') }}" class="text-[14px] text-[#c9b896] transition hover:text-white">Shipping Info</a></li>
                    <li><a href="{{ route('frontend.returns') }}" class="text-[14px] text-[#c9b896] transition hover:text-white">Return Policy</a></li>
                    <li><a href="{{ route('frontend.privacy') }}" class="text-[14px] text-[#c9b896] transition hover:text-white">Privacy Policy</a></li>
                </ul>
            </div>

            <div>
                <h4 class="mb-4 text-[12px] font-bold uppercase tracking-[0.22em] text-amber-400">Categories</h4>
                <ul class="space-y-3.5">
                    @foreach(($frontendNavCategories ?? collect())->take(6) as $footerCat)
                        <li><a href="{{ route('frontend.products', ['category' => $footerCat->name]) }}" class="text-[14px] text-[#c9b896] transition hover:text-white">{{ $footerCat->name }}</a></li>
                    @endforeach
                </ul>
            </div>

            <div>
                <h4 class="mb-4 text-[12px] font-bold uppercase tracking-[0.22em] text-amber-400">Reach Us</h4>
                <p class="mb-3 text-[14px] text-[#c9b896]">D-1b/1028, Sangam Vihar-110080</p>
                <a href="tel:9217577006" class="mb-2 inline-flex min-h-9 items-center gap-2 rounded-lg pr-3 text-[15px] font-semibold text-white transition hover:text-amber-400">
                    <i class="fa-solid fa-phone text-amber-400 text-xs"></i> +91 92175 77006
                </a>
                <a href="mailto:porville1986@gmail.com" class="mb-4 flex min-w-0 items-center gap-2 text-[14px] text-[#c9b896] transition hover:text-white">
                    <i class="fa-regular fa-envelope shrink-0 text-xs"></i> <span class="min-w-0 break-all">porville1986@gmail.com</span>
                </a>
                <div class="flex items-center gap-3.5 text-[15px] text-[#c9b896]">
                    <a href="https://wa.me/919217577006" target="_blank" rel="noopener noreferrer" class="transition hover:text-white" aria-label="WhatsApp"><i class="fa-brands fa-whatsapp"></i></a>
                    <a href="https://www.instagram.com/porville_1986?stkn=MXFsNHc5dTZxdGRnaA%3D%3D" target="_blank" rel="noopener noreferrer" class="transition hover:text-white" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a>
                    <a href="#" target="_blank" rel="noopener noreferrer" class="transition hover:text-white" aria-label="Facebook"><i class="fa-brands fa-facebook"></i></a>
                </div>
            </div>

            <div class="col-span-2 lg:col-span-1">
                <div class="flex h-full min-h-[140px] flex-col items-center justify-center rounded-[14px] border border-amber-900/40 bg-[#141210] px-5 py-6 text-center">
                    <i class="fa-solid fa-drumstick-bite mb-3 text-[22px] text-amber-400"></i>
                    <h5 class="text-[13px] font-bold uppercase leading-none text-white">Fresh &amp; Hygienic</h5>
                    <p class="mt-2 text-[12px] text-[#c9b896]">Fresh Cut Pure Standards</p>
                </div>
            </div>
        </div>

        <div class="mb-5 flex items-center gap-3 rounded-2xl border border-emerald-800/40 bg-emerald-950/30 px-4 py-3">
            <span class="flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-full bg-emerald-500/15 text-emerald-400">
                <i class="fa-solid fa-shield-halved"></i>
            </span>
            <div class="text-left">
                <p class="text-[11px] font-bold uppercase tracking-[0.14em] text-emerald-400">FSSAI Food Safety Registered</p>
                <p class="text-[11px] text-[#c9b896]"><span class="font-semibold text-[#e7dcc2]">FoSCoS Ref No:</span> 30260223123490898 | <span class="font-semibold text-[#e7dcc2]">Date:</span> 23-02-2026</p>
            </div>
        </div>

        <div class="flex flex-col gap-4 border-t border-white/6 pt-4 text-center sm:pt-5 md:flex-row md:items-center md:justify-between md:text-left">
            <p class="text-[10px] uppercase tracking-[0.2em] text-[#c9b896]">
                &copy; {{ date('Y') }} Porville. All Rights Reserved. Branding: "Fresh Cut Pure Standards".
            </p>
            <a
                href="https://digitalutilization.com/"
                target="_blank"
                rel="noopener noreferrer"
                class="group inline-flex items-center justify-center gap-3 rounded-xl border border-white/10 bg-white/[0.04] px-4 py-2 transition hover:border-[#38bdf8]/40 hover:bg-white/[0.08] md:justify-end"
                aria-label="Developed by BMDU - visit Digital Utilization"
            >
                <span class="text-[10px] font-bold uppercase tracking-[0.16em] text-[#c9b896] transition group-hover:text-white">Developed By</span>
                <img
                    src="{{ asset('images/bmdu-logo.webp') }}"
                    class="block h-8 w-auto max-w-[120px] object-contain transition duration-200 group-hover:scale-[1.03]"
                    alt="BMDU"
                >
            </a>
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

let cartAddedPopupTimeout;

function closeCartAddedPopup() {
    document.getElementById('cart-added-popup')?.classList.remove('open');
    clearTimeout(cartAddedPopupTimeout);
}

function openCartFromPreview() {
    closeCartAddedPopup();
    openCart();
}

function showCartAddedPopup() {
    fetch('{{ route("frontend.cart.count") }}?delivery_day=' + encodeURIComponent(selectedCartDrawerDay))
        .then(response => response.json())
        .then(data => {
            const items = data.items || [];
            const container = document.getElementById('cart-added-popup-items');
            const popup = document.getElementById('cart-added-popup');
            if (!container || !popup) return;

            container.innerHTML = items.length
                ? items.map(item => `
                    <div class="flex items-center gap-3">
                        <img src="${escapeHtml(item.image_url || '')}" alt="${escapeHtml(item.name)}" class="h-16 w-16 rounded-xl bg-slate-100 object-cover" onerror="this.style.visibility='hidden'">
                        <div class="min-w-0 flex-1"><p class="line-clamp-2 text-sm font-bold text-slate-800">${escapeHtml(item.name)}</p><p class="mt-1 text-xs text-slate-500">${escapeHtml(item.variant_label || item.unit || '1 pack')} × ${item.quantity}</p></div>
                        <p class="text-sm font-black text-emerald-700">${formatCartCurrency(item.subtotal)}</p>
                    </div>`).join('')
                : '<p class="py-5 text-center text-sm text-slate-400">Your cart is empty.</p>';

            popup.classList.add('open');
            clearTimeout(cartAddedPopupTimeout);
            cartAddedPopupTimeout = setTimeout(closeCartAddedPopup, 3000);
        })
        .catch(() => showCartAddedAlert('Added to cart'));
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
function updateHeaderCartBadge(count) {
    const badge = document.getElementById('header-cart-badge');

    if (!badge) return;

    const itemCount = Math.max(0, Number(count) || 0);
    badge.textContent = itemCount;
    badge.classList.toggle('hidden', itemCount === 0);
}

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

        // Update badge with unique product count (not total quantity)
        updateHeaderCartBadge(data.unique_count ?? data.cart_count);

        // Show only the compact product preview; the full drawer opens from Cart/Go to Cart.
        showCartAddedPopup();
    });
}
function formatCartCurrency(amount) {
    return '₹' + Number(amount || 0).toFixed(2);
}
// Product card pack-size dropdown + single Add to Cart button (see partials/product-card.blade.php)
function updateProductCardPrice(cardUid) {
    const select = document.getElementById(cardUid);
    const priceEl = document.getElementById(cardUid + '-price');
    if (!select || !priceEl) return;
    const option = select.options[select.selectedIndex];
    const price = option?.dataset.price;
    if (price === undefined) return;
    priceEl.textContent = 'From Rs' + Math.round(Number(price)).toLocaleString('en-IN');

    const mrpEl = document.getElementById(cardUid + '-mrp');
    const mrp = Number(option?.dataset.mrp || 0);
    if (mrpEl) {
        if (mrp > Number(price)) {
            mrpEl.textContent = 'Rs' + Math.round(mrp).toLocaleString('en-IN');
            mrpEl.classList.remove('hidden');
        } else {
            mrpEl.classList.add('hidden');
        }
    }
}
function addProductCardToCart(productId, cardUid) {
    const control = document.getElementById(cardUid);
    const variantIndex = control && control.value !== '' ? parseInt(control.value, 10) : null;
    addToCart(productId, variantIndex, 'today');
}
let selectedCartDrawerDay = 'today';

function toggleCartDropdown(panelId, chevronId) {
    const panel = document.getElementById(panelId);
    const chevron = document.getElementById(chevronId);
    if (!panel) return;

    const willOpen = panel.classList.contains('hidden');
    panel.classList.toggle('hidden', !willOpen);

    if (panelId === 'drawer-delivery-slot-options') {
        panel.classList.toggle('flex', willOpen);
    }

    chevron?.classList.toggle('rotate-180', willOpen);
}

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
    const wrap = document.getElementById('cart-coupon-wrap');
    if (!panel) return;
    const applied = data.applied_coupon;
    const available = data.available_coupons || [];
    wrap?.classList.toggle('hidden', !applied && !available.length);
    panel.classList.add('hidden');
    document.getElementById('cart-coupon-chevron')?.classList.remove('rotate-180');
    if (applied) {
        panel.innerHTML = `<div class="flex items-center justify-between gap-3">
            <div><p class="text-[10px] font-black uppercase tracking-wider text-emerald-600">Applied</p><p class="text-xs font-bold text-slate-800">${escapeHtml(applied.title)}</p></div>
            <button type="button" onclick="removeCartCoupon()" class="text-[11px] font-black text-red-500">Remove</button>
        </div>`;
        return;
    }
    panel.innerHTML = `<p class="mb-2 text-[10px] font-black uppercase tracking-wider text-amber-500">Available offers</p>` +
        available.map(coupon => `<div class="mb-2 flex items-center justify-between gap-2 rounded-xl bg-white p-2 last:mb-0">
            <div class="min-w-0"><p class="truncate text-xs font-bold text-slate-800">${escapeHtml(coupon.title)}</p><p class="text-[10px] text-slate-500">${coupon.type === 'percent' ? coupon.value + '%' : '₹' + coupon.value} off</p></div>
            <button type="button" onclick="applyCartCoupon('${escapeHtml(coupon.code)}')" class="rounded-lg bg-amber-600 px-3 py-1.5 text-[10px] font-black text-white">Apply</button>
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
                        <a href="${item.product_url}" class="line-clamp-2 text-[13px] font-bold leading-4 text-slate-900 hover:text-amber-500">
                            ${item.name}
                        </a>
                    </div>
                    <div class="mt-0.5 flex flex-wrap items-center gap-1">
                        <span class="text-[10px] font-semibold uppercase tracking-[0.12em] text-slate-400">
                            ${item.variant_label || item.unit || 'Fresh Cut'}
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
                                <button onclick="updateCartDrawerQty('${item.key}', ${item.quantity + 1})" class="flex h-7 w-7 items-center justify-center text-gray-500 transition hover:bg-gray-50 hover:text-amber-600">
                                    <i class="fa-solid fa-plus text-[8px]"></i>
                                </button>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <p class="text-xs font-extrabold text-amber-500">${formatCartCurrency(item.subtotal)}</p>
                            <button type="button" onclick="removeCartDrawerItem('${item.key}')" class="inline-flex items-center gap-1 rounded-md bg-red-500 px-2.5 py-1.5 text-[9px] font-black uppercase tracking-wide text-white transition hover:bg-red-600" title="Remove item">
                                <i class="fa-solid fa-trash-can"></i>
                                Remove
                            </button>
                        </div>
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
            ? 'border-amber-600 bg-white text-amber-500 shadow-sm'
            : 'border-amber-100 bg-white/80 text-slate-600';

        return `
            <button
                type="button"
                onclick="updateCartDrawerDeliverySlot('${escapeHtml(slot.value)}')"
                data-drawer-delivery-slot="${escapeHtml(slot.value)}"
                class="drawer-delivery-slot-chip ${classes} rounded-full border px-2.5 py-2 text-[10px] font-bold leading-none transition hover:border-amber-400 hover:text-amber-500"
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
            const uniqueCount = data.unique_count ?? data.count;
            const totalQty = data.count;
            updateHeaderCartBadge(uniqueCount);
            document.getElementById('cart-badge-drawer').textContent = uniqueCount + ' item' + (uniqueCount !== 1 ? 's' : '');
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
                tab.classList.toggle('bg-amber-600', isActive);
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
                chip.classList.toggle('border-amber-600', isActive);
                chip.classList.toggle('bg-white', isActive);
                chip.classList.toggle('text-amber-500', isActive);
                chip.classList.toggle('shadow-sm', isActive);
                chip.classList.toggle('border-amber-100', !isActive);
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

// Display every visible rupee amount consistently with the Indian rupee symbol.
function formatVisibleRupeeSymbols(root = document.body) {
    const walker = document.createTreeWalker(root, NodeFilter.SHOW_TEXT, {
        acceptNode(node) {
            const parentTag = node.parentElement?.tagName;

            return ['SCRIPT', 'STYLE', 'NOSCRIPT'].includes(parentTag)
                ? NodeFilter.FILTER_REJECT
                : NodeFilter.FILTER_ACCEPT;
        },
    });

    const textNodes = [];
    while (walker.nextNode()) textNodes.push(walker.currentNode);

    textNodes.forEach((node) => {
        node.nodeValue = node.nodeValue.replace(/\bRs\.?\s*(?=\d)/g, '₹');
    });
}

function syncHeaderOffset() {
    const header = document.getElementById('siteHeader');
    const wrapper = document.getElementById('pageContentWrapper');
    if (!header || !wrapper) return;

    wrapper.style.paddingTop = header.offsetHeight + 'px';
}

window.addEventListener('load', syncHeaderOffset);
window.addEventListener('resize', syncHeaderOffset);
document.addEventListener('DOMContentLoaded', syncHeaderOffset);

document.addEventListener('DOMContentLoaded', () => {
    const accountBlockedModal = document.getElementById('accountBlockedModal');
    if (accountBlockedModal) {
        accountBlockedModal.classList.remove('hidden');
        accountBlockedModal.classList.add('flex');
        accountBlockedModal.querySelector('[data-close-account-blocked]')?.addEventListener('click', () => {
            accountBlockedModal.classList.add('hidden');
            accountBlockedModal.classList.remove('flex');
        });
    }

    formatVisibleRupeeSymbols();

    document.querySelectorAll('input[type="password"]').forEach((input) => {
        const parent = input.parentElement;

        // Password fields that already provide their own toggle (for example,
        // the signup form) are left unchanged.
        if (parent.querySelector('button')) {
            return;
        }

        const parentStyle = window.getComputedStyle(parent);
        const wrapper = parentStyle.position === 'relative' ? parent : document.createElement('div');

        if (wrapper !== parent) {
            wrapper.style.position = 'relative';
            input.before(wrapper);
            wrapper.appendChild(input);
        }

        input.style.paddingRight = '3rem';

        const toggle = document.createElement('button');
        toggle.type = 'button';
        toggle.setAttribute('data-password-visibility-toggle', '');
        toggle.setAttribute('aria-label', 'Show password');
        toggle.setAttribute('aria-pressed', 'false');
        toggle.style.cssText = 'position:absolute;right:0.75rem;top:50%;transform:translateY(-50%);padding:0.35rem;color:#94a3b8;line-height:1;cursor:pointer;background:transparent;border:0;';
        toggle.innerHTML = '<i class="fa-regular fa-eye" aria-hidden="true"></i>';

        toggle.addEventListener('click', () => {
            const isHidden = input.type === 'password';
            input.type = isHidden ? 'text' : 'password';
            toggle.setAttribute('aria-label', isHidden ? 'Hide password' : 'Show password');
            toggle.setAttribute('aria-pressed', String(isHidden));
            toggle.innerHTML = `<i class="fa-regular ${isHidden ? 'fa-eye-slash' : 'fa-eye'}" aria-hidden="true"></i>`;
        });

        wrapper.appendChild(toggle);
    });

    new MutationObserver((mutations) => {
        mutations.forEach((mutation) => {
            mutation.addedNodes.forEach((node) => {
                if (node.nodeType === Node.ELEMENT_NODE || node.nodeType === Node.TEXT_NODE) {
                    formatVisibleRupeeSymbols(node.nodeType === Node.TEXT_NODE ? node.parentElement : node);
                }
            });
        });
    }).observe(document.body, { childList: true, subtree: true });
});

</script>
@yield('scripts')
</body>
</html>
