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
        'is_consolidate',
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

    public function item_detail()
    {
        return $this->hasOne(ItemDetail::class, 'id', 'item_details_id');
    }

    public function source_of_fund()
    {
        return $this->hasOne(SourceOfFund::class, 'id', 'source_of_funds_id');
    }

    public function item_purpose()
    {
        return $this->hasOne(ItemPurpose::class, 'id', 'item_purposes_id');
    }

    public function branch()
    {
        return $this->hasOne(Branch::class, 'branches_id', 'id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'submitted_by');
    }

    public function user_profile()
    {
        return $this->hasOne(UserProfile::class, 'users_id', 'submitted_by');
    }

    public function pr_item()
    {
        return $this->hasOne(PurchaseRequestItem::class, 'pro_pro_man_plans_id', 'id');
    }
}
