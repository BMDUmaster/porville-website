<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\SeoPage;
use App\Support\ShopUrl;
use Illuminate\Http\Response;

class SeoController extends Controller
{
    public function sitemap(): Response
    {
        $entries = [];

        $pages = SeoPage::query()
            ->active()
            ->where('sitemap_include', true)
            ->where('robots_index', true)
            ->orderByDesc('sitemap_priority')
            ->orderBy('route_path')
            ->get();

        foreach ($pages as $page) {
            $entries[$page->resolved_canonical] = [
                'loc' => $page->resolved_canonical,
                'lastmod' => $page->updated_at?->toAtomString(),
                'changefreq' => $page->sitemap_changefreq,
                'priority' => number_format($page->sitemap_priority, 1),
            ];
        }

        // Pages turned off in SEO Management must not come back in through the product feed.
        $excluded = SeoPage::query()
            ->where(fn ($query) => $query->where('sitemap_include', false)->orWhere('robots_index', false))
            ->where('is_active', true)
            ->pluck('route_path')
            ->all();

        Product::query()->active()->whereNotNull('slug')->select(['slug', 'updated_at'])->orderBy('slug')->get()
            ->each(function (Product $product) use (&$entries, $excluded) {
                $path = '/shop/' . $product->slug;
                $loc = url($path);

                if (isset($entries[$loc]) || in_array($path, $excluded, true)) {
                    return;
                }

                $entries[$loc] = [
                    'loc' => $loc,
                    'lastmod' => $product->updated_at?->toAtomString(),
                    'changefreq' => 'weekly',
                    'priority' => '0.7',
                ];
            });

        // Category and sub category listing pages (clean /category/... URLs).
        Category::parents()->where('is_active', true)
            ->with(['children' => fn ($query) => $query->where('is_active', true)])
            ->get()
            ->each(function (Category $category) use (&$entries, $excluded) {
                $pages = [[$category, ShopUrl::to(['category' => $category->slug])]];

                foreach ($category->children as $child) {
                    $pages[] = [$child, ShopUrl::to(['category' => $category->slug, 'subcategory' => $child->slug])];
                }

                foreach ($pages as [$page, $loc]) {
                    if (isset($entries[$loc]) || in_array(parse_url($loc, PHP_URL_PATH), $excluded, true)) {
                        continue;
                    }

                    $entries[$loc] = [
                        'loc' => $loc,
                        'lastmod' => $page->updated_at?->toAtomString(),
                        'changefreq' => 'daily',
                        'priority' => '0.8',
                    ];
                }
            });

        return response()
            ->view('frontend.seo.sitemap', ['entries' => array_values($entries)])
            ->header('Content-Type', 'application/xml; charset=UTF-8');
    }

    public function robots(): Response
    {
        // Admin panel routes live at the site root, so each one is listed explicitly.
        $adminPaths = [
            '/dashboard', '/login', '/banners', '/contact-messages', '/reviews', '/categories',
            '/subcategories', '/products', '/orders', '/users', '/delivery-boys', '/notifications',
            '/coupons', '/profile', '/settings/', '/faqs', '/seo-management',
        ];

        $lines = [
            'User-agent: *',
            ...array_map(fn ($path) => "Disallow: {$path}", $adminPaths),
            'Disallow: /account/',
            'Disallow: /checkout',
            'Disallow: /cart',
            'Disallow: /wishlist',
            'Disallow: /order-success/',
            'Disallow: /review/',
            'Disallow: /server-check',
            'Disallow: /system-check',
            'Disallow: /clear-cache',
            '',
            'Sitemap: ' . url('/sitemap.xml'),
        ];

        return response(implode("\n", $lines) . "\n", 200, ['Content-Type' => 'text/plain; charset=UTF-8']);
    }
}
