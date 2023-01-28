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
        'under_of_section',
    ];

    public function category_group_section()
    {
        return $this->belongsTo(ItemCategoryGroupSection::class, 'under_of_section', 'id');
    }

    public function categories()
    {
        return $this->hasMany(ItemCategory::class, 'under_of_group', 'id');
    }
}
