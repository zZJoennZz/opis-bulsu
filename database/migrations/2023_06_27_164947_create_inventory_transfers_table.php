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
        Schema::create('inventory_transfers', function (Blueprint $table) {
            $table->id();
            $table->string('number');
            $table->string('reason');
            $table->integer('quantity');
            $table->unsignedBigInteger('inventory_transaction_items_id');
            $table->unsignedBigInteger('added_by');
            $table->timestamps();

            $table->foreign('inventory_transaction_items_id')->references('id')->on('inventory_transaction_items');
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
        Schema::dropIfExists('inventory_transfers');
    }
};
