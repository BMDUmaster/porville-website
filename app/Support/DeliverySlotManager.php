<?php

namespace App\Support;

use App\Models\AppSetting;
use DateInterval;
use DateTimeImmutable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

class DeliverySlotManager
{
    private const CACHE_KEY = 'delivery_slot_settings';
    private const FIXED_SLOTS_KEY = 'delivery_fixed_slots';
    private const EVENING_START_KEY = 'delivery_evening_slot_start';
    private const LAST_END_KEY = 'delivery_last_slot_end';
    private const DURATION_HOURS_KEY = 'delivery_slot_duration_hours';

    private const TOMORROW_FIXED_SLOTS_KEY = 'tomorrow_delivery_fixed_slots';
    private const TOMORROW_EVENING_START_KEY = 'tomorrow_delivery_evening_slot_start';
    private const TOMORROW_LAST_END_KEY = 'tomorrow_delivery_last_slot_end';
    private const TOMORROW_DURATION_HOURS_KEY = 'tomorrow_delivery_slot_duration_hours';

    public static function options(string $day = 'today'): array
    {
        $day = strtolower($day) === 'tomorrow' ? 'tomorrow' : 'today';
        $slots = [];
        $settings = self::settings();

        $fixedSlotsKey = $day === 'tomorrow' ? 'tomorrow_fixed_slots' : 'fixed_slots';
        $durationHoursKey = $day === 'tomorrow' ? 'tomorrow_slot_duration_hours' : 'slot_duration_hours';
        $eveningStartKey = $day === 'tomorrow' ? 'tomorrow_evening_start' : 'evening_start';
        $lastEndKey = $day === 'tomorrow' ? 'tomorrow_last_end' : 'last_end';

        foreach ($settings[$fixedSlotsKey] as $slot) {
            $normalized = self::normalizeSlot($slot['start'] ?? null, $slot['end'] ?? null);

            if ($normalized) {
                $slots[$normalized['value']] = $normalized;
            }
        }

        $durationHours = max(1, (int) ($settings[$durationHoursKey] ?? 2));
        $eveningStart = self::parseTime($settings[$eveningStartKey] ?? null);
        $lastEnd = self::parseTime($settings[$lastEndKey] ?? null);

        if ($eveningStart && $lastEnd && $lastEnd > $eveningStart) {
            $interval = new DateInterval('PT' . $durationHours . 'H');
            $current = $eveningStart;

            while ($current < $lastEnd) {
                $end = $current->add($interval);

                if ($end > $lastEnd) {
                    break;
                }

                $normalized = self::normalizeSlot($current->format('H:i'), $end->format('H:i'));

                if ($normalized) {
                    $slots[$normalized['value']] = $normalized;
                }

                $current = $end;
            }
        }

        return array_values($slots);
    }

    public static function availableOptions(string $day = 'today', ?Carbon $now = null): array
    {
        $day = strtolower($day) === 'tomorrow' ? 'tomorrow' : 'today';
        $options = self::options($day);
        if ($day === 'tomorrow') {
            return $options;
        }

        $now = ($now ?: Carbon::now(self::timezone()))->copy()->timezone(self::timezone());

        return array_values(array_filter($options, function (array $slot) use ($now) {
            [$start] = array_pad(explode('-', (string) ($slot['value'] ?? ''), 2), 2, null);

            if (! $start) {
                return false;
            }

            $slotStart = Carbon::createFromFormat('Y-m-d H:i', $now->toDateString() . ' ' . $start, self::timezone());

            return $slotStart && $slotStart->greaterThan($now);
        }));
    }

