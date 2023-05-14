<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AllotAndOblSlip extends Model
{
    use HasFactory;
    protected $fillable = [
        'purchase_orders_id',
        'budget_officer_id',
        'is_draft',
        'is_delete',
        'added_by',
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'added_by');
    }

    public function purchase_order()
    {
        return $this->hasOne(PurchaseOrder::class, 'id', 'purchase_orders_id');
    }

    public function budget_office()
    {
        return $this->hasOne(User::class, 'id', 'budget_officer_id');
    }
}
