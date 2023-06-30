<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryTransferItem extends Model
{
    use HasFactory;
    protected $fillable = [
        'inventory_transfers_id',
        'inventory_transaction_item_serial_numbers_id',
    ];

    public function transfer() {
        return $this->belongsTo(InventoryTransfer::class, 'id', 'inventory_transfers_id');
    }

    public function serial_number() {
        return $this->hasOne(InventoryTransactionItemSerialNumber::class, 'id', 'inventory_transaction_item_serial_numbers_id');
    }
}
