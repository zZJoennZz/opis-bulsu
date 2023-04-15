<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplyEndUserPositions extends Model
{
    use HasFactory;
    protected $fillable = [
        'position_name',
        'is_delete',
        'added_by',
    ];
}
