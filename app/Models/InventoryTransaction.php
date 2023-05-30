<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryTransaction extends Model
{
    use HasFactory;
    protected $fillable = [
        'type', //ICSH, ICSL, PAR
        'number',
        'date_acquired',
        'purchase_orders_id',
        'date_issued',
        'is_delete',
        'added_by',
    ];

    public function items()
    {
        return $this->hasMany(InventoryTransactionItem::class, 'inventory_transactions_id', 'id');
    }
}
