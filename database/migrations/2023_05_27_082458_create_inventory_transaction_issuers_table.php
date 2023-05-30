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
        Schema::create('inventory_transaction_issuers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('inventory_transactions_id');
            $table->unsignedBigInteger('supply_office_employees_id');
            $table->timestamps();

            $table->foreign('inventory_transactions_id')->references('id')->on('inventory_transactions');
            $table->foreign('supply_office_employees_id')->references('id')->on('supply_office_employees');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inventory_transaction_issuers');
    }
};
