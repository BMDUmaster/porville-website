<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = ['subject', 'message', 'sent_by'];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sent_by');
    }
}
