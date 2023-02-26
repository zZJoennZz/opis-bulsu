<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseRequestItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_requests_id',
        'pro_pro_man_plans_id',
    ];

    public function pr()
    {
        return $this->belongsTo(PurchaseRequest::class, 'purchase_requests_id', 'id');
    }

    public function ppmp()
    {
        return $this->belongsTo(ProProManPlan::class, 'pro_pro_man_plans_id', 'id');
    }

    public function quotations()
    {
        return $this->hasMany(QuotationItem::class, 'purchase_request_items_id', 'id');
    }
}
