<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseRequestMode extends Model
{
    use HasFactory;
    protected $fillable = [
        'branches_id',
        'year',
        'mode'
    ];
}
