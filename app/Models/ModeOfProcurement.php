<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModeOfProcurement extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'added_by',
        'is_delete'
    ];
}
