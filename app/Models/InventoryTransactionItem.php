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
        'unit_price',
        'equipment_codes_id',
        'property_number',
    ];

    public function transaction()
    {
        return $this->belongsTo(InventoryTransaction::class, 'inventory_transactions_id', 'id');
    }

    public function bac_reso_item()
    {
        return $this->hasOne(BACResoItem::class, 'id', 'b_a_c_reso_items_id');
    }

    public function properties()
    {
        return $this->hasMany(InventoryTransactionItemProperty::class, 'inventory_transaction_items_id', 'id');
    }

    public function equipment_code()
    {
        return $this->hasOne(EquipmentCode::class, 'id', 'equipment_codes_id');
    }
}
