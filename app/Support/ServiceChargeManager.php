<?php

namespace App\Support;

use App\Models\AppSetting;
use Illuminate\Support\Facades\Cache;

class ServiceChargeManager
{
    public const SETTING_KEY = 'service_charge_percent';
    private const CACHE_KEY = 'service_charge_percent';

    public static function percentage(): float
    {
        return Cache::rememberForever(self::CACHE_KEY, function () {
            $storedValue = AppSetting::query()
                ->where('key', self::SETTING_KEY)
                ->value('value');

            if ($storedValue === null || $storedValue === '') {
                return self::defaultPercentage();
            }

            return self::normalizePercentage((float) $storedValue);
        });
    }

    public static function updatePercentage(float $percentage): float
    {
        $normalized = self::normalizePercentage($percentage);

        AppSetting::query()->updateOrCreate(
            ['key' => self::SETTING_KEY],
            ['value' => (string) $normalized]
        );

        Cache::forget(self::CACHE_KEY);

        return $normalized;
    }

    public static function calculate(float $subtotal): float
    {
        if ($subtotal <= 0) {
            return 0.0;
        }

        return round($subtotal * self::percentage() / 100, 2);
    }

    public static function defaultPercentage(): float
    {
        return self::normalizePercentage((float) config('order_pricing.default_service_charge_percent', 10));
    }

    private static function normalizePercentage(float $percentage): float
    {
        return round(min(max($percentage, 0), 100), 2);
    }
}
