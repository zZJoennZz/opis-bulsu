<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemCategoryGroupSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'report_sub_total_footer',
        'order'
    ];
}
