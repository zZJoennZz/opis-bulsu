<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryCustodian extends Model
{
    use HasFactory;
    protected $fillable = [
        'ics_number_year',
        'ics_number_month',
        'ics_number_series',
        'date_acquired',
        'inspection_and_acceptances_id',
        'canvass_abstract_items_id',
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
    ];
}
