<?php

namespace App\Support;

use Illuminate\Support\Str;

/**
 * Builds shop listing URLs. A category / sub category becomes part of the
 * path (/category/chicken/curry-cut) instead of ?category=…, which is what
 * search engines index; other filters (sort, price…) stay in the query.
 */
class ShopUrl
{
    public static function to(array $params = []): string
    {
        $category = $params['category'] ?? null;
        $subcategory = $params['subcategory'] ?? null;
        unset($params['category'], $params['subcategory']);

        if (blank($category)) {
            return route('frontend.products', $params);
        }

        $path = ['category' => self::slug($category)];

        if (filled($subcategory)) {
            $path['subcategory'] = self::slug($subcategory);
        }

        return route('frontend.category', $path + array_filter($params, fn ($value) => $value !== null && $value !== ''));
    }

    /**
     * Older links passed category names ("Chicken"); keep URLs lowercase slugs.
     */
    private static function slug(string $value): string
    {
        return Str::slug($value) ?: $value;
    }
}
