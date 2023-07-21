<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyTransfer extends Model
{
    use HasFactory;
    protected $fillable = [
        'inventory_transaction_item_properties_id',
        'number',
        'quantity',
        'reason',
        'added_by',
    ];

    public function property() {
        return $this->belongsTo(InventoryTransactionItemProperty::class, 'id', 'inventory_transaction_item_properties_id');
    }
}
