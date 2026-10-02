<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = ['name', 'email', 'password', 'phone', 'date_of_birth', 'gender', 'delivery_charge', 'photo', 'role', 'permissions', 'status'];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'date_of_birth'     => 'date',
            'delivery_charge'   => 'float',
            'password'          => 'hashed',
            'permissions'       => 'array',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Main admin or sub admin (anyone allowed into the admin panel).
     */
    public function isStaff(): bool
    {
        return in_array($this->role, ['admin', 'sub_admin'], true);
    }

    /**
     * Module keys a sub admin may use (see App\Support\AdminModules).
     */
    public function getPermissionListAttribute(): array
    {
        return array_values(array_filter((array) ($this->permissions ?? []), 'is_string'));
    }

    /**
     * Profile photo URL for the admin header / profile page.
     */
    public function getPhotoUrlAttribute(): ?string
    {
        return $this->photo ? \App\Support\MediaUrl::storage($this->photo) : null;
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
