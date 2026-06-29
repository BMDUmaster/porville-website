<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'entry_type', 'title', 'description', 'code', 'type', 'value',
        'min_order_amount', 'max_uses', 'per_user_limit', 'used_count', 'expires_at', 'is_active',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'is_active'  => 'boolean',
    ];

    public function scopeCoupons($query)
    {
        if (! Schema::hasColumn($this->getTable(), 'entry_type')) {
            return $query;
        }

        return $query->where('entry_type', 'coupon');
    }

    public function scopeOffers($query)
    {
        if (! Schema::hasColumn($this->getTable(), 'entry_type')) {
            return $query;
        }

        return $query->where('entry_type', 'offer');
    }

    public function scopeValid($query)
    {
        return $query->where('is_active', true)
            ->where(function ($innerQuery) {
                $innerQuery->whereNull('max_uses')
                    ->orWhereColumn('used_count', '<', 'max_uses');
            })
            ->where(function ($innerQuery) {
                $innerQuery->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            });
    }

    public function usageCountFor(?int $userId): int
    {
        if (! $userId) {
            return 0;
        }

        return (int) CouponUserUsage::where('coupon_id', $this->id)
            ->where('user_id', $userId)
            ->value('usage_count');
    }

    public function canBeUsedBy(?int $userId): bool
    {
        return ! $this->per_user_limit
            || ($userId && $this->usageCountFor($userId) < $this->per_user_limit);
    }

    public function scopeActiveEntries($query)
    {
        return $query->where('is_active', true)
            ->where(function ($innerQuery) {
                $innerQuery->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            });
    }
}
