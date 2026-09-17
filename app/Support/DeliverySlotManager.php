<?php

namespace App\Support;

use App\Models\DeliverySlot;
use Illuminate\Support\Carbon;

class DeliverySlotManager
{
    /**
     * Admin can add slots for any calendar date. "today"/"tomorrow" here are
     * just the two labels the storefront currently lets a customer pick —
     * they resolve to the real calendar date and look up whatever the admin
     * configured for that date.
     */
    public static function options(string $day = 'today'): array
    {
        return self::optionsForDate(self::resolveDate($day));
    }

    public static function optionsForDate(Carbon $date): array
    {
        return DeliverySlot::query()
            ->whereDate('date', $date->toDateString())
            ->where('is_active', true)
            ->orderBy('start_time')
            ->get()
            ->map(fn (DeliverySlot $slot) => self::formatSlot($slot))
            ->values()
            ->all();
    }

    public static function availableOptions(string $day = 'today', ?Carbon $now = null): array
    {
        $day = strtolower($day) === 'tomorrow' ? 'tomorrow' : 'today';

        return self::availableOptionsForDate(self::resolveDate($day, $now), $now);
    }

    /**
     * Same as optionsForDate(), but drops any slot whose start time has already
     * passed — only relevant when $date is today; future dates are unaffected.
     */
    public static function availableOptionsForDate(Carbon $date, ?Carbon $now = null): array
    {
        $options = self::optionsForDate($date);
        $now = ($now ?: Carbon::now(self::timezone()))->copy()->timezone(self::timezone());

        if (! $date->isSameDay($now)) {
            return $options;
        }

        return array_values(array_filter($options, function (array $slot) use ($now, $date) {
            [$start] = array_pad(explode('-', (string) ($slot['value'] ?? ''), 2), 2, null);

            if (! $start) {
                return false;
            }

            $slotStart = Carbon::createFromFormat('Y-m-d H:i', $date->toDateString() . ' ' . $start, self::timezone());

            return $slotStart && $slotStart->greaterThan($now);
        }));
    }

    public static function resolveDate(string $day, ?Carbon $now = null): Carbon
    {
        $now = ($now ?: Carbon::now(self::timezone()))->copy()->timezone(self::timezone())->startOfDay();

        return strtolower($day) === 'tomorrow' ? $now->addDay() : $now;
    }

    /**
     * All upcoming (today onward) dates that have at least one configured slot,
     * for the admin panel — grouped so the admin can see/manage each date at a glance.
     */
    public static function datesWithSlots()
    {
        return DeliverySlot::query()
            ->where('date', '>=', Carbon::now(self::timezone())->toDateString())
            ->orderBy('date')
            ->orderBy('start_time')
            ->get()
            ->groupBy(fn (DeliverySlot $slot) => $slot->date->toDateString())
            ->map(fn ($slots, $date) => [
                'date' => $date,
                'date_label' => Carbon::parse($date)->format('D, d M Y'),
                'slots' => $slots->map(fn (DeliverySlot $slot) => [
                    'id' => $slot->id,
                    'label' => self::formatSlot($slot)['label'],
                ])->values(),
            ])
            ->values();
    }

    /**
     * Upcoming dates a customer can actually pick from at checkout — only dates
     * that still have at least one active, not-yet-passed slot.
     */
    public static function upcomingDatesForCustomer(?Carbon $now = null): array
    {
        $now = $now ?: Carbon::now(self::timezone());

        return DeliverySlot::query()
            ->where('date', '>=', $now->toDateString())
            ->where('is_active', true)
            ->orderBy('date')
            ->get()
            ->groupBy(fn (DeliverySlot $slot) => $slot->date->toDateString())
            ->map(fn ($slots, $date) => [
                'date' => $date,
                'date_label' => Carbon::parse($date)->isSameDay($now) ? 'Today' : Carbon::parse($date)->format('D, d M'),
                'options' => self::availableOptionsForDate(Carbon::parse($date, self::timezone()), $now),
            ])
            ->filter(fn (array $day) => ! empty($day['options']))
            ->values()
            ->all();
    }

    public static function addSlot(string $date, string $start, string $end): DeliverySlot
    {
        return DeliverySlot::query()->firstOrCreate([
            'date' => $date,
            'start_time' => $start,
            'end_time' => $end,
        ], [
            'is_active' => true,
        ]);
    }

    public static function deleteSlot(int $id): void
    {
        DeliverySlot::query()->whereKey($id)->delete();
    }

    public static function deleteDate(string $date): void
    {
        DeliverySlot::query()->whereDate('date', $date)->delete();
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

    private static function formatSlot(DeliverySlot $slot): array
    {
        return self::normalizeSlot(
            substr((string) $slot->start_time, 0, 5),
            substr((string) $slot->end_time, 0, 5)
        ) ?? ['value' => null, 'label' => null];
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

    private static function parseTime(?string $time): ?\DateTimeImmutable
    {
        if (! $time) {
            return null;
        }

        $parsed = \DateTimeImmutable::createFromFormat('!H:i', $time);

        return $parsed ?: null;
    }

    private static function timezone(): string
    {
        return (string) config('delivery.timezone', 'Asia/Kolkata');
    }
}
