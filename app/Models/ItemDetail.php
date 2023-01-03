<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemDetail extends Model
{
    use HasFactory;

    protected $fillable = ['description', 'category_id', 'unit_id', 'added_by', 'article', 'price_catalogue', 'is_approve', 'is_delete'];

    public function category()
    {
        return $this->hasOne(ItemCategory::class, 'id', 'category_id');
    }

    public function unit()
    {
        return $this->hasOne(Unit::class, 'id', 'unit_id');
    }

    public function histories()
    {
        return $this->hasMany(ItemDetailHistory::class, 'item_details_id', 'id');
    }
}
