<?php

use Illuminate\Support\Facades\Route;

// ── Dashboard Controllers
use App\Http\Controllers\Dashboard\AuthController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\CategoryController;
use App\Http\Controllers\Dashboard\SubcategoryController;
use App\Http\Controllers\Dashboard\ProductController;
use App\Http\Controllers\Dashboard\OrderController;
use App\Http\Controllers\Dashboard\UserController;
use App\Http\Controllers\Dashboard\DeliveryBoyController;
use App\Http\Controllers\Dashboard\NotificationController;
use App\Http\Controllers\Dashboard\CouponController;
use App\Http\Controllers\Dashboard\ProfileController;

// ── Frontend Controllers 
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\ProductController as FrontProductController;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\CheckoutController;
use App\Http\Controllers\Frontend\AuthController as FrontAuthController;
use App\Http\Controllers\Frontend\ProfileController as FrontProfileController;
use App\Http\Controllers\Frontend\OrderController as FrontOrderController;
use App\Http\Controllers\Frontend\PageController;


// ROOT

Route::get('/', fn() => redirect()->route('frontend.home'));


// ADMIN DASHBOARD AUTH

Route::get('/login',   [AuthController::class, 'showLogin'])->name('dashboard.login')->middleware('guest');
Route::post('/login',  [AuthController::class, 'login'])->middleware('throttle:5,1')->name('dashboard.login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('dashboard.logout');


// ADMIN DASHBOARD (protected)

Route::middleware('admin')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.home');

    // Categories
    Route::get('/categories',               [CategoryController::class, 'index'])->name('dashboard.categories');
    Route::post('/categories',              [CategoryController::class, 'store'])->name('dashboard.categories.store');
    Route::put('/categories/{category}',    [CategoryController::class, 'update'])->name('dashboard.categories.update');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('dashboard.categories.destroy');

    // Sub-categories
    Route::get('/subcategories',                   [SubcategoryController::class, 'index'])->name('dashboard.subcategories');
    Route::post('/subcategories',                  [SubcategoryController::class, 'store'])->name('dashboard.subcategories.store');
    Route::put('/subcategories/{subcategory}',     [SubcategoryController::class, 'update'])->name('dashboard.subcategories.update');
    Route::delete('/subcategories/{subcategory}',  [SubcategoryController::class, 'destroy'])->name('dashboard.subcategories.destroy');

    // Products
    Route::get('/products',              [ProductController::class, 'index'])->name('dashboard.products');
    Route::get('/products/{product}',    [ProductController::class, 'show'])->name('dashboard.products.show');
    Route::post('/products',             [ProductController::class, 'store'])->name('dashboard.products.store');
    Route::put('/products/{product}',    [ProductController::class, 'update'])->name('dashboard.products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('dashboard.products.destroy');

    // Orders — specific routes BEFORE wildcard {order}
    Route::get('/orders',                  [OrderController::class, 'index'])->name('dashboard.orders');
    Route::get('/orders/history',          [OrderController::class, 'history'])->name('dashboard.orders.history');
    Route::get('/orders/total',            [OrderController::class, 'totalOrders'])->name('dashboard.orders.total');
    Route::get('/orders/category',         [OrderController::class, 'categoryOrders'])->name('dashboard.orders.category');
    Route::get('/orders/report',           [OrderController::class, 'report'])->name('dashboard.orders.report');
    Route::get('/orders/{order}',          [OrderController::class, 'show'])->name('dashboard.orders.show');
    Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('dashboard.orders.status');

    // Users
    Route::get('/users',                 [UserController::class, 'index'])->name('dashboard.users');
    Route::get('/users/{user}',          [UserController::class, 'show'])->name('dashboard.users.show');
    Route::patch('/users/{user}/toggle', [UserController::class, 'toggleStatus'])->name('dashboard.users.toggle');
    Route::delete('/users/{user}',       [UserController::class, 'destroy'])->name('dashboard.users.destroy');

    // Delivery Boys
    Route::get('/delivery-boys',                         [DeliveryBoyController::class, 'index'])->name('dashboard.delivery-boys');
    Route::post('/delivery-boys',                        [DeliveryBoyController::class, 'store'])->name('dashboard.delivery-boys.store');
    Route::patch('/delivery-boys/{deliveryBoy}/toggle',  [DeliveryBoyController::class, 'toggleStatus'])->name('dashboard.delivery-boys.toggle');
    Route::delete('/delivery-boys/{deliveryBoy}',        [DeliveryBoyController::class, 'destroy'])->name('dashboard.delivery-boys.destroy');

    // Notifications
    Route::get('/notifications',                   [NotificationController::class, 'index'])->name('dashboard.notifications');
    Route::post('/notifications',                  [NotificationController::class, 'store'])->name('dashboard.notifications.store');
    Route::put('/notifications/{notification}',    [NotificationController::class, 'update'])->name('dashboard.notifications.update');
    Route::delete('/notifications/{notification}', [NotificationController::class, 'destroy'])->name('dashboard.notifications.destroy');

    // Coupons
    Route::get('/coupons',             [CouponController::class, 'index'])->name('dashboard.coupons');
    Route::post('/coupons',            [CouponController::class, 'store'])->name('dashboard.coupons.store');
    Route::put('/coupons/{coupon}',    [CouponController::class, 'update'])->name('dashboard.coupons.update');
    Route::delete('/coupons/{coupon}', [CouponController::class, 'destroy'])->name('dashboard.coupons.destroy');

    // Profile
    Route::get('/profile',           [ProfileController::class, 'index'])->name('dashboard.profile');
    Route::put('/profile',           [ProfileController::class, 'update'])->name('dashboard.profile.update');
    Route::put('/profile/password',  [ProfileController::class, 'updatePassword'])->name('dashboard.profile.password');
});


