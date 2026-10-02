<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;

// Serve uploaded files when public/storage symlink is missing (common on shared hosting).
Route::get('/storage/{path}', function (string $path) {
    $path = str_replace(['..', '\\'], '', $path);
    $fullPath = storage_path('app/public/' . $path);

    if (! File::isFile($fullPath)) {
        abort(404);
    }

    return response()->file($fullPath);
})->where('path', '.*');

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
use App\Http\Controllers\Dashboard\ServiceChargeController;
use App\Http\Controllers\Dashboard\DeliverySlotController;
use App\Http\Controllers\Dashboard\ProductSlotController;
use App\Http\Controllers\Dashboard\DeliveryAreaController;
use App\Http\Controllers\Dashboard\SystemCheckController;
use App\Http\Controllers\Dashboard\HomeBannerController;
use App\Http\Controllers\Dashboard\ContactMessageController;
use App\Http\Controllers\Dashboard\ReviewController as DashboardReviewController;
use App\Http\Controllers\Dashboard\SeoPageController;
use App\Http\Controllers\ServerDiagnosticsController;

// Public server diagnostics (no login) — use this URL on live hosting
Route::get('/server-check', ServerDiagnosticsController::class)->name('server-check');

// Utility to clear route, view and application cache from browser
Route::get('/clear-cache', function () {
    \Illuminate\Support\Facades\Artisan::call('route:clear');
    \Illuminate\Support\Facades\Artisan::call('view:clear');
    \Illuminate\Support\Facades\Artisan::call('cache:clear');
    return 'Route, view, and application cache cleared successfully!';
});

// ── Frontend Controllers 
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\ProductController as FrontProductController;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\WishlistController;
use App\Http\Controllers\Frontend\CheckoutController;
use App\Http\Controllers\Frontend\AuthController as FrontAuthController;
use App\Http\Controllers\Frontend\ProfileController as FrontProfileController;
use App\Http\Controllers\Frontend\OrderController as FrontOrderController;
use App\Http\Controllers\Frontend\PageController;
use App\Http\Controllers\Frontend\ReviewController as FrontReviewController;


// ROOT

Route::get('/', [HomeController::class, 'index'])->name('frontend.home');


// ADMIN DASHBOARD AUTH

