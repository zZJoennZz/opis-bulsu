<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplyOfficeEmployee extends Model
{
    use HasFactory;
    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'supply_positions_id',
        'is_delete',
        'added_by',
    ];

    public function position()
    {
        return $this->hasOne(SupplyPosition::class, 'id', 'supply_positions_id');
    }
}
