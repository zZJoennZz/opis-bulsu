<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quotation_requests', function (Blueprint $table) {
            $table->id();
            $table->text('year');
            $table->unsignedBigInteger('purchase_requests_id');
            $table->text('quotation_number');
            $table->date('deadline_of_submission');
            $table->unsignedBigInteger('mode_of_procurements_id');
            $table->double('approved_budget');
            $table->text('bidder_company_bank_name')->nullable();
            $table->text('bidder_bank_account_number')->nullable();
            $table->text('bidder_tax_id_number')->nullable();
            $table->text('bidder_contact_number')->nullable();
            $table->text('bidder_email_address')->nullable();
            $table->text('bidder_delivery_period')->nullable();
            $table->text('bidder_representative')->nullable();
            $table->date('bidder_sign_date')->nullable();
            $table->date('date_of_canvass')->nullable();
            $table->text('buyer_name')->nullable();
            $table->text('head_procurement')->nullable();
            $table->timestamps();

            $table->foreign('purchase_requests_id')->references('id')->on('purchase_requests');
            $table->foreign('mode_of_procurements_id')->references('id')->on('mode_of_procurements');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('quotation_requests');
    }
};
