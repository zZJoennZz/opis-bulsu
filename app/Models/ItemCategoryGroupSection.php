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

    public function category_groups()
    {
        return $this->hasMany(ItemCategoryGroup::class, 'under_of_section', 'id');
    }
}
