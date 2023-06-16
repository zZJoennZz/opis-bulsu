<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProProManPlanRevision extends Model
{
    use HasFactory;
    protected $fillable = [
        'pro_pro_man_plans_id',
        'type', //supplemental or revision
        'item_details_id',
    ];

    public function ppmp()
    {
        return $this->belongsTo(ProProManPlan::class, 'id', 'pro_pro_man_plans_id');
    }

    public function item_detail()
    {
        return $this->hasOne(ItemDetail::class, 'id', 'item_details_id');
    }
}
