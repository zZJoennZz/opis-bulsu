<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryTransactionItem extends Model
{
    use HasFactory;
    protected $fillable = [
        'inventory_transactions_id',
        'b_a_c_reso_items_id',
        'quantity',
        'equipment_codes_id',
        'property_number',
    ];

    public function bac_reso_item()
    {
        return $this->hasOne(BACResoItem::class, 'id', 'b_a_c_reso_items_id');
    }
}
