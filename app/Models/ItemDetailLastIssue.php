<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemDetailLastIssue extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'item_details_id',
        'issued_qty',
    ];

    public function item_detail()
    {
        $this->belongsTo(ItemDetail::class, 'item_details_id', 'id');
    }
}
