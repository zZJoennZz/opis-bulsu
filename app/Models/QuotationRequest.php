<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuotationRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_requests_id',
        'quotation_number',
        'deadline_of_submission',
        'mode_of_procurements_id',
        'approved_budget',
        'bidder_company_bank_name',
        'bidder_bank_account_number',
        'bidder_tax_id_number',
        'bidder_contact_number',
        'bidder_email_address',
        'bidder_delivery_period',
        'bidder_representative',
        'bidder_sign_date',
        'date_of_canvass',
        'buyer_name',
        'head_procurement',
    ];
}
