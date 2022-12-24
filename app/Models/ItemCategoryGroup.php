<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemCategoryGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'report_sub_total_footer',
        'order',
        'under_of_secion',
    ];
}