Route::get('/login',   [AuthController::class, 'showLogin'])->name('dashboard.login')->middleware('guest');
Route::post('/login',  [AuthController::class, 'login'])->middleware('throttle:5,1')->name('dashboard.login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('dashboard.logout');

// Admin password reset (emailed code). Also linked from the admin Profile page.
Route::get('/login/forgot-password',   [\App\Http\Controllers\Dashboard\PasswordResetController::class, 'show'])->name('dashboard.password.forgot');
Route::post('/login/forgot-password',  [\App\Http\Controllers\Dashboard\PasswordResetController::class, 'sendOtp'])->middleware('throttle:3,1')->name('dashboard.password.send-otp');
Route::post('/login/reset-password',   [\App\Http\Controllers\Dashboard\PasswordResetController::class, 'reset'])->middleware('throttle:6,1')->name('dashboard.password.reset');
Route::post('/login/forgot-password/restart', [\App\Http\Controllers\Dashboard\PasswordResetController::class, 'restart'])->name('dashboard.password.restart');


// ADMIN DASHBOARD (protected)

Route::middleware('admin')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.home');
    Route::get('/dashboard/system-check', SystemCheckController::class)->name('dashboard.system-check');
    Route::get('/system-check', SystemCheckController::class)->name('dashboard.system-check.short');

    // Home Banners
    Route::get('/banners',              [HomeBannerController::class, 'index'])->name('dashboard.banners');
    Route::post('/banners',             [HomeBannerController::class, 'store'])->name('dashboard.banners.store');
    Route::put('/banners/{banner}',     [HomeBannerController::class, 'update'])->name('dashboard.banners.update');
    Route::delete('/banners/{banner}',  [HomeBannerController::class, 'destroy'])->name('dashboard.banners.destroy');

    // Contact Messages
    Route::get('/contact-messages',                    [ContactMessageController::class, 'index'])->name('dashboard.contact-messages');
    Route::get('/contact-messages/live',               [ContactMessageController::class, 'live'])->name('dashboard.contact-messages.live');
    Route::get('/contact-messages/{message}',          [ContactMessageController::class, 'show'])->name('dashboard.contact-messages.show');
    Route::patch('/contact-messages/{message}/read',   [ContactMessageController::class, 'markRead'])->name('dashboard.contact-messages.read');
    Route::delete('/contact-messages/{message}',       [ContactMessageController::class, 'destroy'])->name('dashboard.contact-messages.destroy');

    // Customer Reviews
    Route::get('/reviews', [DashboardReviewController::class, 'index'])->name('dashboard.reviews');
    Route::patch('/reviews/{review}', [DashboardReviewController::class, 'update'])->name('dashboard.reviews.update');
    Route::delete('/reviews/{review}', [DashboardReviewController::class, 'destroy'])->name('dashboard.reviews.destroy');

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
    Route::get('/products',                      [ProductController::class, 'index'])->name('dashboard.products');
    Route::get('/products/search-json',          [ProductController::class, 'searchJson'])->name('dashboard.products.search-json');
    Route::match(['get', 'post'], '/products/multi-edit', [ProductController::class, 'multiEdit'])->name('dashboard.products.multi-edit');
    Route::post('/products/multi-update',        [ProductController::class, 'multiUpdate'])->name('dashboard.products.multi-update');
    Route::get('/products/{product}',            [ProductController::class, 'show'])->name('dashboard.products.show');
    Route::post('/products',                     [ProductController::class, 'store'])->name('dashboard.products.store');
    Route::put('/products/{product}',            [ProductController::class, 'update'])->name('dashboard.products.update');
    Route::delete('/products/{product}',         [ProductController::class, 'destroy'])->name('dashboard.products.destroy');

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
    Route::patch('/users/{user}/delivery-charge', [UserController::class, 'updateDeliveryCharge'])->name('dashboard.users.delivery-charge');
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

    // Admin Management (main admin only — enforced in AdminMiddleware)
    Route::get('/admins',            [\App\Http\Controllers\Dashboard\AdminUserController::class, 'index'])->name('dashboard.admins');
    Route::post('/admins',           [\App\Http\Controllers\Dashboard\AdminUserController::class, 'store'])->name('dashboard.admins.store');
    Route::put('/admins/{staff}',    [\App\Http\Controllers\Dashboard\AdminUserController::class, 'update'])->name('dashboard.admins.update');
    Route::delete('/admins/{staff}', [\App\Http\Controllers\Dashboard\AdminUserController::class, 'destroy'])->name('dashboard.admins.destroy');

    // Profile
    Route::get('/profile',           [ProfileController::class, 'index'])->name('dashboard.profile');
    Route::put('/profile',           [ProfileController::class, 'update'])->name('dashboard.profile.update');
    Route::put('/profile/password',  [ProfileController::class, 'updatePassword'])->name('dashboard.profile.password');

    // Settings
    Route::get('/settings/service-charge', [ServiceChargeController::class, 'index'])->name('dashboard.settings.service-charge');
    Route::post('/settings/service-charge/tier', [ServiceChargeController::class, 'storeTier'])->name('dashboard.settings.service-charge.tier.store');
    Route::delete('/settings/service-charge/tier/{tier}', [ServiceChargeController::class, 'destroyTier'])->name('dashboard.settings.service-charge.tier.destroy');
    Route::get('/settings/delivery-slots', [DeliverySlotController::class, 'index'])->name('dashboard.settings.delivery-slots');
    Route::put('/settings/delivery-slots', [DeliverySlotController::class, 'update'])->name('dashboard.settings.delivery-slots.update');
    Route::post('/settings/delivery-slots/slot', [DeliverySlotController::class, 'storeSlot'])->name('dashboard.settings.delivery-slots.slot.store');
    Route::delete('/settings/delivery-slots/slot/{slot}', [DeliverySlotController::class, 'destroySlot'])->name('dashboard.settings.delivery-slots.slot.destroy');
    Route::delete('/settings/delivery-slots/date/{date}', [DeliverySlotController::class, 'destroyDate'])->name('dashboard.settings.delivery-slots.date.destroy');
    Route::get('/product-slots', [ProductSlotController::class, 'index'])->name('dashboard.product-slots');
    Route::post('/product-slots', [ProductSlotController::class, 'store'])->name('dashboard.product-slots.store');
    Route::put('/product-slots/{slot}', [ProductSlotController::class, 'update'])->name('dashboard.product-slots.update');
    Route::delete('/product-slots/{slot}', [ProductSlotController::class, 'destroy'])->name('dashboard.product-slots.destroy');
    Route::get('/settings/delivery-areas', [DeliveryAreaController::class, 'index'])->name('dashboard.settings.delivery-areas');
    Route::put('/settings/delivery-areas', [DeliveryAreaController::class, 'update'])->name('dashboard.settings.delivery-areas.update');
    // FAQs
    Route::get('/faqs', [\App\Http\Controllers\Dashboard\FaqController::class, 'index'])->name('dashboard.faqs');
    Route::post('/faqs/categories', [\App\Http\Controllers\Dashboard\FaqController::class, 'storeCategory'])->name('dashboard.faqs.categories.store');
    Route::put('/faqs/categories/{category}', [\App\Http\Controllers\Dashboard\FaqController::class, 'updateCategory'])->name('dashboard.faqs.categories.update');
    Route::delete('/faqs/categories/{category}', [\App\Http\Controllers\Dashboard\FaqController::class, 'destroyCategory'])->name('dashboard.faqs.categories.destroy');
    Route::post('/faqs/items', [\App\Http\Controllers\Dashboard\FaqController::class, 'storeFaq'])->name('dashboard.faqs.items.store');
    Route::put('/faqs/items/{faq}', [\App\Http\Controllers\Dashboard\FaqController::class, 'updateFaq'])->name('dashboard.faqs.items.update');
    Route::delete('/faqs/items/{faq}', [\App\Http\Controllers\Dashboard\FaqController::class, 'destroyFaq'])->name('dashboard.faqs.items.destroy');

    // SEO Management
    Route::get('/seo-management',                    [SeoPageController::class, 'index'])->name('dashboard.seo');
    Route::get('/seo-management/create',             [SeoPageController::class, 'create'])->name('dashboard.seo.create');
    // SEO > Category page content (HTML shown above the footer)
    Route::get('/seo-management/category-content',                    [\App\Http\Controllers\Dashboard\CategorySeoContentController::class, 'index'])->name('dashboard.seo.category-content');
    Route::get('/seo-management/category-content/{category}/edit',    [\App\Http\Controllers\Dashboard\CategorySeoContentController::class, 'edit'])->name('dashboard.seo.category-content.edit');
    Route::put('/seo-management/category-content/{category}',         [\App\Http\Controllers\Dashboard\CategorySeoContentController::class, 'update'])->name('dashboard.seo.category-content.update');
    Route::delete('/seo-management/category-content/{category}',      [\App\Http\Controllers\Dashboard\CategorySeoContentController::class, 'destroy'])->name('dashboard.seo.category-content.destroy');
    Route::post('/seo-management',                   [SeoPageController::class, 'store'])->name('dashboard.seo.store');
    Route::post('/seo-management/import',            [SeoPageController::class, 'importDefaults'])->name('dashboard.seo.import');
    Route::get('/seo-management/{seoPage}/edit',     [SeoPageController::class, 'edit'])->name('dashboard.seo.edit');
    Route::put('/seo-management/{seoPage}',          [SeoPageController::class, 'update'])->name('dashboard.seo.update');
    Route::patch('/seo-management/{seoPage}/toggle', [SeoPageController::class, 'toggle'])->name('dashboard.seo.toggle');
    Route::delete('/seo-management/{seoPage}',       [SeoPageController::class, 'destroy'])->name('dashboard.seo.destroy');
});

