<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProProManPlanHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'pro_pro_man_plans_id',
        'before_state',
        'after_state',
        'remarks',
        'is_confirm',
        'changes_summary',
        'record_by',
    ];

    public function ppmp()
    {
        return $this->belongsTo(ProProManPlan::class, 'pro_pro_man_plans_id', 'id');
    }

    public function changes_record_by()
    {
        return $this->hasOne(User::class, 'id', 'record_by');
    }
}
