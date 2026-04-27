<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'entry_type', 'title', 'description', 'code', 'type', 'value',
        'min_order_amount', 'max_uses', 'used_count', 'expires_at', 'is_active',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'is_active'  => 'boolean',
    ];

    public function scopeCoupons($query)
    {
        return $query->where('entry_type', 'coupon');
    }

    public function scopeOffers($query)
    {
        return $query->where('entry_type', 'offer');
    }

    public function scopeValid($query)
    {
        return $query->coupons()
            ->where('is_active', true)
            ->where(function ($innerQuery) {
                $innerQuery->whereNull('max_uses')
                    ->orWhereColumn('used_count', '<', 'max_uses');
            })
            ->where(function ($innerQuery) {
                $innerQuery->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            });
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
