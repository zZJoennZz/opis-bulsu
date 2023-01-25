<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'full_address',
        'tin',
        'contact_number',
        'email_address',
        'is_delete',
        'added_by'
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'added_by');
    }

    public function quotations()
    {
        return $this->hasMany(Quotation::class, 'companies_id', 'id');
    }
}
