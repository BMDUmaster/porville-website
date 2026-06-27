<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id', 'subcategory_id', 'name', 'slug', 'description',
        'price', 'mrp', 'weight', 'unit', 'stock', 'images', 'videos', 'variants', 'is_active',
    ];

    protected $casts = [
        'images'    => 'array',
        'videos'    => 'array',
        'variants'  => 'array',
        'is_active' => 'boolean',
    ];

    public function subcategory()
    {
        return $this->belongsTo(Category::class, 'subcategory_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function scopeActive($query)
    {
        return $query->where('products.is_active', true)
            ->whereHas('category', function ($q) {
                $q->where('is_active', true);
            })
            ->where(function ($q) {
                $q->whereNull('subcategory_id')
                  ->orWhereHas('subcategory', function ($sq) {
                      $sq->where('is_active', true);
                  });
            });
    }

    public function getDisplayPackLabelAttribute(): string
    {
        $variant = collect($this->variants ?? [])
            ->first(fn ($item) => filled($item['quantity'] ?? null) || filled($item['piece'] ?? null) || filled($item['unit'] ?? null));

        if ($variant) {
            return $this->formatPackLabel(
                $variant['quantity'] ?? null,
                $variant['unit'] ?? $this->unit,
                $variant['piece'] ?? null
            );
        }

        return $this->formatPackLabel($this->weight ?? null, $this->unit ?? null, null);
    }

    public function getDisplayPriceAttribute(): float
    {
        $variant = collect($this->variants ?? [])
            ->first(fn ($item) => filled($item['selling_price'] ?? null) || filled($item['today_price'] ?? null));

        if ($variant) {
            return (float) \App\Support\ProductDayPricing::sellingPrice($variant, 'today', (float) $this->price);
        }

        return (float) $this->price;
    }

    public function getDisplayMrpAttribute(): float
    {
        $variant = collect($this->variants ?? [])
            ->first(fn ($item) => filled($item['mrp'] ?? null));

        return (float) ($variant['mrp'] ?? $this->mrp ?? $this->display_price);
    }

    private function formatPackLabel($quantity, $unit, $piece): string
    {
        $quantity = trim((string) $quantity);
        $unit = $this->normalizeUnit(trim((string) $unit));
        $piece = trim((string) $piece);

        if ($quantity !== '') {
            return trim($quantity . ' ' . $unit);
        }

        if ($piece !== '') {
            return preg_match('/[A-Za-z]/', $piece)
                ? $piece
                : trim($piece . ' ' . ($unit !== '' ? $unit : 'Pcs'));
        }

        return $unit !== '' ? $unit : 'Standard Pack';
    }

    private function normalizeUnit(string $unit): string
    {
        return match (strtolower($unit)) {
            'pc', 'pcs', 'piece', 'pieces' => 'Pcs',
            'kg', 'kgs', 'kilogram', 'kilograms' => 'Kg',
            'gm', 'g', 'gram', 'grams' => 'Gram',
            default => $unit,
        };
    }
}
