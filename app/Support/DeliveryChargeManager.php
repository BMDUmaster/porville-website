<?php

namespace App\Support;

use App\Models\AppSetting;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class DeliveryChargeManager
{
    public const SETTING_KEY = 'delivery_charge';
    private const CACHE_KEY = 'delivery_charge:v2';

    public static function amount(): float
    {
        return Cache::rememberForever(self::CACHE_KEY, function () {
            $storedValue = AppSetting::query()
                ->where('key', self::SETTING_KEY)
                ->value('value');

            if ($storedValue === null || $storedValue === '') {
                return self::defaultAmount();
            }

            return self::normalizeAmount((float) $storedValue);
        });
    }

    public static function updateAmount(float $amount): float
    {
        $normalized = self::normalizeAmount($amount);

        AppSetting::query()->updateOrCreate(
            ['key' => self::SETTING_KEY],
            ['value' => (string) $normalized]
        );

        Cache::forget(self::CACHE_KEY);

        return $normalized;
    }

    public static function amountForUser(?User $user): float
    {
        if ($user && $user->role === 'customer' && $user->delivery_charge !== null) {
            return self::normalizeAmount((float) $user->delivery_charge);
        }

        return self::amount();
    }

    public static function defaultAmount(): float
    {
        return self::normalizeAmount((float) config('order_pricing.delivery_charge', 25));
    }

    private static function normalizeAmount(float $amount): float
    {
        return round(max($amount, 0), 2);
    }
}
