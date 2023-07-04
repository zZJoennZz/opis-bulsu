<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryTransferReceiver extends Model
{
    use HasFactory;
    protected $fillable = [
        'inventory_transfers_id',
        'supply_end_users_id',
    ];

    public function transfer() {
        return $this->belongsTo(InventoryTransfer::class, 'id', 'inventory_transfers_id');
    }

    public function end_user() {
        return $this->hasOne(SupplyEndUser::class, 'id', 'supply_end_users_id');
    }
}