// SEO — public crawler files
Route::get('/sitemap.xml', [\App\Http\Controllers\Frontend\SeoController::class, 'sitemap'])->name('seo.sitemap');
Route::get('/robots.txt',  [\App\Http\Controllers\Frontend\SeoController::class, 'robots'])->name('seo.robots');


// FRONTEND — Home

Route::get('/home', fn() => redirect('/', 301));
Route::get('/deals', [PageController::class, 'deals'])->name('frontend.deals');

// Signed links sent after delivery; customer login is not required.
Route::get('/review/order/{order}', [FrontReviewController::class, 'create'])->name('frontend.review.create');
Route::post('/review/order/{order}', [FrontReviewController::class, 'store'])->name('frontend.review.store');

// ── Cart (public)
Route::get('/cart',          [CartController::class, 'index'])->name('frontend.cart');
Route::post('/cart/add',     [CartController::class, 'add'])->name('frontend.cart.add');
Route::post('/cart/update',  [CartController::class, 'update'])->name('frontend.cart.update');
Route::post('/cart/delivery-slot', [CartController::class, 'updateDeliverySlot'])->name('frontend.cart.delivery-slot');
Route::post('/cart/delivery-date', [CartController::class, 'updateDeliveryDate'])->name('frontend.cart.delivery-date');
Route::post('/cart/coupon', [CartController::class, 'applyCoupon'])->name('frontend.cart.coupon.apply');
Route::delete('/cart/coupon', [CartController::class, 'removeCoupon'])->name('frontend.cart.coupon.remove');
Route::post('/cart/remove',  [CartController::class, 'remove'])->name('frontend.cart.remove');
Route::get('/cart/count',    [CartController::class, 'count'])->name('frontend.cart.count');

