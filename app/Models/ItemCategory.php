<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemCategory extends Model
{
    use HasFactory;

    protected $fillable = ['description', 'under_of_group', 'added_by', 'is_delete'];

    public function item_details()
    {
        return $this->hasMany(ItemDetail::class, 'category_id', 'id');
    }
}
