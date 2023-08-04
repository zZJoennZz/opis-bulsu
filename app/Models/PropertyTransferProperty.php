<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyTransferProperty extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_transfers_id',
        'inventory_transaction_item_properties_id',
    ];

    public function property()
    {
        return $this->hasOne(InventoryTransactionItemProperty::class, 'id', 'inventory_transaction_item_properties_id');
    }

    public function transfer()
    {
        return $this->belongsTo(PropertyTransfer::class, 'property_transfers_id', 'id');
    }
}
