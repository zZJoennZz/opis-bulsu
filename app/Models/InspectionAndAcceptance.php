<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InspectionAndAcceptance extends Model
{
    use HasFactory;
    protected $fillable = [
        'iar_no',
        'purchase_orders_id',
        'branches_id',
        'iar_date',
        'responsibility_center_code',
        'is_delete',
        'added_by',
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'added_by');
    }

    public function branch()
    {
        return $this->hasOne(Branch::class, 'id', 'branches_id');
    }

    public function purchase_order()
    {
        return $this->hasOne(PurchaseOrder::class, 'id', 'purchase_orders_id');
    }
}
