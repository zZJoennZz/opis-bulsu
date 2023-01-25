<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuotationItem extends Model
{
    use HasFactory;
    protected $fillable = [
        'item_number',
        'quotations_id',
        'pro_pro_man_plans_id',
        'brand_and_model_offered',
        'offered_unit_price'
    ];

    public function ppmp()
    {
        return $this->hasOne(ProProManPlan::class, 'id', 'pro_pro_man_plans_id');
    }

    public function quotation()
    {
        return $this->belongsTo(Quotation::class, 'quotations_id', 'id');
    }
}
