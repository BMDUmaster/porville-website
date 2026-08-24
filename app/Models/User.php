<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = ['name', 'email', 'password', 'phone', 'date_of_birth', 'gender', 'delivery_charge', 'photo', 'role', 'status'];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'date_of_birth'     => 'date',
            'delivery_charge'   => 'float',
            'password'          => 'hashed',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function canPlaceOrders(): bool
    {
        return $this->status === 'active';
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function receivedNotifications()
    {
        return $this->hasMany(Notification::class, 'recipient_id');
    }
}
