<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

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
}
