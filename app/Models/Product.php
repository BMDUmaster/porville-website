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
        return $query->where('is_active', true);
    }
}
