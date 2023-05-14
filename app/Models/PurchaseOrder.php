<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    use HasFactory;
    protected $fillable = [
        'b_a_c_resos_id',
        'companies_id',
        'year',
        'po_number',
        'purchase_order_mode_of_payments_id',
        'mode_of_procurements_id ',
        'note',
        'place_of_delivery',
        'date_of_delivery',
        'for_inquiry',
        'delivery_term',
        'accountant_name',
        'is_delete',
        'added_by',
    ];

    public function canvass_abstract()
    {
        return $this->hasOne(CanvassAbstract::class, 'id', 'canvass_abstracts_id');
    }

    public function company()
    {
        return $this->hasOne(Company::class, 'id', 'companies_id');
    }

    public function mop()
    {
        return $this->hasOne(PurchaseOrderModeOfPayment::class, 'id', 'purchase_order_mode_of_payments_id');
    }

    public function mode_of_procurement()
    {
        return $this->hasOne(ModeOfProcurement::class, 'id', 'mode_of_procurements_id');
    }

    public function iaa()
    {
        return $this->belongsTo(InspectionAndAcceptance::class, 'id', 'purchase_orders_id');
    }

    public function bac_reso()
    {
        return $this->hasOne(BACReso::class, 'id', 'b_a_c_resos_id');
    }
}
