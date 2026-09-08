<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>@yield('title', 'Dashboard') | FarmSea</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!--Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'mayview-blue':   '#1e40af',
                        'farmsea-green':  '#16a34a',
                        'mayview-dark':   '#0f172a',
                        'mayview-accent': '#3b82f6',
                        'admin-orange':  '#f97316',
                    },
                    fontFamily: { sans: ['Poppins', 'sans-serif'] }
                }
            }
        }
    </script>

    <style>
        body { font-family: 'Poppins', sans-serif; }
        body.sidebar-open { overflow: hidden; }
        html, body { overflow-x: hidden; }
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #475569; border-radius: 3px; }
        .nav-link,
        .sidebar-sub-link {
            position: relative;
        }
        .active-link {
            background: linear-gradient(to right, #3b82f6, #6366f1) !important;
            color: #ffffff !important;
            border-radius: 10px;
            box-shadow: 0 4px 14px rgba(59,130,246,0.35);
        }
        .active-link::before,
        .active-sub-link::before {
            content: "";
            position: absolute;
            left: 6px;
            top: 50%;
            width: 4px;
            height: 22px;
            border-radius: 999px;
            background: #000000;
            transform: translateY(-50%);
        }
        .active-link i { color: #ffffff !important; }
        .active-sub-link {
            background: #111827 !important;
            color: #ffffff !important;
        }
        .active-sub-link i { color: #ffffff !important; }
        /* Responsive table fix */
        @media (max-width: 768px) {
            table { font-size: 12px; }
            .lg\:ml-72 { margin-left: 0 !important; }
        }
        /* Prevent icon overflow in cards */
        .stat-card { overflow: hidden; }
        .stat-card .icon-box { flex-shrink: 0; }
        @media print {
            #main-sidebar,
            header,
            #sidebar-overlay,
            .print\:hidden {
                display: none !important;
            }
            .lg\:ml-72 {
                margin-left: 0 !important;
            }
            main {
                margin-top: 0 !important;
                padding-top: 0 !important;
            }
            body {
                background: white !important;
                color: black !important;
            }
        }
        @yield('styles')
    </style>
</head>
<body class="bg-slate-50 text-slate-900 antialiased">

    <!-- Page Refresh / Navigation Top Progress Bar & Loader -->
    <div id="topProgressBar" class="fixed top-0 left-0 z-[9999] h-1 w-0 bg-gradient-to-r from-blue-600 via-green-500 to-amber-400 shadow-[0_0_12px_rgba(37,99,235,0.9)] transition-all duration-300 pointer-events-none"></div>

    <div id="pageLoaderPill" class="fixed top-4 right-4 z-[9999] hidden items-center gap-2.5 rounded-full bg-slate-900/90 px-4 py-2 text-white shadow-2xl backdrop-blur-md transition-all duration-300 pointer-events-none border border-white/10">
        <i class="fa-solid fa-circle-notch fa-spin text-sm text-green-400"></i>
        <span class="text-xs font-bold tracking-wide">Loading...</span>
    </div>

    <!-- Sidebar Overlay (mobile) -->
    <div id="sidebar-overlay" onclick="closeSidebar()"
         class="hidden fixed inset-0 bg-black/60 z-40 lg:hidden"></div>

    <!--SIDEBAr -->
    <aside id="main-sidebar"
           class="fixed top-0 left-0 w-72 bg-white border-r border-slate-200 h-screen overflow-y-auto custom-scrollbar z-50
                  transform transition-transform duration-300 lg:translate-x-0 -translate-x-full">

        <!-- Logo -->
        <div class="p-4 border-b border-slate-200 bg-white flex items-center justify-between">
            <a href="{{ route('dashboard.home') }}" class="flex items-center">
                <img src="{{ $brandLogoUrl }}"
                     alt="FarmSea"
                     class="h-20 w-auto max-w-full object-contain"
                     onerror="this.remove(); document.getElementById('logo-fallback').style.display='flex'">
                <span id="logo-fallback" class="hidden items-center gap-2 text-xl font-extrabold text-green-700 tracking-tight">
                    🌿 FarmSea
                </span>
            </a>
            <button onclick="closeSidebar()" class="lg:hidden text-gray-500 hover:text-gray-700 text-xl">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <!-- Nav -->
        <nav class="px-4 py-3 space-y-1">

            <a href="{{ route('dashboard.home') }}"
               class="nav-link flex items-center gap-3 px-4 py-2.5 text-sm font-medium rounded-xl
                      hover:bg-gradient-to-r hover:from-blue-500 hover:to-indigo-600 hover:text-white transition
                      {{ request()->routeIs('dashboard.home') ? 'active-link' : 'text-slate-700' }}">
                <i class="fa-solid fa-gauge w-5 text-green-700"></i>
                <span>Dashboard</span>
            </a>

            <!-- Products Dropdown -->
            <div>
                <button onclick="toggleProductMenu()"
                        class="w-full flex items-center justify-between px-4 py-2.5 text-sm font-medium rounded-xl
                               hover:bg-gradient-to-r hover:from-blue-500 hover:to-indigo-600 hover:text-white transition
                               {{ request()->routeIs('dashboard.categories','dashboard.subcategories','dashboard.products') ? 'active-link' : 'text-slate-700' }}">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-boxes-stacked w-5 text-green-700"></i>
                        <span>Products</span>
                    </div>
                    <i id="productArrow" class="fa-solid fa-chevron-down text-xs transition-transform duration-300
                       {{ request()->routeIs('dashboard.categories','dashboard.subcategories','dashboard.products') ? 'rotate-180' : '' }}"></i>
                </button>

                <div id="productDropdown"
                     class="{{ request()->routeIs('dashboard.categories','dashboard.subcategories','dashboard.products') ? 'flex' : 'hidden' }}
                            flex-col pl-9 pr-2 py-2 space-y-1 bg-green-50/50 rounded-xl mt-1 mx-2 border-l-2 border-green-200">

                    <a href="{{ route('dashboard.categories') }}"
                       class="sidebar-sub-link flex items-center gap-3 px-4 py-2 text-xs font-semibold rounded-lg transition
                              hover:bg-blue-500 hover:text-white
                              {{ request()->routeIs('dashboard.categories') ? 'active-sub-link' : 'text-slate-700' }}">
                        <i class="fa-solid fa-sitemap w-4 text-green-600"></i> Categories
                    </a>

                    <a href="{{ route('dashboard.subcategories') }}"
                       class="sidebar-sub-link flex items-center gap-3 px-4 py-2 text-xs font-semibold rounded-lg transition
                              hover:bg-blue-500 hover:text-white
                              {{ request()->routeIs('dashboard.subcategories') ? 'active-sub-link' : 'text-slate-700' }}">
                        <i class="fa-solid fa-layer-group w-4 text-green-600"></i> Sub-Categories
                    </a>

                    <a href="{{ route('dashboard.products') }}"
                       class="sidebar-sub-link flex items-center gap-3 px-4 py-2 text-xs font-semibold rounded-lg transition
                              hover:bg-blue-500 hover:text-white
                              {{ request()->routeIs('dashboard.products') ? 'active-sub-link' : 'text-slate-700' }}">
                        <i class="fa-solid fa-circle-plus w-4 text-green-600"></i> Products
                    </a>
                </div>
            </div>

            <a href="{{ route('dashboard.orders') }}"
               class="nav-link flex items-center gap-3 px-4 py-2.5 text-sm font-medium rounded-xl transition
                      hover:bg-gradient-to-r hover:from-blue-500 hover:to-indigo-600 hover:text-white
                      {{ request()->routeIs('dashboard.orders') ? 'active-link' : 'text-slate-700' }}">
                <i class="fa-solid fa-bag-shopping w-5 text-green-700"></i>
                <span>Orders</span>
            </a>

            <a href="{{ route('dashboard.orders.history') }}"
               class="nav-link flex items-center gap-3 px-4 py-2.5 text-sm font-medium rounded-xl transition
                      hover:bg-gradient-to-r hover:from-blue-500 hover:to-indigo-600 hover:text-white
                      {{ request()->routeIs('dashboard.orders.history') ? 'active-link' : 'text-slate-700' }}">
                <i class="fa-solid fa-clock-rotate-left w-5 text-green-700"></i>
                <span>Order History</span>
            </a>

            <a href="{{ route('dashboard.banners') }}"
               class="nav-link flex items-center gap-3 px-4 py-2.5 text-sm font-medium rounded-xl transition
                      hover:bg-gradient-to-r hover:from-blue-500 hover:to-indigo-600 hover:text-white
                      {{ request()->routeIs('dashboard.banners*') ? 'active-link' : 'text-slate-700' }}">
                <i class="fa-regular fa-images w-5 text-green-700"></i>
                <span>Home Banners</span>
            </a>

            <a href="{{ route('dashboard.contact-messages') }}"
               class="nav-link flex items-center gap-3 px-4 py-2.5 text-sm font-medium rounded-xl transition
                      hover:bg-gradient-to-r hover:from-blue-500 hover:to-indigo-600 hover:text-white
                      {{ request()->routeIs('dashboard.contact-messages*') ? 'active-link' : 'text-slate-700' }}">
                <i class="fa-regular fa-envelope w-5 text-green-700"></i>
                <span>Contact Us</span>
                <span id="contactSidebarUnreadBadge" class="ml-auto hidden min-w-5 rounded-full bg-red-500 px-1.5 py-0.5 text-center text-[10px] font-bold text-white"></span>
            </a>

            <a href="{{ route('dashboard.reviews') }}"
               class="nav-link flex items-center gap-3 px-4 py-2.5 text-sm font-medium rounded-xl transition
                      hover:bg-gradient-to-r hover:from-blue-500 hover:to-indigo-600 hover:text-white
                      {{ request()->routeIs('dashboard.reviews*') ? 'active-link' : 'text-slate-700' }}">
                <i class="fa-solid fa-star w-5 text-green-700"></i>
                <span>Reviews</span>
            </a>

            <a href="{{ route('dashboard.users') }}"
               class="nav-link flex items-center gap-3 px-4 py-2.5 text-sm font-medium rounded-xl transition
                      hover:bg-gradient-to-r hover:from-blue-500 hover:to-indigo-600 hover:text-white
                      {{ request()->routeIs('dashboard.users*') ? 'active-link' : 'text-slate-700' }}">
                <i class="fa-solid fa-user w-5 text-green-700"></i>
                <span>Customers</span>
            </a>

            <a href="{{ route('dashboard.delivery-boys') }}"
               class="nav-link flex items-center gap-3 px-4 py-2.5 text-sm font-medium rounded-xl transition
                      hover:bg-gradient-to-r hover:from-blue-500 hover:to-indigo-600 hover:text-white
                      {{ request()->routeIs('dashboard.delivery-boys*') ? 'active-link' : 'text-slate-700' }}">
                <i class="fa-solid fa-motorcycle w-5 text-green-700"></i>
                <span>Delivery Boys</span>
            </a>

            <a href="{{ route('dashboard.notifications') }}"
               class="nav-link flex items-center gap-3 px-4 py-2.5 text-sm font-medium rounded-xl transition
                      hover:bg-gradient-to-r hover:from-blue-500 hover:to-indigo-600 hover:text-white
                      {{ request()->routeIs('dashboard.notifications') ? 'active-link' : 'text-slate-700' }}">
                <i class="fa-regular fa-bell w-5 text-green-700"></i>
                <span>Notifications</span>
            </a>

            <a href="{{ route('dashboard.coupons') }}"
               class="nav-link flex items-center gap-3 px-4 py-2.5 text-sm font-medium rounded-xl transition
                      hover:bg-gradient-to-r hover:from-blue-500 hover:to-indigo-600 hover:text-white
                      {{ request()->routeIs('dashboard.coupons') ? 'active-link' : 'text-slate-700' }}">
                <i class="fa-solid fa-tags w-5 text-green-700"></i>
                <span>Coupons & Offers</span>
            </a>

            <a href="{{ route('dashboard.settings.service-charge') }}"
               class="nav-link flex items-center gap-3 px-4 py-2.5 text-sm font-medium rounded-xl transition
                      hover:bg-gradient-to-r hover:from-blue-500 hover:to-indigo-600 hover:text-white
                      {{ request()->routeIs('dashboard.settings.service-charge') ? 'active-link' : 'text-slate-700' }}">
                <i class="fa-solid fa-percent w-5 text-green-700"></i>
                <span>&#8505;&#65039; Service Charge</span>
            </a>

            <a href="{{ route('dashboard.settings.delivery-slots') }}"
               class="nav-link flex items-center gap-3 px-4 py-2.5 text-sm font-medium rounded-xl transition
                      hover:bg-gradient-to-r hover:from-blue-500 hover:to-indigo-600 hover:text-white
                      {{ request()->routeIs('dashboard.settings.delivery-slots') ? 'active-link' : 'text-slate-700' }}">
                <i class="fa-regular fa-clock w-5 text-green-700"></i>
                <span>Delivery Slots</span>
            </a>

            <a href="{{ route('dashboard.settings.delivery-areas') }}"
               class="nav-link flex items-center gap-3 px-4 py-2.5 text-sm font-medium rounded-xl transition
                      hover:bg-gradient-to-r hover:from-blue-500 hover:to-indigo-600 hover:text-white
                      {{ request()->routeIs('dashboard.settings.delivery-areas') ? 'active-link' : 'text-slate-700' }}">
                <i class="fa-solid fa-location-dot w-5 text-green-700"></i>
                <span>Delivery Areas</span>
            </a>

            <a href="{{ route('dashboard.settings.ordering') }}"
               class="nav-link flex items-center gap-3 px-4 py-2.5 text-sm font-medium rounded-xl transition
                      hover:bg-gradient-to-r hover:from-blue-500 hover:to-indigo-600 hover:text-white
                      {{ request()->routeIs('dashboard.settings.ordering') ? 'active-link' : 'text-slate-700' }}">
                <i class="fa-solid fa-toggle-on w-5 text-green-700"></i>
                <span>Order On/Off</span>
            </a>

            <a href="{{ route('dashboard.profile') }}"
               class="nav-link flex items-center gap-3 px-4 py-2.5 text-sm font-medium rounded-xl transition
                      hover:bg-gradient-to-r hover:from-blue-500 hover:to-indigo-600 hover:text-white
                      {{ request()->routeIs('dashboard.profile') ? 'active-link' : 'text-slate-700' }}">
                <i class="fa-solid fa-circle-user w-5 text-green-700"></i>
                <span>Profile</span>
            </a>

            
        </nav>
    </aside>

    <!-- HEADER -->
    <div class="lg:ml-72">
        <header class="bg-white shadow-sm border-b border-slate-200 lg:fixed lg:top-0 lg:right-0 lg:left-72 z-30">
            <div class="px-4 sm:px-6 lg:px-8 py-4 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <button onclick="toggleSidebar()"
                            class="lg:hidden w-10 h-10 flex items-center justify-center bg-slate-100 hover:bg-mayview-blue hover:text-white rounded-xl transition">
                        <i class="fa-solid fa-bars text-lg"></i>
                    </button>
                    <h1 class="text-xl font-bold text-slate-800">@yield('page_title', 'Dashboard')</h1>
                </div>

                <div class="flex items-center gap-3">
                    <a href="{{ route('dashboard.notifications') }}"
                       class="relative w-10 h-10 flex items-center justify-center bg-slate-100 rounded-xl hover:bg-mayview-blue hover:text-white transition">
                        <i class="fa-regular fa-bell text-lg"></i>
                        <span class="absolute top-1 right-1 w-2.5 h-2.5 bg-red-500 rounded-full"></span>
                    </a>

                    <div class="relative">
                        <button onclick="toggleProfile()" class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-green-500 flex items-center justify-center text-white font-bold text-sm">
                                {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                            </div>
                            <div class="hidden sm:block text-left">
                                <p class="text-sm font-semibold text-slate-800">{{ auth()->user()->name ?? 'Admin' }}</p>
                                <p class="text-xs text-slate-500 capitalize">{{ auth()->user()->role ?? 'admin' }}</p>
                            </div>
                        </button>
                        <!-- userprofile -->
                        <div id="profileModal"
                             class="hidden absolute right-0 mt-3 w-56 bg-white rounded-xl shadow-2xl border border-slate-200 p-4 z-50">
                            <button onclick="closeProfile()" class="absolute top-3 right-4 text-slate-400 hover:text-red-500">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                            <div class="pb-3 border-b mb-3">
                                <p class="font-semibold text-slate-800">{{ auth()->user()->name ?? 'Admin' }}</p>
                                <p class="text-xs text-slate-500">{{ auth()->user()->email ?? '' }}</p>
                            </div>
                            <a href="{{ route('dashboard.profile') }}"
                               class="flex items-center gap-2 px-3 py-2 text-sm text-slate-700 hover:bg-slate-100 rounded-lg">
                                <i class="fa-solid fa-user-gear"></i> Profile Settings
                            </a>
                            <form method="POST" action="{{ route('dashboard.logout') }}">
                                @csrf
                                <button type="submit"
                                        class="w-full flex items-center gap-2 px-3 py-2 text-sm text-red-600 hover:bg-red-50 rounded-lg">
                                    <i class="fa-solid fa-right-from-bracket"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- MAIN CONTENT  -->
        <main class="lg:mt-[4.5rem] min-h-screen">
            @if(session('success'))
                <div data-auto-dismiss="3000" class="mx-6 mt-4 flex items-center gap-2 rounded-lg bg-green-100 p-3 text-sm text-green-700 transition-all duration-500">
                    <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mx-6 mt-4 flex items-center gap-2 rounded-lg bg-red-100 p-3 text-sm text-red-700">
                    <i class="fa-solid fa-circle-xmark"></i> {{ session('error') }}
                </div>
            @endif
            @if(isset($errors) && $errors->any())
                <div class="mx-6 mt-4 rounded-lg bg-red-100 p-3 text-sm text-red-700">
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="bg-white border-t border-slate-100 px-8 py-3">
            <div class="flex flex-col md:flex-row justify-between items-center gap-2">
                <p class="text-sm text-slate-500">
                    &copy; {{ date('Y') }} <span class="text-farmsea-green font-bold">FarmSea</span>. All rights reserved.
                </p>
                <div class="flex items-center gap-2 px-4 py-1.5 bg-slate-50 rounded-full border border-slate-100">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Design & Developed by</span>
                    <span class="text-xs font-black text-mayview-dark">BMDU</span>
                    <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                </div>
            </div>
        </footer>
    </div>

    <script>
        document.querySelectorAll('[data-auto-dismiss]').forEach((notice) => {
            const delay = Number(notice.dataset.autoDismiss || 3000);

            window.setTimeout(() => {
                notice.classList.add('opacity-0', '-translate-y-2');

                window.setTimeout(() => {
                    notice.remove();
                }, 500);
            }, delay);
        });

        function toggleSidebar() {
            document.getElementById('main-sidebar').classList.toggle('-translate-x-full');
            document.getElementById('sidebar-overlay').classList.toggle('hidden');
            document.body.classList.toggle('sidebar-open');
        }
        function closeSidebar() {
            document.getElementById('main-sidebar').classList.add('-translate-x-full');
            document.getElementById('sidebar-overlay').classList.add('hidden');
            document.body.classList.remove('sidebar-open');
        }
        function toggleProfile() {
            document.getElementById('profileModal').classList.toggle('hidden');
        }
        function closeProfile() {
            document.getElementById('profileModal').classList.add('hidden');
        }
        function toggleProductMenu() {
            const menu  = document.getElementById('productDropdown');
            const arrow = document.getElementById('productArrow');
            menu.classList.toggle('hidden');
            menu.classList.toggle('flex');
            arrow.classList.toggle('rotate-180');
        }
        document.addEventListener('click', function(e) {
            const modal = document.getElementById('profileModal');
            if (modal && !modal.contains(e.target) && !e.target.closest('[onclick="toggleProfile()"]')) {
                modal.classList.add('hidden');
            }
        });
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') { closeSidebar(); closeProfile(); }
        });

        document.querySelectorAll('input[type="password"]').forEach((input) => {
            const parent = input.parentElement;

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

        (() => {
            const badge = document.getElementById('contactSidebarUnreadBadge');
            if (!badge) return;

            function refreshContactBadge() {
                fetch(@json(route('dashboard.contact-messages.live')), {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                })
                    .then(response => response.ok ? response.json() : null)
                    .then(data => {
                        if (!data) return;
                        const unread = Number(data.unread || 0);
                        badge.textContent = unread > 99 ? '99+' : unread;
                        badge.classList.toggle('hidden', unread <= 0);
                    })
                    .catch(() => {});
            }

            refreshContactBadge();
            window.setInterval(refreshContactBadge, 10000);
        })();

        // Page Navigation / Refresh Loader
        (function() {
            const bar = document.getElementById('topProgressBar');
            const pill = document.getElementById('pageLoaderPill');
            let progressTimer = null;
            let currentProgress = 0;

            function startLoader() {
                if (!bar) return;
                bar.style.opacity = '1';
                if (pill) { pill.classList.remove('hidden'); pill.classList.add('flex'); }

                currentProgress = 15;
                bar.style.width = currentProgress + '%';

                clearInterval(progressTimer);
                progressTimer = setInterval(() => {
                    if (currentProgress < 85) {
                        currentProgress += Math.random() * 15;
                        bar.style.width = currentProgress + '%';
                    }
                }, 150);
            }

            function completeLoader() {
                if (!bar) return;
                clearInterval(progressTimer);
                bar.style.width = '100%';
                setTimeout(() => {
                    bar.style.opacity = '0';
                    setTimeout(() => {
                        bar.style.width = '0%';
                        if (pill) { pill.classList.add('hidden'); pill.classList.remove('flex'); }
                    }, 300);
                }, 200);
            }

            document.addEventListener('click', (e) => {
                const link = e.target.closest('a');
                if (!link) return;

                const href = link.getAttribute('href');
                const target = link.getAttribute('target');

                if (href && !href.startsWith('#') && !href.startsWith('javascript:') && !href.startsWith('tel:') && !href.startsWith('mailto:') && target !== '_blank') {
                    if (link.hostname === window.location.hostname) {
                        startLoader();
                    }
                }
            });

            document.addEventListener('submit', () => {
                startLoader();
            });

            window.addEventListener('beforeunload', () => {
                startLoader();
            });

            if (document.readyState === 'complete') {
                completeLoader();
            } else {
                window.addEventListener('load', completeLoader);
                document.addEventListener('DOMContentLoaded', () => setTimeout(completeLoader, 100));
            }
        })();

        // Scroll Position Preservation for Admin Dashboard
        (function() {
            const pagePath = window.location.pathname;
            const storageKey = 'admin_scroll_pos_' + pagePath + (window.location.search || '');
            const fallbackKey = 'admin_scroll_pos_' + pagePath;

            const savedPos = sessionStorage.getItem(storageKey) || sessionStorage.getItem(fallbackKey);
            if (savedPos !== null) {
                const targetY = parseInt(savedPos, 10);
                if (targetY > 0) {
                    const restoreScroll = () => {
                        window.scrollTo({ top: targetY, behavior: 'instant' });
                    };
                    if (document.readyState === 'loading') {
                        document.addEventListener('DOMContentLoaded', () => setTimeout(restoreScroll, 50));
                    } else {
                        setTimeout(restoreScroll, 50);
                    }
                }
            }

            let scrollTimer;
            window.addEventListener('scroll', () => {
                clearTimeout(scrollTimer);
                scrollTimer = setTimeout(() => {
                    sessionStorage.setItem(storageKey, window.scrollY);
                    sessionStorage.setItem(fallbackKey, window.scrollY);
                }, 80);
            }, { passive: true });

            document.addEventListener('submit', () => {
                sessionStorage.setItem(storageKey, window.scrollY);
                sessionStorage.setItem(fallbackKey, window.scrollY);
            });
        })();
    </script>

    @yield('scripts')
</body>
</html>
