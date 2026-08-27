<?php

namespace App\Support;

use App\Models\AppSetting;
use Illuminate\Support\Facades\Cache;

class OrderingManager
{
    private const CACHE_KEY = 'ordering_settings';
    private const CACHE_TTL = 60; // seconds

    public static function isActive(?string $day = null): bool
    {
        $key = match (self::normalizeDay($day)) {
            'today' => 'ordering_active_today',
            'tomorrow' => 'ordering_active_tomorrow',
            default => 'ordering_active',
        };

        return (bool) filter_var(
            self::get($key, self::get('ordering_active', '1')),
            FILTER_VALIDATE_BOOLEAN
        );
    }

    public static function isActiveForDay(string $day): bool
    {
        return self::isActive($day);
    }

    public static function inactiveTitle(): string
    {
        return self::get('ordering_inactive_title', 'Orders Temporarily Paused');
    }

    public static function inactiveMessage(): string
    {
        return self::get('ordering_inactive_message', 'We are currently not accepting new orders. Please check back soon.');
    }

    public static function settings(): array
    {
        return Cache::remember(self::CACHE_KEY, self::CACHE_TTL, function () {
            $rows = AppSetting::whereIn('key', [
                'ordering_active',
                'ordering_active_today',
                'ordering_active_tomorrow',
                'ordering_inactive_title',
                'ordering_inactive_message',
            ])->pluck('value', 'key')->toArray();

            return array_merge([
                'ordering_active'           => '1',
                'ordering_active_today'     => $rows['ordering_active'] ?? '1',
                'ordering_active_tomorrow'  => $rows['ordering_active'] ?? '1',
                'ordering_inactive_title'   => 'Orders Temporarily Paused',
                'ordering_inactive_message' => 'We are currently not accepting new orders. Please check back soon.',
            ], $rows);
        });
    }

    public static function update(bool $todayActive, bool $tomorrowActive, string $title, string $message): void
    {
        $data = [
            'ordering_active'           => ($todayActive && $tomorrowActive) ? '1' : '0',
            'ordering_active_today'     => $todayActive ? '1' : '0',
            'ordering_active_tomorrow'  => $tomorrowActive ? '1' : '0',
            'ordering_inactive_title'   => trim($title),
            'ordering_inactive_message' => trim($message),
        ];

        foreach ($data as $key => $value) {
            AppSetting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        Cache::forget(self::CACHE_KEY);
    }

    private static function get(string $key, string $default): string
    {
        return self::settings()[$key] ?? $default;
    }

    private static function normalizeDay(?string $day): ?string
    {
        return in_array($day, ['today', 'tomorrow'], true) ? $day : null;
    }
}
