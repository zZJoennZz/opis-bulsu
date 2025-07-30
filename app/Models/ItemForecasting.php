<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemForecasting extends Model
{
    use HasFactory;
    protected $fillable = [
        'item_details_id',
        'alpha',
        'group',
        'from_date',
        'to_date',
        'data',
    ];

    public function item_detail()
    {
        return $this->belongsTo(ItemDetail::class, 'item_details_id', 'id');
    }
}
