<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryTransactionItemSerialNumber extends Model
{
    use HasFactory;
    protected $fillable = [
        'inventory_transaction_items_id',
        'serial_number',
    ];

    public function item() {
        return $this->belongsTo(InventoryTransactionItem::class, 'id', 'inventory_transaction_items_id');
    }

    public function current_end_user() {
        return $this->hasOne(InventoryTransferItem::class, 'inventory_transaction_item_serial_numbers_id', 'id');
    }
}
