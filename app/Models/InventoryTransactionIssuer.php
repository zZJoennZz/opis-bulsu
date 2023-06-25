<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryTransactionIssuer extends Model
{
    use HasFactory;
    protected $fillable = [
        'inventory_transactions_id',
        'supply_office_employees_id',
    ];

    public function employee()
    {
        return $this->hasOne(SupplyOfficeEmployee::class, 'id', 'supply_office_employees_id');
    }
}
