<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryTransactionReceiver extends Model
{
    use HasFactory;
    protected $fillable = [
        'inventory_transactions_id',
        'supply_end_users_id',
    ];
}
