<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class ProductSlot extends Model
{
    public const TYPE_DAILY = 'daily';
    public const TYPE_DATE = 'date';

    protected $fillable = ['name', 'type', 'slot_date', 'start_time', 'end_time', 'is_active'];

    protected $casts = [
        'slot_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_slot_product');
    }

    public function isDaily(): bool
    {
        return $this->type === self::TYPE_DAILY;
    }

    /**
     * A one-date slot whose date (and window) is already over.
     */
    public function isExpired(?Carbon $now = null): bool
    {
        if ($this->isDaily() || ! $this->slot_date) {
            return false;
        }

        $now ??= now();

        return $this->windowOn($this->slot_date)[1]->lte($now);
    }

    /**
     * [start, end] of this slot on the given calendar day (app timezone).
     */
    public function windowOn(Carbon $day): array
    {
        $date = $day->copy()->timezone(config('app.timezone'))->toDateString();
        $tz = config('app.timezone');

        return [
            Carbon::parse($date . ' ' . $this->start_time, $tz),
            Carbon::parse($date . ' ' . $this->end_time, $tz),
        ];
    }

    public function timeRangeLabel(): string
    {
        return self::formatTime($this->start_time) . ' – ' . self::formatTime($this->end_time);
    }

    public static function formatTime(string $time): string
    {
        return Carbon::parse($time)->format('g:i A');
    }
}
