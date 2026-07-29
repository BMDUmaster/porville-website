<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomeBanner extends Model
{
    use HasFactory;

    protected $fillable = [
        'image',
        'mobile_image',
        'badge',
        'title_1',
        'title_2',
        'description',
        'button_text',
        'link_url',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function getImageUrlAttribute(): string
    {
        if (str_starts_with($this->image, 'http://') || str_starts_with($this->image, 'https://')) {
            return $this->image;
        }

        // Match the product/category image URLs. On deployments where public
        // is not the document root, ASSET_URL supplies the required /public
        // segment while APP_URL remains the clean application URL.
        return asset('storage/' . ltrim($this->image, '/'));
    }

    public function getMobileImageUrlAttribute(): ?string
    {
        if (! $this->mobile_image) {
            return null;
        }

        if (str_starts_with($this->mobile_image, 'http://') || str_starts_with($this->mobile_image, 'https://')) {
            return $this->mobile_image;
        }

        return asset('storage/' . ltrim($this->mobile_image, '/'));
    }
}
