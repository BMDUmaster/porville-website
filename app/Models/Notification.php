<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = ['subject', 'message', 'sent_by', 'recipient_id', 'read_at'];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sent_by');
    }

    public function recipient()
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }

    public function scopeForUser($query, int $userId)
    {
        return $query->where(function ($notificationQuery) use ($userId) {
            $notificationQuery
                ->where('recipient_id', $userId)
                ->orWhereNull('recipient_id');
        });
    }
}