// FRONTEND — Home

Route::get('/home', [HomeController::class, 'index'])->name('frontend.home');

// ── Cart (public)
Route::get('/cart',          [CartController::class, 'index'])->name('frontend.cart');
Route::post('/cart/add',     [CartController::class, 'add'])->name('frontend.cart.add');
Route::post('/cart/update',  [CartController::class, 'update'])->name('frontend.cart.update');
Route::post('/cart/remove',  [CartController::class, 'remove'])->name('frontend.cart.remove');
Route::get('/cart/count',    [CartController::class, 'count'])->name('frontend.cart.count');

// ── Shop — specific routes BEFORE wildcard
Route::get('/shop',            [FrontProductController::class, 'index'])->name('frontend.products');
Route::get('/shop/categories', [PageController::class, 'categories'])->name('frontend.categories');
Route::get('/shop/{slug}',     [FrontProductController::class, 'show'])->name('frontend.product.show');

// ── Frontend Auth (guest only)
Route::middleware('guest:web_frontend')->group(function () {
    Route::get('/account/login',     [FrontAuthController::class, 'showLogin'])->name('frontend.login');
    Route::post('/account/login',    [FrontAuthController::class, 'login'])->middleware('throttle:5,1')->name('frontend.login.post');
    Route::get('/account/register',  [FrontAuthController::class, 'showRegister'])->name('frontend.register');
    Route::post('/account/register', [FrontAuthController::class, 'register'])->middleware('throttle:3,1')->name('frontend.register.post');
});
Route::post('/account/logout', [FrontAuthController::class, 'logout'])->name('frontend.logout');

// ── Frontend Protected (auth required) 
Route::middleware('auth:web_frontend')->group(function () {
    Route::get('/account/profile',      [FrontProfileController::class, 'index'])->name('frontend.profile');
    Route::put('/account/profile',      [FrontProfileController::class, 'update'])->name('frontend.profile.update');
    Route::put('/account/password',     [FrontProfileController::class, 'updatePassword'])->name('frontend.profile.password');

    Route::get('/account/orders',       [FrontOrderController::class, 'index'])->name('frontend.orders');
    Route::get('/account/orders/{id}',  [FrontOrderController::class, 'show'])->name('frontend.order.show');
    Route::get('/account/orders/{id}/invoice',  [FrontOrderController::class, 'invoice'])->name('frontend.order.invoice');

    Route::get('/checkout',             [CheckoutController::class, 'index'])->name('frontend.checkout');
    Route::post('/checkout',            [CheckoutController::class, 'store'])->name('frontend.checkout.store');
    Route::get('/order-success/{id}',   [CheckoutController::class, 'success'])->name('frontend.order.success');
});

// ── Track Order (public) 
Route::get('/track-order', [FrontOrderController::class, 'track'])->name('frontend.track');

// ── Static Pages
Route::get('/about-us',             [PageController::class, 'about'])->name('frontend.about');
Route::get('/our-farms',            [PageController::class, 'farms'])->name('frontend.farms');
Route::get('/contact-us',           [PageController::class, 'contact'])->name('frontend.contact');
Route::get('/privacy-policy',       [PageController::class, 'privacy'])->name('frontend.privacy');
Route::get('/terms-of-service',     [PageController::class, 'terms'])->name('frontend.terms');
Route::get('/shipping-policy',      [PageController::class, 'shipping'])->name('frontend.shipping');
Route::get('/return-refund-policy', [PageController::class, 'returns'])->name('frontend.returns');
Route::get('/cookie-policy',        [PageController::class, 'cookies'])->name('frontend.cookies');
