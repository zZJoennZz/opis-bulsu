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
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('b_a_c_resos_id');
            $table->unsignedBigInteger('companies_id');
            $table->string('year');
            $table->string('po_number');
            $table->unsignedBigInteger('purchase_order_mode_of_payments_id');
            $table->unsignedBigInteger('mode_of_procurements_id');
            $table->string('notes')->default('Please furnish this Office the following articles subject to the terms and conditions herein:');
            $table->string('place_of_delivery');
            $table->string('date_of_delivery');
            $table->string('for_inquiry');
            $table->string('delivery_term');
            $table->boolean('is_delete')->default(0);
            $table->unsignedBigInteger('added_by');
            $table->timestamps();

            $table->foreign('b_a_c_resos_id')->references('id')->on('b_a_c_resos');
            $table->foreign('purchase_order_mode_of_payments_id')->references('id')->on('purchase_order_mode_of_payments');
            $table->foreign('mode_of_procurements_id')->references('id')->on('mode_of_procurements');
            $table->foreign('companies_id')->references('id')->on('companies');
            $table->foreign('added_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchase_orders');
    }
};
