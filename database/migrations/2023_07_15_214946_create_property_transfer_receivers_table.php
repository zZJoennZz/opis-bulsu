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
        Schema::create('property_transfer_receivers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('property_transfers_id');
            $table->unsignedBigInteger('supply_end_users_id');
            $table->timestamps();

            $table->foreign('property_transfers_id')->references('id')->on('property_transfers');
            $table->foreign('supply_end_users_id')->references('id')->on('supply_end_users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('property_transfer_receivers');
    }
};
