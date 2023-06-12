<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;
    protected $fillable = [
        'branch_name',
        'type', 'address',
        'email_address',
        'contact_number',
        'office_code',
        'is_delete',
        'added_by',
    ];

    public function ppmp()
    {
        return $this->hasMany(ProProManPlan::class, 'branches_id', 'id');
    }

    public function pr_record()
    {
        return $this->hasMany(PurchaseRequest::class, 'branches_id', 'id');
    }

    public function pr_mode()
    {
        return $this->hasMany(PurchaseRequestMode::class, 'branches_id', 'id');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'branches_id', 'id');
    }
}
