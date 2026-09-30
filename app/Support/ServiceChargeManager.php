<?php

namespace App\Support;

use App\Models\AppSetting;
use App\Models\ServiceChargeTier;
use Illuminate\Support\Facades\Cache;

class ServiceChargeManager
{
    public const SETTING_KEY = 'service_charge_percent';
    public const TOMORROW_SETTING_KEY = 'service_charge_percent_tomorrow';
    private const CACHE_KEY_PREFIX = 'service_charge_percent:v2:';
    private const TIERS_CACHE_KEY = 'service_charge_tiers:v2';

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

        return round($subtotal * self::effectivePercentage($subtotal, $day) / 100, 2);
    }

    /**
     * The percent actually applied for a given order amount: an admin-defined
     * amount tier if one matches, otherwise the flat today/tomorrow percentage.
     */
    public static function effectivePercentage(float $subtotal, ?string $day = 'today'): float
    {
        $tier = self::matchingTier($subtotal);

        return $tier ? $tier->percent : self::percentage($day);
    }

    public static function matchingTier(float $subtotal): ?ServiceChargeTier
    {
        return self::tiers()->first(function (ServiceChargeTier $tier) use ($subtotal) {
            return $subtotal >= $tier->min_amount
                && ($tier->max_amount === null || $subtotal <= $tier->max_amount);
        });
    }

    public static function tiers()
    {
        return Cache::rememberForever(self::TIERS_CACHE_KEY, function () {
            return ServiceChargeTier::query()->orderBy('min_amount')->get();
        });
    }

    public static function addTier(float $minAmount, ?float $maxAmount, float $percent): ServiceChargeTier
    {
        $tier = ServiceChargeTier::create([
            'min_amount' => $minAmount,
            'max_amount' => $maxAmount,
            'percent' => self::normalizePercentage($percent),
        ]);

        Cache::forget(self::TIERS_CACHE_KEY);

        return $tier;
    }

    public static function deleteTier(int $id): void
    {
        ServiceChargeTier::query()->whereKey($id)->delete();
        Cache::forget(self::TIERS_CACHE_KEY);
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
