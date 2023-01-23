<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{
    use HasFactory;

    protected $fillable = [
        'companies_id',
        'quotation_number',
        'pro_pro_man_plans_id',
        'purpose'
    ];
}
