<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BACReso extends Model
{
    use HasFactory;
    protected $fillable = [
        'b_a_c_reso_number',
        'abstract_of_canvasses_id',
        'rfq_reference_numbers',
        'rfq_date',
        'opening_quotation_location',
        'opening_quotation_date',
        'is_delete',
        'is_draft',
        'added_by',
    ];

    public function abstract_of_canvass()
    {
        return $this->hasOne(AbstractOfCanvass::class, 'id', 'abstract_of_canvasses_id');
    }

    public function bac_reso_items()
    {
        return $this->hasMany(BACResoItem::class, 'b_a_c_resos_id', 'id');
    }

    public function purchase_order()
    {
        return $this->hasOne(PurchaseOrder::class, 'b_a_c_resos_id', 'id');
    }
}
