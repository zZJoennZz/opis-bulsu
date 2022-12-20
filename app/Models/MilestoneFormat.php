<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MilestoneFormat extends Model
{
    use HasFactory;

    protected $fillable = ['format_name', 'format'];
}