    public static function settings(): array
    {
        return Cache::rememberForever(self::CACHE_KEY, function () {
            $defaults = self::defaultSettings();
            $storedSettings = AppSetting::query()
                ->whereIn('key', [
                    self::FIXED_SLOTS_KEY,
                    self::EVENING_START_KEY,
                    self::LAST_END_KEY,
                    self::DURATION_HOURS_KEY,
                    self::TOMORROW_FIXED_SLOTS_KEY,
                    self::TOMORROW_EVENING_START_KEY,
                    self::TOMORROW_LAST_END_KEY,
                    self::TOMORROW_DURATION_HOURS_KEY,
                ])
                ->pluck('value', 'key');

            $fixedSlots = self::decodeFixedSlots($storedSettings->get(self::FIXED_SLOTS_KEY));
            $eveningStart = self::normalizeTimeValue($storedSettings->get(self::EVENING_START_KEY));
            $lastEnd = self::normalizeTimeValue($storedSettings->get(self::LAST_END_KEY));
            $durationHours = filter_var($storedSettings->get(self::DURATION_HOURS_KEY), FILTER_VALIDATE_INT);

            $tFixedSlots = self::decodeFixedSlots($storedSettings->get(self::TOMORROW_FIXED_SLOTS_KEY));
            $tEveningStart = self::normalizeTimeValue($storedSettings->get(self::TOMORROW_EVENING_START_KEY));
            $tLastEnd = self::normalizeTimeValue($storedSettings->get(self::TOMORROW_LAST_END_KEY));
            $tDurationHours = filter_var($storedSettings->get(self::TOMORROW_DURATION_HOURS_KEY), FILTER_VALIDATE_INT);

            return [
                'fixed_slots' => $storedSettings->has(self::FIXED_SLOTS_KEY) ? $fixedSlots : $defaults['fixed_slots'],
                'fixed_slots_text' => self::fixedSlotsToText($storedSettings->has(self::FIXED_SLOTS_KEY) ? $fixedSlots : $defaults['fixed_slots']),
                'evening_start' => $storedSettings->has(self::EVENING_START_KEY) ? $eveningStart : $defaults['evening_start'],
                'last_end' => $storedSettings->has(self::LAST_END_KEY) ? $lastEnd : $defaults['last_end'],
                'slot_duration_hours' => $storedSettings->has(self::DURATION_HOURS_KEY) && $durationHours && $durationHours > 0
                    ? $durationHours
                    : $defaults['slot_duration_hours'],

                'tomorrow_fixed_slots' => $storedSettings->has(self::TOMORROW_FIXED_SLOTS_KEY) ? $tFixedSlots : $defaults['fixed_slots'],
                'tomorrow_fixed_slots_text' => self::fixedSlotsToText($storedSettings->has(self::TOMORROW_FIXED_SLOTS_KEY) ? $tFixedSlots : $defaults['fixed_slots']),
                'tomorrow_evening_start' => $storedSettings->has(self::TOMORROW_EVENING_START_KEY) ? $tEveningStart : $defaults['evening_start'],
                'tomorrow_last_end' => $storedSettings->has(self::TOMORROW_LAST_END_KEY) ? $tLastEnd : $defaults['last_end'],
                'tomorrow_slot_duration_hours' => $storedSettings->has(self::TOMORROW_DURATION_HOURS_KEY) && $tDurationHours && $tDurationHours > 0
                    ? $tDurationHours
                    : $defaults['slot_duration_hours'],
            ];
        });
    }

    public static function defaultSettings(): array
    {
        $fixedSlots = [];

        foreach (config('delivery.fixed_slots', []) as $slot) {
            $normalized = self::normalizeSlot($slot['start'] ?? null, $slot['end'] ?? null);

            if ($normalized) {
                $fixedSlots[] = [
                    'start' => substr($normalized['value'], 0, 5),
                    'end' => substr($normalized['value'], 6, 5),
                ];
            }
        }

        return [
            'fixed_slots' => $fixedSlots,
            'fixed_slots_text' => self::fixedSlotsToText($fixedSlots),
            'evening_start' => self::normalizeTimeValue((string) config('delivery.evening_slots.start', '16:00')) ?? '16:00',
            'last_end' => self::normalizeTimeValue((string) config('delivery.evening_slots.last_end', '20:00')) ?? '20:00',
            'slot_duration_hours' => max(1, (int) config('delivery.slot_duration_hours', 2)),
        ];
    }

