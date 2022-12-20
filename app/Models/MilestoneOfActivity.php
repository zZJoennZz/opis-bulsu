<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MilestoneOfActivity extends Model
{
    use HasFactory;
    protected $fillable = [
        'pro_pro_man_plans_id',
        'milestone_formats_id',
        'milestone_value_id',
        'milestone_value'
    ];
}
