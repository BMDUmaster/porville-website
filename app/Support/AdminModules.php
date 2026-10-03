<?php

namespace App\Support;

use App\Models\User;
use Illuminate\Support\Str;

/**
 * Admin panel modules and which routes belong to each. The main admin can
 * use everything; a sub admin only gets the modules assigned to them.
 */
class AdminModules
{
    public const MODULES = [
        'dashboard'      => ['label' => 'Dashboard',                 'icon' => 'fa-gauge',              'routes' => ['dashboard.home', 'dashboard.system-check*'], 'home' => 'dashboard.home'],
        'products'       => ['label' => 'Product Management',        'icon' => 'fa-boxes-stacked',      'routes' => ['dashboard.categories*', 'dashboard.subcategories*', 'dashboard.products*'], 'home' => 'dashboard.products'],
        'orders'         => ['label' => 'Order Management',          'icon' => 'fa-bag-shopping',       'routes' => ['dashboard.orders*'], 'home' => 'dashboard.orders'],
        'banners'        => ['label' => 'Website Banners',           'icon' => 'fa-images',             'routes' => ['dashboard.banners*'], 'home' => 'dashboard.banners'],
        'contact'        => ['label' => 'Contact Us',                'icon' => 'fa-envelope',           'routes' => ['dashboard.contact-messages*'], 'home' => 'dashboard.contact-messages'],
        'reviews'        => ['label' => 'Review Management',         'icon' => 'fa-star',               'routes' => ['dashboard.reviews*'], 'home' => 'dashboard.reviews'],
        'customers'      => ['label' => 'Customers',                 'icon' => 'fa-user',               'routes' => ['dashboard.users*'], 'home' => 'dashboard.users'],
        'delivery_boys'  => ['label' => 'Delivery Boys',             'icon' => 'fa-motorcycle',         'routes' => ['dashboard.delivery-boys*'], 'home' => 'dashboard.delivery-boys'],
        'notifications'  => ['label' => 'All Notifications',         'icon' => 'fa-bell',               'routes' => ['dashboard.notifications*'], 'home' => 'dashboard.notifications'],
        'coupons'        => ['label' => 'Offer & Coupon Management', 'icon' => 'fa-tags',               'routes' => ['dashboard.coupons*'], 'home' => 'dashboard.coupons'],
        'delivery_slots' => ['label' => 'Delivery Slots',            'icon' => 'fa-clock',              'routes' => ['dashboard.settings.delivery-slots*'], 'home' => 'dashboard.settings.delivery-slots'],
        'product_slots'  => ['label' => 'Product Slots',             'icon' => 'fa-business-time',      'routes' => ['dashboard.product-slots*'], 'home' => 'dashboard.product-slots'],
        'delivery_areas' => ['label' => 'Delivery Area Management',  'icon' => 'fa-location-dot',       'routes' => ['dashboard.settings.delivery-areas*'], 'home' => 'dashboard.settings.delivery-areas'],
        'service_charge' => ['label' => 'Service Charge',            'icon' => 'fa-percent',            'routes' => ['dashboard.settings.service-charge*'], 'home' => 'dashboard.settings.service-charge'],
        'faqs'           => ['label' => 'Website FAQ',               'icon' => 'fa-circle-question',    'routes' => ['dashboard.faqs*'], 'home' => 'dashboard.faqs'],
        'seo'            => ['label' => 'SEO Management',            'icon' => 'fa-magnifying-glass-chart', 'routes' => ['dashboard.seo*'], 'home' => 'dashboard.seo'],
    ];

    /** Every staff member can use these. */
    private const ALWAYS = ['dashboard.profile*'];

    /** Only the main admin. */
    private const MAIN_ADMIN_ONLY = ['dashboard.admins*'];

    public static function keys(): array
    {
        return array_keys(self::MODULES);
    }

    public static function can(?User $user, string $module): bool
    {
        if (! $user) {
            return false;
        }

        if ($user->role === 'admin') {
            return true;
        }

        return $user->role === 'sub_admin' && in_array($module, $user->permission_list, true);
    }

    /**
     * Where a staff member lands after login (and when a page is not allowed):
     * the dashboard if they may see it, otherwise their first module, else Profile.
     */
    public static function homeUrl(User $user): string
    {
        foreach (self::MODULES as $key => $module) {
            if (self::can($user, $key)) {
                return route($module['home']);
            }
        }

        return route('dashboard.profile');
    }

    public static function allowsRoute(?User $user, ?string $routeName): bool
    {
        if (! $user || ! $routeName) {
            return false;
        }

        if ($user->role === 'admin') {
            return true;
        }

        if ($user->role !== 'sub_admin' || Str::is(self::MAIN_ADMIN_ONLY, $routeName)) {
            return false;
        }

        if (Str::is(self::ALWAYS, $routeName)) {
            return true;
        }

        foreach (self::MODULES as $key => $module) {
            if (Str::is($module['routes'], $routeName)) {
                return in_array($key, $user->permission_list, true);
            }
        }

        return false; // unknown admin route: main admin only
    }

    /**
     * Staff who should get admin alert emails for a module: every active
     * main admin plus sub admins assigned to that module.
     */
    public static function recipients(string $module): array
    {
        return User::query()
            ->whereIn('role', ['admin', 'sub_admin'])
            ->where('status', 'active')
            ->get()
            ->filter(fn (User $user) => self::can($user, $module))
            ->pluck('email')
            ->filter()
            ->unique()
            ->values()
            ->all();
    }
}
