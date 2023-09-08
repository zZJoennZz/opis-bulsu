<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryTransactionItemProperty extends Model
{
    use HasFactory;
    protected $fillable = [
        'inventory_transaction_items_id',
        'serial_number',
        'property_condition',
        'is_available',
    ];

    public function item()
    {
        return $this->belongsTo(InventoryTransactionItem::class, 'inventory_transaction_items_id', 'id');
    }

    public function current_owners()
    {
        return $this->hasMany(InventoryTransactionItemPropertyCurrentKeeper::class, 'inventory_transaction_item_properties_id', 'id');
    }

    public function transfers()
    {
        return $this->hasMany(PropertyTransferProperty::class, 'inventory_transaction_item_properties_id', 'id');
    }

    public function histories() {
        return $this->hasMany(InventoryTransactionItemPropertyHistory::class, 'inventory_transaction_item_properties_id', 'id');
    }
}
