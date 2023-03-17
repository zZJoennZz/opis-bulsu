<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    use HasFactory;
    protected $fillable = [
        'canvass_abstracts_id',
        'po_number',
        'purchase_order_mode_of_payments_id',
        'is_delete',
        'added_by',
    ];

    public function canvass_abstract()
    {
        return $this->hasOne(CanvassAbstract::class, 'id', 'canvass_abstracts_id');
    }

    public function mop()
    {
        return $this->hasOne(PurchaseOrderModeOfPayment::class, 'id', 'purchase_order_mode_of_payments_id');
    }

    public function iaa()
    {
        return $this->belongsTo(InspectionAndAcceptance::class, 'id', 'purchase_orders_id');
    }
}
