<?php

namespace App\Support;

class ProductDayPricing
{
    public static function normalizeDay(?string $day): string
    {
        return $day === 'tomorrow' ? 'tomorrow' : 'today';
    }

    public static function dayLabel(?string $day): string
    {
        return ucfirst(self::normalizeDay($day));
    }

    public static function sellingPrice(array $variant, ?string $day = null, ?float $fallback = null): float
    {
        $basePrice = (float) ($variant['selling_price'] ?? $fallback ?? 0);
        $dayKey = self::normalizeDay($day) . '_price';
        $dayPrice = $variant[$dayKey] ?? null;

        if ($dayPrice === null || $dayPrice === '') {
            return $basePrice;
        }

        return (float) $dayPrice;
    }

    public static function mrp(array $variant, ?float $fallback = null): float
    {
        return (float) ($variant['mrp'] ?? $fallback ?? 0);
    }

    public static function saveOfferPercent(array $variant, ?string $day = null, ?float $fallbackMrp = null): float
    {
        $mrp = self::mrp($variant, $fallbackMrp);
        $sellingPrice = self::sellingPrice($variant, $day);

        if ($mrp <= 0 || $sellingPrice >= $mrp) {
            return 0.0;
        }

        return round((($mrp - $sellingPrice) / $mrp) * 100, 1);
    }
}
