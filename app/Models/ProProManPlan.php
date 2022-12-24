<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProProManPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_details_id',
        'year',
        'branches_id',
        'is_draft',
        'is_bo_approve',
        'is_pr_approve',
        'source_of_funds_id',
        'item_purposes_id',
        'estimated_budget',
        'is_priority',
        'is_delete',
        'remarks',
        'submitted_by',
    ];

    public function milestones()
    {
        return $this->hasMany(MilestoneOfActivity::class, 'pro_pro_man_plans_id', 'id');
    }
}
