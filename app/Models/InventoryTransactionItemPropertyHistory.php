<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryTransactionItemPropertyHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'inventory_transaction_item_properties_id',
        'type', //AVAILABLE, RETURN, DISPOSAL, MAINTENANCE
        'details',
        'added_by',
    ];

    public function property() {
        return $this->belongsTo(InventoryTransactionItemProperty::class, 'id', 'inventory_transaction_item_properties_id');
    }
}
