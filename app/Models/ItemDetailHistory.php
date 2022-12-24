<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemDetailHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_details_id',
        'added_by',
        'before_change',
        'after_change',
        'changes'
    ];

    public function item_detail()
    {
        return $this->hasOne(ItemDetail::class, 'id', 'item_details_id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'added_by');
    }
}
