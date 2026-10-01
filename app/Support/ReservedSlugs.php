<?php

namespace App\Support;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

/**
 * Category pages live at the site root (/chicken), so a category slug must
 * never equal the first part of another URL (/shop, /cart, /products, …).
 * The list is read from the registered routes, so new pages are covered
 * automatically.
 */
class ReservedSlugs
{
    private const EXTRA = ['api', 'build', 'category', 'storage', 'vendor', 'up', 'index', 'admin'];

    private static ?array $cache = null;

    public static function all(): array
    {
        if (self::$cache !== null) {
            return self::$cache;
        }

        $segments = self::EXTRA;

        foreach (Route::getRoutes() as $route) {
            $first = Str::before(trim($route->uri(), '/'), '/');

            if ($first !== '' && ! str_contains($first, '{')) {
                $segments[] = strtolower($first);
            }
        }

        return self::$cache = array_values(array_unique($segments));
    }

    public static function contains(string $slug): bool
    {
        return in_array(strtolower($slug), self::all(), true);
    }
}
