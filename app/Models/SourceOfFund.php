<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SourceOfFund extends Model
{
    use HasFactory;
    protected $fillable = ['source_of_fund', 'added_by', 'is_delete', 'description'];
}
