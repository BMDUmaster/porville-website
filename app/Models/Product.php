<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id', 'subcategory_id', 'name', 'slug', 'description',
        'price', 'mrp', 'weight', 'unit', 'contact_number', 'processing_note', 'delivery_note', 'stock', 'images', 'videos', 'variants', 'is_active',
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
        return $query->whereHas('category', function ($q) {
                $q->where('categories.is_active', true);
            })
            ->where(function ($q) {
                $q->whereNull('products.subcategory_id')
                  ->orWhereHas('subcategory', function ($sq) {
                      $sq->where('categories.is_active', true);
                  });
            });
    }

    /**
     * Excludes enquiry-only products (e.g. Live Stock) from algorithmic picks
     * like New Arrivals / Featured / Best Sellers, which assume normal cart pricing.
     */
    public function scopeNotEnquiryOnly($query)
    {
        return $query->whereHas('category', function ($q) {
            $q->where('is_enquiry_only', false);
        });
    }

    public function getIsOutOfStockAttribute(): bool
    {
        return !$this->is_active || ($this->stock !== null && (int) $this->stock <= 0);
    }

    /**
     * Enquiry-only products (e.g. Live Stock) aren't sold through the cart —
     * storefront shows a "Call to Order" button using contact_number instead.
     */
    public function getIsEnquiryOnlyAttribute(): bool
    {
        return (bool) ($this->category?->is_enquiry_only ?? $this->subcategory?->is_enquiry_only ?? false);
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

    /**
     * Normalized list of purchasable pack options for product cards, one entry
     * per variant that has a valid today price (falls back to the base
     * price/pack when the product has no variants at all).
     */
    public function getCardVariantOptionsAttribute(): array
    {
        $variants = collect($this->variants ?? [])
            ->filter(fn ($variant) => filled($variant['selling_price'] ?? null) || filled($variant['today_price'] ?? null))
            ->values();

        if ($variants->isEmpty()) {
            return [[
                'index' => null,
                'label' => $this->formatPackLabel($this->weight ?? null, $this->unit ?? null, null),
                'price' => (float) $this->price,
                'mrp' => (float) ($this->mrp ?? $this->price),
            ]];
        }

        return $variants->map(function ($variant, $i) {
            $originalIndex = collect($this->variants)->search($variant, true);

            return [
                'index' => $originalIndex,
                'label' => $this->formatPackLabel($variant['quantity'] ?? null, $variant['unit'] ?? $this->unit, $variant['piece'] ?? null),
                'price' => \App\Support\ProductDayPricing::sellingPrice($variant, 'today', (float) $this->price),
                'mrp' => \App\Support\ProductDayPricing::mrp($variant, (float) ($this->mrp ?? $this->price)),
            ];
        })->values()->all();
    }

    public function getCardFromPriceAttribute(): float
    {
        return collect($this->card_variant_options)->min('price') ?? (float) $this->price;
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
