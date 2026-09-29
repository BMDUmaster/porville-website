<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>@yield('title', 'Dashboard') | Porville</title>
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
                        'porville-gold':  '#b8862c',
                        'porville-black': '#0d0d0d',
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
            background: linear-gradient(to right, #b8862c, #0d0d0d) !important;
            color: #ffffff !important;
            border-radius: 10px;
            box-shadow: 0 4px 14px rgba(184,134,44,0.35);
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

    <!-- Sidebar Overlay (mobile) -->
    <div id="sidebar-overlay" onclick="closeSidebar()"
         class="hidden fixed inset-0 bg-black/60 z-40 lg:hidden"></div>

    <!--SIDEBAr -->
    <aside id="main-sidebar"
           class="fixed top-0 left-0 w-72 bg-white border-r border-slate-200 h-screen overflow-y-auto custom-scrollbar z-50
                  transform transition-transform duration-300 lg:translate-x-0 -translate-x-full">

        <!-- Logo -->
        <div class="flex items-center justify-between border-b border-slate-200 bg-black p-4">
            <a href="{{ route('dashboard.home') }}" class="flex items-center gap-2.5">
                <img src="{{ $brandLogoUrl }}"
                     alt="Porville"
                     class="h-11 w-11 flex-shrink-0 rounded-full object-cover ring-1 ring-amber-500/40">
                <span class="flex flex-col leading-none">
                    <span class="text-lg font-extrabold tracking-wide text-amber-300">PORVILLE</span>
                    <span class="mt-1 text-[9px] font-bold uppercase tracking-[0.2em] text-stone-400">Admin</span>
                </span>
            </a>
            <button onclick="closeSidebar()" class="lg:hidden text-xl text-stone-400 hover:text-white">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <!-- Nav -->
        <nav class="px-4 py-3 space-y-1">

            <a href="{{ route('dashboard.home') }}"
               class="nav-link flex items-center gap-3 px-4 py-2.5 text-sm font-medium rounded-xl
                      hover:bg-gradient-to-r hover:from-amber-600 hover:to-black hover:text-white transition
                      {{ request()->routeIs('dashboard.home') ? 'active-link' : 'text-slate-700' }}">
                <i class="fa-solid fa-gauge w-5 text-amber-600"></i>
                <span>Dashboard</span>
            </a>

            <!-- Products Dropdown -->
            <div>
                <button onclick="toggleProductMenu()"
                        class="w-full flex items-center justify-between px-4 py-2.5 text-sm font-medium rounded-xl
                               hover:bg-gradient-to-r hover:from-amber-600 hover:to-black hover:text-white transition
                               {{ request()->routeIs('dashboard.categories','dashboard.subcategories','dashboard.products') ? 'active-link' : 'text-slate-700' }}">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-boxes-stacked w-5 text-amber-600"></i>
                        <span>Products</span>
                    </div>
                    <i id="productArrow" class="fa-solid fa-chevron-down text-xs transition-transform duration-300
                       {{ request()->routeIs('dashboard.categories','dashboard.subcategories','dashboard.products') ? 'rotate-180' : '' }}"></i>
                </button>

                <div id="productDropdown"
                     class="{{ request()->routeIs('dashboard.categories','dashboard.subcategories','dashboard.products') ? 'flex' : 'hidden' }}
                            flex-col pl-9 pr-2 py-2 space-y-1 bg-amber-50/50 rounded-xl mt-1 mx-2 border-l-2 border-amber-200">

                    <a href="{{ route('dashboard.categories') }}"
                       class="sidebar-sub-link flex items-center gap-3 px-4 py-2 text-xs font-semibold rounded-lg transition
                              hover:bg-porville-gold hover:text-white
                              {{ request()->routeIs('dashboard.categories') ? 'active-sub-link' : 'text-slate-700' }}">
                        <i class="fa-solid fa-sitemap w-4 text-amber-600"></i> Categories
                    </a>

                    <a href="{{ route('dashboard.subcategories') }}"
                       class="sidebar-sub-link flex items-center gap-3 px-4 py-2 text-xs font-semibold rounded-lg transition
                              hover:bg-porville-gold hover:text-white
                              {{ request()->routeIs('dashboard.subcategories') ? 'active-sub-link' : 'text-slate-700' }}">
                        <i class="fa-solid fa-layer-group w-4 text-amber-600"></i> Sub-Categories
                    </a>

                    <a href="{{ route('dashboard.products') }}"
                       class="sidebar-sub-link flex items-center gap-3 px-4 py-2 text-xs font-semibold rounded-lg transition
                              hover:bg-porville-gold hover:text-white
                              {{ request()->routeIs('dashboard.products') ? 'active-sub-link' : 'text-slate-700' }}">
                        <i class="fa-solid fa-circle-plus w-4 text-amber-600"></i> Products
                    </a>
                </div>
            </div>

            <a href="{{ route('dashboard.orders') }}"
               class="nav-link flex items-center gap-3 px-4 py-2.5 text-sm font-medium rounded-xl transition
                      hover:bg-gradient-to-r hover:from-amber-600 hover:to-black hover:text-white
                      {{ request()->routeIs('dashboard.orders') ? 'active-link' : 'text-slate-700' }}">
                <i class="fa-solid fa-bag-shopping w-5 text-amber-600"></i>
                <span>Orders</span>
            </a>

            <a href="{{ route('dashboard.orders.history') }}"
               class="nav-link flex items-center gap-3 px-4 py-2.5 text-sm font-medium rounded-xl transition
                      hover:bg-gradient-to-r hover:from-amber-600 hover:to-black hover:text-white
                      {{ request()->routeIs('dashboard.orders.history') ? 'active-link' : 'text-slate-700' }}">
                <i class="fa-solid fa-clock-rotate-left w-5 text-amber-600"></i>
                <span>Order History</span>
            </a>

            <a href="{{ route('dashboard.banners') }}"
               class="nav-link flex items-center gap-3 px-4 py-2.5 text-sm font-medium rounded-xl transition
                      hover:bg-gradient-to-r hover:from-amber-600 hover:to-black hover:text-white
                      {{ request()->routeIs('dashboard.banners*') ? 'active-link' : 'text-slate-700' }}">
                <i class="fa-regular fa-images w-5 text-amber-600"></i>
                <span>Home Banners</span>
            </a>

            <a href="{{ route('dashboard.contact-messages') }}"
               class="nav-link flex items-center gap-3 px-4 py-2.5 text-sm font-medium rounded-xl transition
                      hover:bg-gradient-to-r hover:from-amber-600 hover:to-black hover:text-white
                      {{ request()->routeIs('dashboard.contact-messages*') ? 'active-link' : 'text-slate-700' }}">
                <i class="fa-regular fa-envelope w-5 text-amber-600"></i>
                <span>Contact Us</span>
                <span id="contactSidebarUnreadBadge" class="ml-auto hidden min-w-5 rounded-full bg-red-500 px-1.5 py-0.5 text-center text-[10px] font-bold text-white"></span>
            </a>

            <a href="{{ route('dashboard.reviews') }}"
               class="nav-link flex items-center gap-3 px-4 py-2.5 text-sm font-medium rounded-xl transition
                      hover:bg-gradient-to-r hover:from-amber-600 hover:to-black hover:text-white
                      {{ request()->routeIs('dashboard.reviews*') ? 'active-link' : 'text-slate-700' }}">
                <i class="fa-solid fa-star w-5 text-amber-600"></i>
                <span>Reviews</span>
            </a>

            <a href="{{ route('dashboard.users') }}"
               class="nav-link flex items-center gap-3 px-4 py-2.5 text-sm font-medium rounded-xl transition
                      hover:bg-gradient-to-r hover:from-amber-600 hover:to-black hover:text-white
                      {{ request()->routeIs('dashboard.users*') ? 'active-link' : 'text-slate-700' }}">
                <i class="fa-solid fa-user w-5 text-amber-600"></i>
                <span>Customers</span>
            </a>

            <a href="{{ route('dashboard.delivery-boys') }}"
               class="nav-link flex items-center gap-3 px-4 py-2.5 text-sm font-medium rounded-xl transition
                      hover:bg-gradient-to-r hover:from-amber-600 hover:to-black hover:text-white
                      {{ request()->routeIs('dashboard.delivery-boys*') ? 'active-link' : 'text-slate-700' }}">
                <i class="fa-solid fa-motorcycle w-5 text-amber-600"></i>
                <span>Delivery Boys</span>
            </a>

            <a href="{{ route('dashboard.notifications') }}"
               class="nav-link flex items-center gap-3 px-4 py-2.5 text-sm font-medium rounded-xl transition
                      hover:bg-gradient-to-r hover:from-amber-600 hover:to-black hover:text-white
                      {{ request()->routeIs('dashboard.notifications') ? 'active-link' : 'text-slate-700' }}">
                <i class="fa-regular fa-bell w-5 text-amber-600"></i>
                <span>Notifications</span>
            </a>

            <a href="{{ route('dashboard.coupons') }}"
               class="nav-link flex items-center gap-3 px-4 py-2.5 text-sm font-medium rounded-xl transition
                      hover:bg-gradient-to-r hover:from-amber-600 hover:to-black hover:text-white
                      {{ request()->routeIs('dashboard.coupons') ? 'active-link' : 'text-slate-700' }}">
                <i class="fa-solid fa-tags w-5 text-amber-600"></i>
                <span>Coupons & Offers</span>
            </a>

            <a href="{{ route('dashboard.settings.delivery-slots') }}"
               class="nav-link flex items-center gap-3 px-4 py-2.5 text-sm font-medium rounded-xl transition
                      hover:bg-gradient-to-r hover:from-amber-600 hover:to-black hover:text-white
                      {{ request()->routeIs('dashboard.settings.delivery-slots') ? 'active-link' : 'text-slate-700' }}">
                <i class="fa-regular fa-clock w-5 text-amber-600"></i>
                <span>Delivery Slots</span>
            </a>

            <a href="{{ route('dashboard.faqs') }}"
               class="nav-link flex items-center gap-3 px-4 py-2.5 text-sm font-medium rounded-xl transition
                      hover:bg-gradient-to-r hover:from-amber-600 hover:to-black hover:text-white
                      {{ request()->routeIs('dashboard.faqs') ? 'active-link' : 'text-slate-700' }}">
                <i class="fa-solid fa-circle-question w-5 text-amber-600"></i>
                <span>FAQs</span>
            </a>

            <a href="{{ route('dashboard.seo') }}"
               class="nav-link flex items-center gap-3 px-4 py-2.5 text-sm font-medium rounded-xl transition
                      hover:bg-gradient-to-r hover:from-amber-600 hover:to-black hover:text-white
                      {{ request()->routeIs('dashboard.seo*') ? 'active-link' : 'text-slate-700' }}">
                <i class="fa-solid fa-magnifying-glass-chart w-5 text-amber-600"></i>
                <span>SEO Management</span>
            </a>

            <a href="{{ route('dashboard.profile') }}"
               class="nav-link flex items-center gap-3 px-4 py-2.5 text-sm font-medium rounded-xl transition
                      hover:bg-gradient-to-r hover:from-amber-600 hover:to-black hover:text-white
                      {{ request()->routeIs('dashboard.profile') ? 'active-link' : 'text-slate-700' }}">
                <i class="fa-solid fa-circle-user w-5 text-amber-600"></i>
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
                            class="lg:hidden w-10 h-10 flex items-center justify-center bg-slate-100 hover:bg-porville-gold hover:text-white rounded-xl transition">
                        <i class="fa-solid fa-bars text-lg"></i>
                    </button>
                    <h1 class="text-xl font-bold text-slate-800">@yield('page_title', 'Dashboard')</h1>
                </div>

                <div class="flex items-center gap-3">
                    <a href="{{ route('dashboard.notifications') }}"
                       class="relative w-10 h-10 flex items-center justify-center bg-slate-100 rounded-xl hover:bg-porville-gold hover:text-white transition">
                        <i class="fa-regular fa-bell text-lg"></i>
                        <span class="absolute top-1 right-1 w-2.5 h-2.5 bg-red-500 rounded-full"></span>
                    </a>

                    <div class="relative">
                        <button onclick="toggleProfile()" class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-black ring-1 ring-amber-500/40 flex items-center justify-center text-amber-400 font-bold text-sm">
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
                    &copy; {{ date('Y') }} <span class="text-porville-gold font-bold">Porville</span>. All rights reserved.
                </p>
                <div class="flex items-center gap-2 px-4 py-1.5 bg-slate-50 rounded-full border border-slate-100">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Design & Developed by</span>
                    <span class="text-xs font-black text-porville-black">BMDU</span>
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
