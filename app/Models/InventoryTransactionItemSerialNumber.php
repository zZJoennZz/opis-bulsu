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
}
