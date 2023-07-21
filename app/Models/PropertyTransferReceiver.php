<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyTransferReceiver extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_transfers_id',
        'supply_end_users_id',
    ];
}
