<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BACResoItem extends Model
{
    use HasFactory;
    protected $fillable = [
        'b_a_c_resos_id',
        'quotation_items_id',
        'is_select',
        'note',
    ];

    public function quotation()
    {
        return $this->hasOne(QuotationItem::class, 'id', 'quotation_items_id');
    }

    public function bac_reso()
    {
        return $this->belongsTo(BACReso::class, 'b_a_c_resos_id', 'id');
    }
}
