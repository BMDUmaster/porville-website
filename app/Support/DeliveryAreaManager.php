<?php

namespace App\Support;

use App\Models\AppSetting;
use Illuminate\Support\Facades\Cache;

class DeliveryAreaManager
{
    private const SETTING_KEY = 'delivery_areas';
    private const CACHE_KEY = 'delivery_areas:v2';

    /**
     * Admin-managed delivery areas as [pincode => [sector, ...]].
     * An empty list means no restriction has been configured yet.
     */
    public static function pinSectors(): array
    {
        return Cache::rememberForever(self::CACHE_KEY, function (): array {
            $value = AppSetting::query()->where('key', self::SETTING_KEY)->value('value');
            $areas = $value ? json_decode($value, true) : [];

            return is_array($areas) ? self::normalize($areas) : [];
        });
    }

    public static function update(array $areas): array
    {
        $areas = self::normalize($areas);

        AppSetting::query()->updateOrCreate(
            ['key' => self::SETTING_KEY],
            ['value' => json_encode($areas)]
        );

        Cache::forget(self::CACHE_KEY);

        return self::pinSectors();
    }

    public static function isRestricted(): bool
    {
        return ! empty(self::pinSectors());
    }

    /**
     * Returns the admin's spelling of the sector when the PIN + sector pair is
     * serviceable, or null when it is not. Without configured areas, any
     * sector is accepted as typed.
     */
    public static function match(string $pincode, ?string $sector): ?string
    {
        $pinSectors = self::pinSectors();
        $sector = trim((string) $sector);

        if (empty($pinSectors)) {
            return $sector;
        }

        foreach ($pinSectors[$pincode] ?? [] as $allowed) {
            if (strcasecmp($allowed, $sector) === 0) {
                return $allowed;
            }
        }

        return null;
    }

    private static function normalize(array $areas): array
    {
        $normalized = [];

        foreach ($areas as $pin => $sectors) {
            $pin = preg_replace('/\D/', '', (string) $pin);
            $sectors = array_values(array_unique(array_filter(array_map(
                fn ($sector) => trim((string) $sector),
                is_array($sectors) ? $sectors : []
            ))));

            if (strlen($pin) === 6 && ! empty($sectors)) {
                $normalized[$pin] = $sectors;
            }
        }

        ksort($normalized, SORT_NATURAL);

        return $normalized;
    }
}
