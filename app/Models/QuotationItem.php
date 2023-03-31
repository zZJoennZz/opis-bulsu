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
        // 'pro_pro_man_plans_id', //instead of ppmp, will connect to the PR instead
        'purchase_request_items_id',
        'brand_and_model_offered',
        'offered_unit_price'
    ];

    // public function ppmp() //no need since we will connect quotation items to purchase request items instead of PPMP
    // {
    //     return $this->hasOne(ProProManPlan::class, 'id', 'pro_pro_man_plans_id');
    // }

    public function pr_item()
    {
        return $this->belongsTo(PurchaseRequestItem::class, 'purchase_request_items_id', 'id');
    }

    public function quotation()
    {
        return $this->belongsTo(Quotation::class, 'quotations_id', 'id');
    }

    public function bac_reso_item()
    {
        return $this->hasOne(BACResoItem::class, 'quotation_items_id', 'id');
    }

    public function canvass_abstract_item()
    {
        return $this->hasOne(CanvassAbstractItem::class, 'quotation_items_id', 'id');
    }
}
