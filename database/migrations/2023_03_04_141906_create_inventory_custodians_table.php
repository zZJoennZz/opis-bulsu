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
        Schema::create('inventory_custodians', function (Blueprint $table) {
            $table->id();
            $table->string('ics_number_year')->nullable();
            $table->string('ics_number_month')->nullable();
            $table->string('ics_number_series')->nullable();
            $table->date('date_acquired');
            $table->unsignedBigInteger('inspection_and_acceptances_id');
            $table->unsignedBigInteger('abstract_of_canvasses_id');
            $table->string('serial_number');
            $table->unsignedBigInteger('received_from');
            $table->unsignedBigInteger('received_by');
            $table->date('date_issued');
            $table->unsignedBigInteger('delivered_by');
            $table->unsignedBigInteger('source_of_funds_id');
            $table->string('po_number_year')->nullable();
            $table->string('po_number_month')->nullable();
            $table->string('po_number_series')->nullable();
            $table->string('fund_cluster_year')->nullable();
            $table->string('fund_cluster_month')->nullable();
            $table->string('fund_cluster_series')->nullable();
            $table->timestamps();

            $table->foreign('inspection_and_acceptances_id')->references('id')->on('inspection_and_acceptances');
            $table->foreign('abstract_of_canvasses_id')->references('id')->on('abstract_of_canvasses');
            $table->foreign('received_from')->references('id')->on('supply_office_employees');
            $table->foreign('received_by')->references('id')->on('supply_end_users');
            $table->foreign('delivered_by')->references('id')->on('companies');
            $table->foreign('source_of_funds_id')->references('id')->on('source_of_funds');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inventory_custodians');
    }
};
