<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'message',
        'url',
        'is_read',
        'sent_to',
        'sent_by',
    ];

    public function sentTo()
    {
        return $this->hasOne(User::class, 'sent_to');
    }

    public function sentBy()
    {
        return $this->hasOne(User::class, 'sent_by');
    }
}
