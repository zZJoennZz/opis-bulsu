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
        'branches_id',
        'date_acquired',
        'purchase_orders_id',
        'date_issued',
        'is_delete',
        'added_by',
    ];

    public function purchase_order()
    {
        return $this->hasOne(PurchaseOrder::class, 'id', 'purchase_orders_id');
    }

    public function items()
    {
        return $this->hasMany(InventoryTransactionItem::class, 'inventory_transactions_id', 'id');
    }

    public function issuers()
    {
        return $this->hasMany(InventoryTransactionIssuer::class, 'inventory_transactions_id', 'id');
    }

    public function receivers()
    {
        return $this->hasMany(InventoryTransactionReceiver::class, 'inventory_transactions_id', 'id');
    }

    public function branch() {
        return $this->hasOne(Branch::class, 'id', 'branches_id');
    }
}
