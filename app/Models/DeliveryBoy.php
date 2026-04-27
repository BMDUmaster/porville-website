<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryBoy extends Model
{
    use HasFactory;

    protected $fillable = [
        'partner_name',
        'phone_number',
        'area',
        'status',
        'last_assigned',
    ];

    protected function casts(): array
    {
        return [
            'last_assigned' => 'datetime',
        ];
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
