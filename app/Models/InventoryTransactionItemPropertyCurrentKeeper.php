<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryTransactionItemPropertyCurrentKeeper extends Model
{
    use HasFactory;
    protected $fillable = [
        'inventory_transaction_item_properties_id',
        'supply_end_users_id',
    ];

    public function item()
    {
        return $this->belongsTo(InventoryTransactionItemProperty::class, 'inventory_transaction_item_properties_id', 'id');
    }

    public function end_user()
    {
        return $this->hasOne(SupplyEndUser::class, 'id', 'supply_end_users_id');
    }
}
