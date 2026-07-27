<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    public const STATUSES = ['pending', 'approved', 'hidden'];

    protected $fillable = ['order_id', 'user_id', 'product_id', 'rating', 'comment', 'status', 'display_on', 'reviewed_at'];

    protected $casts = ['reviewed_at' => 'datetime', 'rating' => 'integer'];

    public function order() { return $this->belongsTo(Order::class); }
    public function user() { return $this->belongsTo(User::class); }
    public function product() { return $this->belongsTo(Product::class); }
}
