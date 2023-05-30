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
        Schema::create('inventory_transaction_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('inventory_transactions_id');
            $table->unsignedBigInteger('b_a_c_reso_items_id');
            $table->integer('quantity');
            $table->unsignedBigInteger('equipment_codes_id');
            $table->string('property_number');
            $table->timestamps();

            $table->foreign('inventory_transactions_id')->references('id')->on('inventory_transactions');
            $table->foreign('b_a_c_reso_items_id')->references('id')->on('b_a_c_reso_items');
            $table->foreign('equipment_codes_id')->references('id')->on('equipment_codes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inventory_transaction_items');
    }
};
