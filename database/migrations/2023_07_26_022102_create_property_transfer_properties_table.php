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
        Schema::create('property_transfer_properties', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('property_transfers_id');
            $table->unsignedBigInteger('inventory_transaction_item_properties_id');
            $table->timestamps();

            $table->foreign('property_transfers_id')->references('id')->on('property_transfers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('property_transfer_properties');
    }
};
