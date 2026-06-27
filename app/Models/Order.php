<?php

namespace App\Models;

use App\Support\DeliverySlotManager;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    public const STATUSES = [
        'pending',
        'confirmed',
        'processing',
        'out_for_delivery',
        'delivered',
        'cancelled',
    ];

    protected $fillable = [
        'user_id', 'delivery_boy_id', 'order_number', 'status', 'subtotal', 'discount',
        'shipping_cost', 'delivery_charge', 'platform_fee',
        'vendor_total', 'admin_commission', 'tax', 'total',
        'shipping_address', 'payment_method', 'payment_status', 'delivery_slot', 'delivery_day',
    ];

    protected $casts = [
        'shipping_address' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function deliveryBoy()
    {
        return $this->belongsTo(DeliveryBoy::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return ucwords(str_replace('_', ' ', $this->status));
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'pending'    => 'bg-amber-100 text-amber-700',
            'confirmed'  => 'bg-blue-100 text-blue-700',
            'processing' => 'bg-yellow-100 text-yellow-700',
            'out_for_delivery' => 'bg-indigo-100 text-indigo-700',
            'delivered'  => 'bg-emerald-100 text-emerald-700',
            'cancelled'  => 'bg-red-100 text-red-700',
            default      => 'bg-gray-100 text-gray-700',
        };
    }

    public function getDeliverySlotLabelAttribute(): ?string
    {
        return DeliverySlotManager::label($this->delivery_slot);
    }

    public function getServiceChargeAttribute(): float
    {
        return round((float) ($this->platform_fee ?? 0), 2);
    }

    public function getServiceChargePercentAttribute(): ?float
    {
        $subtotal = (float) ($this->subtotal ?? 0);

        if ($subtotal <= 0) {
            return null;
        }

        return round(($this->service_charge / $subtotal) * 100, 2);
    }
}
