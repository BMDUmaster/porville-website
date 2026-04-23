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
            <span class="nunito font-extrabold text-lg">Your Cart</span>
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
<header class="bg-white shadow-sm sticky top-0 z-[9997]">
    <div class="hidden md:flex bg-blue-900 text-white text-xs py-2 px-8 items-center justify-between font-medium h-8">
        <span>FarmSea Premium Meat & Seafood</span>
        <div class="flex gap-6">
            <a href="<?php echo e(route('frontend.track')); ?>" class="hover:text-blue-300 transition">Order Tracking</a>
        </div>
    </div>
    <div class="container mx-auto px-4 py-3 flex items-center justify-between gap-4">
        <button onclick="toggleSidebar()" class="text-gray-700 text-xl lg:hidden">
            <i class="fa-solid fa-bars"></i>
        </button>
        <a href="<?php echo e(route('frontend.home')); ?>" class="flex-shrink-0">
            <?php if(file_exists(public_path('images/farmsea-logo.png'))): ?>
                <img src="<?php echo e(asset('images/farmsea-logo.png')); ?>" alt="FarmSea" class="h-10 w-auto object-contain">
            <?php else: ?>
                <span class="text-xl font-extrabold text-green-700"><img src="<?php echo e(asset('images/farmsea.png')); ?>"
                     alt="FarmSea"
                     class="h-14 w-auto object-contain"
                     onerror="this.style.display='none'; document.getElementById('logo-fallback').style.display='flex'"></span>
            <?php endif; ?>
        </a>
        <form action="<?php echo e(route('frontend.products')); ?>" method="GET" class="hidden md:flex flex-grow max-w-lg mx-4 relative">
            <input type="text" name="search" placeholder="Search chicken, mutton, fish..."
                   class="w-full border border-gray-200 rounded-xl px-5 py-2.5 text-sm focus:outline-none focus:border-green-500 transition">
            <button type="submit" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-green-600">
                <i class="fa-solid fa-magnifying-glass"></i>
            </button>
        </form>
        <div class="flex items-center gap-4">
            <!-- Account -->
            <div class="relative">
                <button onclick="toggleAccountMenu()" class="text-gray-700 flex flex-col items-center group">
                    <i class="fa-regular fa-user text-xl group-hover:text-blue-600"></i>
                    <span class="text-[10px] font-bold mt-0.5 hidden md:block">
                        <?php if(auth()->guard('web_frontend')->check()): ?> <?php echo e(Str::limit(auth('web_frontend')->user()->name, 10)); ?> <?php else: ?> Sign in <?php endif; ?>
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
    <!-- Nav strip -->
    <div class="border-t border-gray-100 hidden md:block overflow-x-auto">
        <div class="max-w-screen-xl mx-auto px-4 flex items-center gap-1 h-10 text-sm scrollbar-hide">
            <a href="<?php echo e(route('frontend.products')); ?>" class="px-3 py-1.5 bg-gray-100 text-gray-600 font-semibold whitespace-nowrap rounded-md flex-shrink-0">All Products</a>
            <a href="<?php echo e(route('frontend.products', ['category' => 'Chicken'])); ?>" class="px-3 py-1.5 text-gray-700 whitespace-nowrap hover:bg-red-50 rounded-md flex-shrink-0">Chicken</a>
            <a href="<?php echo e(route('frontend.products', ['category' => 'Mutton'])); ?>" class="px-3 py-1.5 text-gray-700 whitespace-nowrap hover:bg-red-50 rounded-md flex-shrink-0">Mutton</a>
            <a href="<?php echo e(route('frontend.products', ['category' => 'Fish'])); ?>" class="px-3 py-1.5 text-gray-700 whitespace-nowrap hover:bg-red-50 rounded-md flex-shrink-0">Fish</a>
            <a href="<?php echo e(route('frontend.products', ['category' => 'Seafood'])); ?>" class="px-3 py-1.5 text-gray-700 whitespace-nowrap hover:bg-red-50 rounded-md flex-shrink-0">Seafood</a>
            <span class="w-px h-5 bg-gray-200 mx-1 shrink-0"></span>
            <a href="<?php echo e(route('frontend.products', ['category' => 'Vegetables'])); ?>" class="px-3 py-1.5 text-gray-700 whitespace-nowrap hover:bg-green-50 rounded-md flex-shrink-0">Vegetables</a>
            <a href="<?php echo e(route('frontend.products', ['category' => 'Fruits'])); ?>" class="px-3 py-1.5 text-gray-700 whitespace-nowrap hover:bg-green-50 rounded-md flex-shrink-0">Fruits</a>
        </div>
    </div>
