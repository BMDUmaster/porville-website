<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title><?php echo $__env->yieldContent('title', 'Dashboard'); ?> | FarmSea</title>
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

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
        .active-link {
            background: linear-gradient(to right, #3b82f6, #6366f1) !important;
            color: #ffffff !important;
            border-radius: 10px;
            box-shadow: 0 4px 14px rgba(59,130,246,0.35);
        }
        .active-link i { color: #ffffff !important; }
        /* Responsive table fix */
        @media (max-width: 768px) {
            table { font-size: 12px; }
            .lg\:ml-72 { margin-left: 0 !important; }
        }
        /* Prevent icon overflow in cards */
        .stat-card { overflow: hidden; }
        .stat-card .icon-box { flex-shrink: 0; }
        <?php echo $__env->yieldContent('styles'); ?>
    </style>
</head>
<body class="bg-slate-50 text-slate-900 antialiased">

    <!-- Sidebar Overlay (mobile) -->
    <div id="sidebar-overlay" onclick="closeSidebar()"
         class="hidden fixed inset-0 bg-black/60 z-40 lg:hidden"></div>

    <!--SIDEBAr -->
    <aside id="main-sidebar"
           class="fixed top-0 left-0 w-72 bg-green-100 h-screen overflow-y-auto custom-scrollbar z-50
                  transform transition-transform duration-300 lg:translate-x-0 -translate-x-full">

        <!-- Logo -->
        <div class="p-4 border-b border-green-200 bg-green-400 flex items-center justify-between">
            <a href="<?php echo e(route('dashboard.home')); ?>" class="flex items-center">
                <img src="<?php echo e(asset('images/farmsea.png')); ?>"
                     alt="FarmSea"
                     class="h-14 w-auto object-contain"
                     onerror="this.style.display='none'; document.getElementById('logo-fallback').style.display='flex'">
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

            <a href="<?php echo e(route('dashboard.home')); ?>"
               class="nav-link flex items-center gap-3 px-4 py-2.5 text-sm font-medium rounded-xl
                      hover:bg-gradient-to-r hover:from-blue-500 hover:to-indigo-600 hover:text-white transition
                      <?php echo e(request()->routeIs('dashboard.home') ? 'active-link' : 'text-slate-700'); ?>">
                <i class="fa-solid fa-gauge w-5 text-green-700"></i>
                <span>Dashboard</span>
            </a>

            <!-- Products Dropdown -->
            <div>
                <button onclick="toggleProductMenu()"
                        class="w-full flex items-center justify-between px-4 py-2.5 text-sm font-medium rounded-xl
                               hover:bg-gradient-to-r hover:from-blue-500 hover:to-indigo-600 hover:text-white transition
                               <?php echo e(request()->routeIs('dashboard.categories','dashboard.subcategories','dashboard.products') ? 'active-link' : 'text-slate-700'); ?>">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-boxes-stacked w-5 text-green-700"></i>
                        <span>Products</span>
                    </div>
                    <i id="productArrow" class="fa-solid fa-chevron-down text-xs transition-transform duration-300
                       <?php echo e(request()->routeIs('dashboard.categories','dashboard.subcategories','dashboard.products') ? 'rotate-180' : ''); ?>"></i>
                </button>

                <div id="productDropdown"
                     class="<?php echo e(request()->routeIs('dashboard.categories','dashboard.subcategories','dashboard.products') ? 'flex' : 'hidden'); ?>

                            flex-col pl-9 pr-2 py-2 space-y-1 bg-green-50/50 rounded-xl mt-1 mx-2 border-l-2 border-green-200">

                    <a href="<?php echo e(route('dashboard.categories')); ?>"
                       class="flex items-center gap-3 px-4 py-2 text-xs font-semibold rounded-lg transition
                              hover:bg-blue-500 hover:text-white
                              <?php echo e(request()->routeIs('dashboard.categories') ? 'bg-blue-500 text-white' : 'text-slate-700'); ?>">
                        <i class="fa-solid fa-sitemap w-4 text-green-600"></i> Categories
                    </a>

                    <a href="<?php echo e(route('dashboard.subcategories')); ?>"
                       class="flex items-center gap-3 px-4 py-2 text-xs font-semibold rounded-lg transition
                              hover:bg-blue-500 hover:text-white
                              <?php echo e(request()->routeIs('dashboard.subcategories') ? 'bg-blue-500 text-white' : 'text-slate-700'); ?>">
                        <i class="fa-solid fa-layer-group w-4 text-green-600"></i> Sub-Categories
                    </a>

                    <a href="<?php echo e(route('dashboard.products')); ?>"
                       class="flex items-center gap-3 px-4 py-2 text-xs font-semibold rounded-lg transition
                              hover:bg-blue-500 hover:text-white
                              <?php echo e(request()->routeIs('dashboard.products') ? 'bg-blue-500 text-white' : 'text-slate-700'); ?>">
                        <i class="fa-solid fa-circle-plus w-4 text-green-600"></i> Products
                    </a>
                </div>
            </div>

            <a href="<?php echo e(route('dashboard.orders')); ?>"
               class="nav-link flex items-center gap-3 px-4 py-2.5 text-sm font-medium rounded-xl transition
                      hover:bg-gradient-to-r hover:from-blue-500 hover:to-indigo-600 hover:text-white
                      <?php echo e(request()->routeIs('dashboard.orders') ? 'active-link' : 'text-slate-700'); ?>">
                <i class="fa-solid fa-bag-shopping w-5 text-green-700"></i>
                <span>Orders</span>
            </a>

            <a href="<?php echo e(route('dashboard.orders.history')); ?>"
               class="nav-link flex items-center gap-3 px-4 py-2.5 text-sm font-medium rounded-xl transition
                      hover:bg-gradient-to-r hover:from-blue-500 hover:to-indigo-600 hover:text-white
                      <?php echo e(request()->routeIs('dashboard.orders.history') ? 'active-link' : 'text-slate-700'); ?>">
                <i class="fa-solid fa-clock-rotate-left w-5 text-green-700"></i>
                <span>Order History</span>
            </a>

            <a href="<?php echo e(route('dashboard.users')); ?>"
               class="nav-link flex items-center gap-3 px-4 py-2.5 text-sm font-medium rounded-xl transition
                      hover:bg-gradient-to-r hover:from-blue-500 hover:to-indigo-600 hover:text-white
                      <?php echo e(request()->routeIs('dashboard.users*') ? 'active-link' : 'text-slate-700'); ?>">
                <i class="fa-solid fa-user w-5 text-green-700"></i>
                <span>Users</span>
            </a>

            <a href="<?php echo e(route('dashboard.notifications')); ?>"
               class="nav-link flex items-center gap-3 px-4 py-2.5 text-sm font-medium rounded-xl transition
                      hover:bg-gradient-to-r hover:from-blue-500 hover:to-indigo-600 hover:text-white
                      <?php echo e(request()->routeIs('dashboard.notifications') ? 'active-link' : 'text-slate-700'); ?>">
                <i class="fa-regular fa-bell w-5 text-green-700"></i>
                <span>Notifications</span>
            </a>

            <a href="<?php echo e(route('dashboard.coupons')); ?>"
               class="nav-link flex items-center gap-3 px-4 py-2.5 text-sm font-medium rounded-xl transition
                      hover:bg-gradient-to-r hover:from-blue-500 hover:to-indigo-600 hover:text-white
                      <?php echo e(request()->routeIs('dashboard.coupons') ? 'active-link' : 'text-slate-700'); ?>">
                <i class="fa-solid fa-tags w-5 text-green-700"></i>
                <span>Coupons & Offers</span>
            </a>

            <a href="<?php echo e(route('dashboard.profile')); ?>"
               class="nav-link flex items-center gap-3 px-4 py-2.5 text-sm font-medium rounded-xl transition
                      hover:bg-gradient-to-r hover:from-blue-500 hover:to-indigo-600 hover:text-white
                      <?php echo e(request()->routeIs('dashboard.profile') ? 'active-link' : 'text-slate-700'); ?>">
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
                    <h1 class="text-xl font-bold text-slate-800"><?php echo $__env->yieldContent('page_title', 'Dashboard'); ?></h1>
                </div>

                <div class="flex items-center gap-3">
                    <a href="<?php echo e(route('dashboard.notifications')); ?>"
                       class="relative w-10 h-10 flex items-center justify-center bg-slate-100 rounded-xl hover:bg-mayview-blue hover:text-white transition">
                        <i class="fa-regular fa-bell text-lg"></i>
                        <span class="absolute top-1 right-1 w-2.5 h-2.5 bg-red-500 rounded-full"></span>
                    </a>

                    <div class="relative">
                        <button onclick="toggleProfile()" class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-green-500 flex items-center justify-center text-white font-bold text-sm">
                                <?php echo e(strtoupper(substr(auth()->user()->name ?? 'A', 0, 1))); ?>

                            </div>
                            <div class="hidden sm:block text-left">
                                <p class="text-sm font-semibold text-slate-800"><?php echo e(auth()->user()->name ?? 'Admin'); ?></p>
                                <p class="text-xs text-slate-500 capitalize"><?php echo e(auth()->user()->role ?? 'admin'); ?></p>
                            </div>
                        </button>
                        <!-- userprofile -->
                        <div id="profileModal"
                             class="hidden absolute right-0 mt-3 w-56 bg-white rounded-xl shadow-2xl border border-slate-200 p-4 z-50">
                            <button onclick="closeProfile()" class="absolute top-3 right-4 text-slate-400 hover:text-red-500">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                            <div class="pb-3 border-b mb-3">
                                <p class="font-semibold text-slate-800"><?php echo e(auth()->user()->name ?? 'Admin'); ?></p>
                                <p class="text-xs text-slate-500"><?php echo e(auth()->user()->email ?? ''); ?></p>
                            </div>
                            <a href="<?php echo e(route('dashboard.profile')); ?>"
                               class="flex items-center gap-2 px-3 py-2 text-sm text-slate-700 hover:bg-slate-100 rounded-lg">
                                <i class="fa-solid fa-user-gear"></i> Profile Settings
                            </a>
                            <form method="POST" action="<?php echo e(route('dashboard.logout')); ?>">
                                <?php echo csrf_field(); ?>
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
            <?php if(session('success')): ?>
                <div class="mx-6 mt-4 p-3 bg-green-100 text-green-700 rounded-lg text-sm flex items-center gap-2">
                    <i class="fa-solid fa-circle-check"></i> <?php echo e(session('success')); ?>

                </div>
            <?php endif; ?>
            <?php if(session('error')): ?>
                <div class="mx-6 mt-4 p-3 bg-red-100 text-red-700 rounded-lg text-sm flex items-center gap-2">
                    <i class="fa-solid fa-circle-xmark"></i> <?php echo e(session('error')); ?>

                </div>
            <?php endif; ?>

            <?php echo $__env->yieldContent('content'); ?>
        </main>

        <!-- Footer -->
        <footer class="bg-white border-t border-slate-100 px-8 py-3">
            <div class="flex flex-col md:flex-row justify-between items-center gap-2">
                <p class="text-sm text-slate-500">
                    &copy; <?php echo e(date('Y')); ?> <span class="text-farmsea-green font-bold">FarmSea</span>. All rights reserved.
                </p>
                <div class="flex items-center gap-2 px-4 py-1.5 bg-slate-50 rounded-full border border-slate-100">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Design & Developed by</span>
                    <span class="text-xs font-black text-mayview-dark">Manok kumar</span>
                    <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                </div>
            </div>
        </footer>
    </div>

    <script>
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
    </script>

    <?php echo $__env->yieldContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\FarmSea-dashboard\resources\views/layouts/dashboard.blade.php ENDPATH**/ ?>