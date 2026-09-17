<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceChargeTier extends Model
{
    protected $fillable = [
        'min_amount',
        'max_amount',
        'percent',
    ];

    protected $casts = [
        'min_amount' => 'float',
        'max_amount' => 'float',
        'percent' => 'float',
    ];
}