</header>

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

<!-- Footer -->
<footer class="bg-[#0f172a] pt-12 pb-6 text-white" style="font-family:'Poppins',sans-serif;">
    <div class="max-w-7xl mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8 pb-8 border-b border-slate-800">
            <div>
                <h4 class="font-bold text-xs uppercase tracking-widest mb-4">Company</h4>
                <ul class="space-y-3">
                    <li><a href="<?php echo e(route('frontend.about')); ?>" class="text-slate-400 text-sm hover:text-green-500 transition">About Us</a></li>
                    <li><a href="<?php echo e(route('frontend.contact')); ?>" class="text-slate-400 text-sm hover:text-green-500 transition">Contact Us</a></li>
                    <li><a href="<?php echo e(route('frontend.blog')); ?>" class="text-slate-400 text-sm hover:text-green-500 transition">Blog</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-bold text-xs uppercase tracking-widest mb-4">Support</h4>
                <ul class="space-y-3">
                    <li><a href="<?php echo e(route('frontend.track')); ?>" class="text-slate-400 text-sm hover:text-green-500 transition">Track Order</a></li>
                    <li><a href="<?php echo e(route('frontend.returns')); ?>" class="text-slate-400 text-sm hover:text-green-500 transition">Returns & Refunds</a></li>
                    <li><a href="<?php echo e(route('frontend.shipping')); ?>" class="text-slate-400 text-sm hover:text-green-500 transition">Shipping Info</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-bold text-xs uppercase tracking-widest mb-4">Categories</h4>
                <ul class="space-y-3">
                    <li><a href="<?php echo e(route('frontend.products', ['category' => 'Chicken'])); ?>" class="text-slate-400 text-sm hover:text-green-500 transition">Chicken</a></li>
                    <li><a href="<?php echo e(route('frontend.products', ['category' => 'Mutton'])); ?>" class="text-slate-400 text-sm hover:text-green-500 transition">Mutton</a></li>
                    <li><a href="<?php echo e(route('frontend.products', ['category' => 'Fish'])); ?>" class="text-slate-400 text-sm hover:text-green-500 transition">Fish & Seafood</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-bold text-xs uppercase tracking-widest mb-4">Legal</h4>
                <ul class="space-y-3">
                    <li><a href="<?php echo e(route('frontend.privacy')); ?>" class="text-slate-400 text-sm hover:text-green-500 transition">Privacy Policy</a></li>
                    <li><a href="<?php echo e(route('frontend.terms')); ?>" class="text-slate-400 text-sm hover:text-green-500 transition">Terms of Service</a></li>
                    <li><a href="<?php echo e(route('frontend.cookies')); ?>" class="text-slate-400 text-sm hover:text-green-500 transition">Cookie Policy</a></li>
                </ul>
            </div>
        </div>
        <div class="pt-6 flex flex-col md:flex-row items-center justify-between gap-4 text-center">
            <p class="text-slate-400 text-xs uppercase tracking-widest">© <?php echo e(date('Y')); ?> FarmSea — Fresh Meat & Seafood Delivered</p>
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
        if (data.success) {
            document.getElementById('header-cart-badge').textContent = data.cart_count;
            openCart();
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