<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemDetail extends Model
{
    use HasFactory;

    protected $fillable = ['description', 'category_id', 'unit_id', 'added_by', 'article', 'price_catalogue'];
}
