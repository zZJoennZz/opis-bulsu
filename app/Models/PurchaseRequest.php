<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseRequest extends Model
{
    use HasFactory;
    protected $fillable = [
        'pr_number',
        'year',
        'is_draft',
        'is_approve',
        'is_delete',
        'branches_id',
        'requested_by'
    ];

    public function branch()
    {
        return $this->hasOne(Branch::class, 'id', 'branches_id');
    }

    public function requester()
    {
        return $this->hasOne(User::class, 'id', 'requested_by');
    }

    public function pr_items()
    {
        return $this->hasMany(PurchaseRequestItem::class, 'purchase_requests_id', 'id');
    }
}
