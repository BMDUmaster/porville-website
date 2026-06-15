<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = ['order_id', 'product_id', 'quantity', 'pack_quantity', 'unit_price', 'mrp', 'unit', 'variant_label', 'pricing_day', 'save_offer', 'vendor_amount', 'admin_amount', 'subtotal'];

    protected $casts = [
        'pack_quantity' => 'float',
    ];

    public function order()   { return $this->belongsTo(Order::class); }
    public function product() { return $this->belongsTo(Product::class); }

    public function getPricingDayLabelAttribute(): string
    {
        return match ($this->pricing_day) {
            'tomorrow' => 'Tomorrow',
            default => 'Today',
        };
    }
}
