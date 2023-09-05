<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EndUserReportRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'date_generated',
        'supply_end_users_id',
        'report_snap_shots_id',
        'added_by',
    ];

    public function snapshot()
    {
        return $this->hasOne(ReportSnapShot::class, 'id', 'report_snap_shots_id');
    }

    public function added_by_user()
    {
        return $this->hasOne(User::class, 'id', 'added_by');
    }
}
