<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    use HasFactory;
    protected $fillable = ['users_id', 'first_name', 'last_name', 'positions_id'];


    public function position()
    {
        return $this->hasOne(Position::class, 'id', 'positions_id');
    }
}
