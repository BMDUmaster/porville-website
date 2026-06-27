<?php

namespace App\Support;

use App\Models\AppSetting;
use Illuminate\Support\Facades\Cache;

class ServiceChargeManager
{
    public const SETTING_KEY = 'service_charge_percent';
    public const TOMORROW_SETTING_KEY = 'service_charge_percent_tomorrow';
    private const CACHE_KEY_PREFIX = 'service_charge_percent_';

    public static function percentage(?string $day = 'today'): float
    {
        $day = self::normalizeDay($day);
        $settingKey = self::settingKeyForDay($day);

        return Cache::rememberForever(self::cacheKeyForDay($day), function () use ($settingKey) {
            $storedValue = AppSetting::query()
                ->where('key', $settingKey)
                ->value('value');

            if ($storedValue === null || $storedValue === '') {
                return self::defaultPercentage();
            }

            return self::normalizePercentage((float) $storedValue);
        });
    }

    public static function updatePercentage(float $percentage, ?string $day = 'today'): float
    {
        $day = self::normalizeDay($day);
        $normalized = self::normalizePercentage($percentage);

        AppSetting::query()->updateOrCreate(
            ['key' => self::settingKeyForDay($day)],
            ['value' => (string) $normalized]
        );

        Cache::forget(self::cacheKeyForDay($day));

        return $normalized;
    }

    public static function calculate(float $subtotal, ?string $day = 'today'): float
    {
        if ($subtotal <= 0) {
            return 0.0;
        }

        return round($subtotal * self::percentage($day) / 100, 2);
    }

    public static function defaultPercentage(): float
    {
        return self::normalizePercentage((float) config('order_pricing.default_service_charge_percent', 10));
    }

    private static function normalizePercentage(float $percentage): float
    {
        return round(min(max($percentage, 0), 100), 2);
    }

    private static function normalizeDay(?string $day): string
    {
        return $day === 'tomorrow' ? 'tomorrow' : 'today';
    }

    private static function settingKeyForDay(string $day): string
    {
        return $day === 'tomorrow' ? self::TOMORROW_SETTING_KEY : self::SETTING_KEY;
    }

    private static function cacheKeyForDay(string $day): string
    {
        return self::CACHE_KEY_PREFIX . $day;
    }
}
