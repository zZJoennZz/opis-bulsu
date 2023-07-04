<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryTransfer extends Model
{
    use HasFactory;
    protected $fillable = [
        'number',
        'reason',
        'quantity',
        'inventory_transaction_items_id',
        'added_by',
    ];

    public function item() {
        return $this->hasOne(InventoryTransactionItem::class, 'id', 'inventory_transaction_items_id');
    }

    public function items() {
        return $this->hasMany(InventoryTransferItem::class, 'inventory_transfers_id', 'id');
    }

    public function issuers() {
        return $this->hasMany(InventoryTransferIssuer::class, 'inventory_transfers_id', 'id');
    }

    public function receivers() {
        return $this->hasMany(InventoryTransferReceiver::class, 'inventory_transfers_id', 'id');
    }
}
