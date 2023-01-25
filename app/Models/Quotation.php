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
        'year'
    ];

    public function items()
    {
        return $this->hasMany(QuotationItem::class, 'quotations_id', 'id');
    }

    public function company()
    {
        return $this->hasOne(Company::class, 'id', 'companies_id');
    }
}
