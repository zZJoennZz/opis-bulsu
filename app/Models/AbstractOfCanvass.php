<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AbstractOfCanvass extends Model
{
    use HasFactory;
    protected $fillable = [
        'purpose',
        'abc',
        'purchase_requests_id',
        'bac_chairman',
        'vice_chairman',
        'member_1',
        'member_2',
        'member_3',
        'member_4',
        'added_by',
        'is_delete',
    ];

    public function pr()
    {
        return $this->hasOne(PurchaseRequest::class, 'id', 'purchase_requests_id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'added_by');
    }

    public function bac_reso()
    {
        return $this->belongsTo(BACReso::class, 'id', 'abstract_of_canvasses_id');
    }
}
