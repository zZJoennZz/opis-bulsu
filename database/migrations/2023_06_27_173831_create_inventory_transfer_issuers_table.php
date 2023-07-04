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
        Schema::create('inventory_transfer_issuers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('inventory_transfers_id');
            $table->unsignedBigInteger('supply_end_users_id');
            $table->timestamps();

            $table->foreign('inventory_transfers_id')->references('id')->on('inventory_transfers');
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
        Schema::dropIfExists('inventory_transfer_issuers');
    }
};
