<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CanvassAbstract extends Model
{
    use HasFactory;
    protected $fillable = [
        'companies_id',
        'year',
        'abc',
        'added_by',
        'is_delete',
    ];

    public function items()
    {
        return $this->hasMany(CanvassAbstractItem::class, 'canvass_abstracts_id', 'id');
    }

    public function added_by()
    {
        return $this->hasOne(User::class, 'id', 'added_by');
    }

    public function company()
    {
        return $this->hasOne(Company::class, 'id', 'companies_id');
    }

    public function purchase_order()
    {
        return $this->hasOne(PurchaseOrder::class, 'canvass_abstracts_id', 'id');
    }
}
