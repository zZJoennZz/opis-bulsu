<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyTransfer extends Model
{
    use HasFactory;
    protected $fillable = [
        'inventory_transaction_items_id',
        'number',
        'quantity',
        'reason',
        'type',
        'other_type',
        'added_by',
    ];

    public function item()
    {
        return $this->hasOne(InventoryTransactionItem::class, 'id', 'inventory_transaction_items_id');
    }

    public function property()
    {
        return $this->hasOne(PropertyTransferProperty::class, 'property_transfers_id', 'id');
    }

    public function receivers()
    {
        return $this->hasMany(PropertyTransferReceiver::class, 'property_transfers_id', 'id');
    }

    public function issuers()
    {
        return $this->hasMany(PropertyTransferIssuer::class, 'property_transfers_id', 'id');
    }
}
