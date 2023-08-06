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
        Schema::create('property_transfers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('inventory_transaction_items_id');
            $table->string('number');
            $table->integer('quantity');
            $table->longText('reason')->nullable();
            $table->string('type')->default('OTHERS');
            $table->string('other_type')->nullable();
            $table->unsignedBigInteger('added_by');
            $table->timestamps();

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
        Schema::dropIfExists('property_transfers');
    }
};
