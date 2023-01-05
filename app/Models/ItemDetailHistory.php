<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemDetailHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_details_id',
        'action_by',
        'before_change',
        'after_change',
        'changes',
        'is_approve',
        'remarks',
    ];

    public function item_detail()
    {
        return $this->hasOne(ItemDetail::class, 'id', 'item_details_id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'action_by');
    }
}
