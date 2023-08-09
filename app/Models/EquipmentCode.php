<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EquipmentCode extends Model
{
    use HasFactory;
    protected $fillable = [
        'equipment_code',
        'unique_code',
        'description',
        'article', //ONLY SEMI-EXPENDABLE and NON-SEMI-EXPENDABLE for now
        'is_delete',
        'added_by',
    ];

    public function items()
    {
        return $this->hasMany(InventoryTransactionItem::class, 'equipment_codes_id', 'id');
    }
}