    public static function updateSettings(array $settings): array
    {
        $fixedSlots = array_values(array_map(fn (array $slot) => [
            'start' => $slot['start'],
            'end' => $slot['end'],
        ], $settings['fixed_slots'] ?? []));

        $tomorrowFixedSlots = array_values(array_map(fn (array $slot) => [
            'start' => $slot['start'],
            'end' => $slot['end'],
        ], $settings['tomorrow_fixed_slots'] ?? []));

        AppSetting::query()->updateOrCreate(
            ['key' => self::FIXED_SLOTS_KEY],
            ['value' => json_encode($fixedSlots)]
        );

        AppSetting::query()->updateOrCreate(
            ['key' => self::EVENING_START_KEY],
            ['value' => $settings['evening_start'] ?: null]
        );

        AppSetting::query()->updateOrCreate(
            ['key' => self::LAST_END_KEY],
            ['value' => $settings['last_end'] ?: null]
        );

        AppSetting::query()->updateOrCreate(
            ['key' => self::DURATION_HOURS_KEY],
            ['value' => (string) max(1, (int) ($settings['slot_duration_hours'] ?? 2))]
        );

        AppSetting::query()->updateOrCreate(
            ['key' => self::TOMORROW_FIXED_SLOTS_KEY],
            ['value' => json_encode($tomorrowFixedSlots)]
        );

        AppSetting::query()->updateOrCreate(
            ['key' => self::TOMORROW_EVENING_START_KEY],
            ['value' => $settings['tomorrow_evening_start'] ?: null]
        );

        AppSetting::query()->updateOrCreate(
            ['key' => self::TOMORROW_LAST_END_KEY],
            ['value' => $settings['tomorrow_last_end'] ?: null]
        );

        AppSetting::query()->updateOrCreate(
            ['key' => self::TOMORROW_DURATION_HOURS_KEY],
            ['value' => (string) max(1, (int) ($settings['tomorrow_slot_duration_hours'] ?? 2))]
        );

        Cache::forget(self::CACHE_KEY);

        return self::settings();
    }

    public static function fixedSlotsToText(array $fixedSlots): string
    {
        return collect($fixedSlots)
            ->map(fn (array $slot) => ($slot['start'] ?? '') . '-' . ($slot['end'] ?? ''))
            ->filter(fn (string $line) => $line !== '-')
            ->implode(PHP_EOL);
    }

    public static function values(string $day = 'today'): array
    {
        return array_column(self::options($day), 'value');
    }

    public static function availableValues(string $day = 'today', ?Carbon $now = null): array
    {
        return array_column(self::availableOptions($day, $now), 'value');
    }

    public static function defaultValue(string $day = 'today'): ?string
    {
        return self::options($day)[0]['value'] ?? null;
    }

    public static function defaultAvailableValue(string $day = 'today', ?Carbon $now = null): ?string
    {
        return self::availableOptions($day, $now)[0]['value'] ?? null;
    }

    public static function label(?string $value, string $day = 'today'): ?string
    {
        if (! $value) {
            return null;
        }

        foreach (self::options($day) as $slot) {
            if (($slot['value'] ?? null) === $value) {
                return $slot['label'] ?? null;
            }
        }

        // Check alternative day as fallback
        $altDay = strtolower($day) === 'tomorrow' ? 'today' : 'tomorrow';
        foreach (self::options($altDay) as $slot) {
            if (($slot['value'] ?? null) === $value) {
                return $slot['label'] ?? null;
            }
        }

        [$start, $end] = array_pad(explode('-', $value, 2), 2, null);

        return self::normalizeSlot($start, $end)['label'] ?? null;
    }


    private static function normalizeSlot(?string $start, ?string $end): ?array
    {
        $startTime = self::parseTime($start);
        $endTime = self::parseTime($end);

        if (! $startTime || ! $endTime || $endTime <= $startTime) {
            return null;
        }

        return [
            'value' => $startTime->format('H:i') . '-' . $endTime->format('H:i'),
            'label' => $startTime->format('h:i A') . ' - ' . $endTime->format('h:i A'),
        ];
    }

    private static function decodeFixedSlots(mixed $value): array
    {
        if (! is_string($value) || trim($value) === '') {
            return [];
        }

        $decoded = json_decode($value, true);

        if (! is_array($decoded)) {
            return [];
        }

        $slots = [];

        foreach ($decoded as $slot) {
            $normalized = self::normalizeSlot($slot['start'] ?? null, $slot['end'] ?? null);

            if ($normalized) {
                $slots[] = [
                    'start' => substr($normalized['value'], 0, 5),
                    'end' => substr($normalized['value'], 6, 5),
                ];
            }
        }

        return $slots;
    }

    private static function normalizeTimeValue(?string $time): ?string
    {
        $parsed = self::parseTime($time);

        return $parsed?->format('H:i');
    }

    private static function parseTime(?string $time): ?DateTimeImmutable
    {
        if (! $time) {
            return null;
        }

        $parsed = DateTimeImmutable::createFromFormat('!H:i', $time);

        return $parsed ?: null;
    }

    private static function timezone(): string
    {
        return (string) config('delivery.timezone', 'Asia/Kolkata');
    }
}
