<?php

namespace App\Support;

use App\Models\AppSetting;
use Illuminate\Support\Facades\Cache;

class DeliveryAreaManager
{
    private const SETTING_KEY = 'delivery_areas';
    private const CACHE_KEY = 'delivery_areas';

    public static function pinSectors(): array
    {
        return Cache::rememberForever(self::CACHE_KEY, function (): array {
            $value = AppSetting::query()->where('key', self::SETTING_KEY)->value('value');

            if (! $value) {
                return self::defaultPinSectors();
            }

            $areas = json_decode($value, true);

            return is_array($areas) && ! empty($areas) ? self::normalize($areas) : self::defaultPinSectors();
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

    public static function defaultPinSectors(): array
    {
        return [
            '201301' => ['Sector 1', 'Sector 2', 'Sector 3', 'Sector 4', 'Sector 5', 'Sector 6', 'Sector 7', 'Sector 8', 'Sector 9', 'Sector 10', 'Sector 11', 'Sector 12', 'Sector 13', 'Sector 14', 'Sector 15', 'Sector 16', 'Sector 17', 'Sector 18', 'Sector 19', 'Sector 20', 'Sector 21', 'Sector 22', 'Sector 23', 'Sector 24', 'Sector 25', 'Sector 26', 'Sector 27', 'Sector 28', 'Sector 29', 'Sector 30', 'Sector 31', 'Sector 32', 'Sector 33', 'Sector 34', 'Sector 35', 'Sector 36', 'Sector 37', 'Sector 39', 'Sector 40', 'Sector 41', 'Sector 50', 'Sector 51'],
            '201303' => ['Sector 44', 'Sector 45', 'Sector 46', 'Sector 47', 'Sector 48', 'Sector 96', 'Sector 98'],
            '201304' => ['Sector 74', 'Sector 75', 'Sector 76', 'Sector 77', 'Sector 78', 'Sector 92', 'Sector 93'],
            '201305' => ['Sector 80', 'Sector 81', 'Sector 82', 'Sector 83', 'Sector 84', 'Sector 85', 'Sector 137', 'Sector 142', 'Sector 143', 'Sector 150', 'Sector 151', 'Sector 152'],
            '201307' => ['Sector 55', 'Sector 56', 'Sector 61'],
            '201309' => ['Sector 62', 'Sector 63', 'Sector 64', 'Sector 65'],
            '201318' => ['Gaur City', 'Sector 1', 'Sector 4', 'Sector 10', 'Sector 12'],
        ];
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
