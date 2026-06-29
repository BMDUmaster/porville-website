<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CouponUserUsage extends Model
{
    protected $fillable = ['coupon_id', 'user_id', 'usage_count'];
}