// ── Wishlist (public)
Route::get('/wishlist',        [WishlistController::class, 'index'])->name('frontend.wishlist');
Route::post('/wishlist/toggle', [WishlistController::class, 'toggle'])->name('frontend.wishlist.toggle');
Route::get('/wishlist/count',  [WishlistController::class, 'count'])->name('frontend.wishlist.count');

// ── Shop — specific routes BEFORE wildcard
Route::get('/shop',            [FrontProductController::class, 'index'])->name('frontend.products');
Route::get('/shop/categories', [PageController::class, 'categories'])->name('frontend.categories');
Route::get('/shop/{slug}',     [FrontProductController::class, 'show'])->name('frontend.product.show');

// ── Frontend Auth (guest only)
Route::middleware('guest:web_frontend')->group(function () {
    Route::get('/account/login',     [FrontAuthController::class, 'showLogin'])->name('frontend.login');
    Route::post('/account/check-email', [FrontAuthController::class, 'checkEmail'])->middleware('throttle:30,1')->name('frontend.email.check');
    Route::post('/account/login',    [FrontAuthController::class, 'login'])->middleware('throttle:5,1')->name('frontend.login.post');
    Route::get('/account/forgot-password', [FrontAuthController::class, 'showForgotPassword'])->name('frontend.password.forgot');
    Route::post('/account/forgot-password/send-otp', [FrontAuthController::class, 'sendForgotPasswordOtp'])->middleware('throttle:3,1')->name('frontend.password.otp');
    Route::post('/account/forgot-password/verify-otp', [FrontAuthController::class, 'verifyForgotPasswordOtp'])->middleware('throttle:5,1')->name('frontend.password.verify');
    Route::post('/account/forgot-password/reset', [FrontAuthController::class, 'resetForgotPassword'])->middleware('throttle:5,1')->name('frontend.password.reset');
    Route::get('/account/register',  [FrontAuthController::class, 'showRegister'])->name('frontend.register');
    Route::post('/account/register/send-otp', [FrontAuthController::class, 'sendRegisterOtp'])->middleware('throttle:10,1')->name('frontend.register.otp');
    Route::post('/account/register/verify-otp', [FrontAuthController::class, 'verifyRegisterOtp'])->middleware('throttle:10,1')->name('frontend.register.verify-otp');
    Route::post('/account/register', [FrontAuthController::class, 'register'])->middleware('throttle:10,1')->name('frontend.register.post');
});
Route::post('/account/logout', [FrontAuthController::class, 'logout'])->name('frontend.logout');

