<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryCustodian extends Model
{
    use HasFactory;
    protected $fillable = [
        'ics_number',
        'date_acquired',
        'inspection_and_acceptances_id',
        'b_a_c_reso_items_id',
        'serial_number',
        'received_from',
        'received_by',
        'date_issued',
        'delivered_by',
        'source_of_funds_id',
        'po_number_year',
        'po_number_month',
        'po_number_series',
        'fund_cluster_year',
        'fund_cluster_month',
        'fund_cluster_series',
        'added_by',
    ];

    public function bac_reso()
    {
        return $this->hasOne(BACResoItem::class, 'id', 'b_a_c_reso_items_id');
    }

    public function received_from_user()
    {
        return $this->hasOne(SupplyOfficeEmployee::class, 'id', 'received_from');
    }

    public function received_by_user()
    {
        return $this->hasOne(SupplyEndUser::class, 'id', 'received_by');
    }

    public function iar()
    {
        return $this->hasOne(InspectionAndAcceptance::class, 'id', 'inspection_and_acceptances_id');
    }

    public function company()
    {
        return $this->hasOne(Company::class, 'id', 'delivered_by');
    }

    public function source_of_fund()
    {
        return $this->hasOne(SourceOfFund::class, 'id', 'source_of_funds_id');
    }

    public function added_by_user()
    {
        return $this->hasOne(User::class, 'id', 'added_by');
    }
}
