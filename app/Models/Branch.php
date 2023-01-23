<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;
    protected $fillable = [
        'branch_name', 'type', 'address', 'email_address', 'contact_number', 'added_by', 'is_delete'
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
}