// ── Frontend Protected (auth required) 
Route::middleware('auth:web_frontend')->group(function () {
    Route::get('/account/profile',      [FrontProfileController::class, 'index'])->name('frontend.profile');
    Route::put('/account/profile',      [FrontProfileController::class, 'update'])->name('frontend.profile.update');
    Route::put('/account/password',     [FrontProfileController::class, 'updatePassword'])->name('frontend.profile.password');
    Route::get('/account/notifications', [FrontProfileController::class, 'notifications'])->name('frontend.notifications');
    Route::post('/account/notifications/read', [FrontProfileController::class, 'markNotificationsRead'])->name('frontend.notifications.read');
    Route::post('/account/notifications/{notification}/read', [FrontProfileController::class, 'markNotificationRead'])->name('frontend.notifications.mark-read');
    Route::delete('/account/notifications/{notification}', [FrontProfileController::class, 'deleteNotification'])->name('frontend.notifications.delete');
    Route::match(['get', 'post'], '/account/slot-alerts/{product}', [\App\Http\Controllers\Frontend\ProductSlotAlertController::class, 'subscribe'])->name('frontend.slot-alerts.subscribe');

    Route::get('/account/orders',       [FrontOrderController::class, 'index'])->name('frontend.orders');
    Route::get('/account/orders/{id}',  [FrontOrderController::class, 'show'])->name('frontend.order.show');
    Route::get('/account/orders/{id}/invoice',  [FrontOrderController::class, 'invoice'])->name('frontend.order.invoice');

    Route::get('/checkout',             [CheckoutController::class, 'index'])->name('frontend.checkout');
    Route::post('/checkout',            [CheckoutController::class, 'store'])->name('frontend.checkout.store');
    Route::get('/order-success/{id}',   [CheckoutController::class, 'success'])->name('frontend.order.success');

    // Razorpay — authenticated routes
    Route::get('/checkout/pay/{order}',             [\App\Http\Controllers\Frontend\RazorpayController::class, 'show'])->name('frontend.razorpay.payment');
    Route::get('/checkout/razorpay/cancel/{order}', [\App\Http\Controllers\Frontend\RazorpayController::class, 'cancel'])->name('frontend.razorpay.cancel');
});

// Razorpay callback — CSRF-exempt, rate-limited
Route::post('/checkout/razorpay/callback', [\App\Http\Controllers\Frontend\RazorpayController::class, 'callback'])
    ->name('frontend.razorpay.callback')
    ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class])
    ->middleware('throttle:20,1');

// ── Track Order (public) 
Route::get('/track-order', [FrontOrderController::class, 'track'])->name('frontend.track');

// ── Static Pages
Route::get('/about-us',             [PageController::class, 'about'])->name('frontend.about');
Route::get('/our-farms',            [PageController::class, 'farms'])->name('frontend.farms');
Route::get('/contact-us',           [PageController::class, 'contact'])->name('frontend.contact');
Route::get('/faq',                  [PageController::class, 'faq'])->name('frontend.faq');
Route::post('/contact-us',          [PageController::class, 'submitContact'])->middleware('throttle:5,1')->name('frontend.contact.submit');
Route::get('/privacy-policy',       [PageController::class, 'privacy'])->name('frontend.privacy');
Route::get('/terms-of-service',     [PageController::class, 'terms'])->name('frontend.terms');
Route::get('/shipping-policy',      [PageController::class, 'shipping'])->name('frontend.shipping');
Route::get('/return-refund-policy', [PageController::class, 'returns'])->name('frontend.returns');
Route::get('/cookie-policy',        [PageController::class, 'cookies'])->name('frontend.cookies');

// ── Category pages at the site root: /chicken and /chicken/curry-cut
// Keep these LAST so every other page is matched first. Category slugs that
// would clash with a page are prevented in App\Support\ReservedSlugs.
$slugPattern = '[a-z0-9]+(?:-[a-z0-9]+)*';

// Earlier /category/... links -> clean URL.
Route::get('/category/{category}/{subcategory?}', fn (string $category, ?string $subcategory = null) => redirect()->to(
    \App\Support\ShopUrl::to(array_filter(compact('category', 'subcategory')) + request()->query()),
    301
))->where(['category' => $slugPattern, 'subcategory' => $slugPattern]);

Route::get('/{category}/{subcategory?}', [FrontProductController::class, 'index'])
    ->where(['category' => $slugPattern, 'subcategory' => $slugPattern])
    ->name('frontend.category');
