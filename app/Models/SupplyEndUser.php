<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplyEndUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'branches_id',
        'supply_positions_id',
        'is_delete',
        'added_by',
    ];

    public function branch()
    {
        return $this->hasOne(Branch::class, 'id', 'branches_id');
    }

    public function position()
    {
        return $this->hasOne(SupplyPosition::class, 'id', 'supply_positions_id');
    }

    public function keepers()
    {
        return $this->hasMany(InventoryTransactionItemPropertyCurrentKeeper::class, 'supply_end_users_id', 'id');
    }
}
