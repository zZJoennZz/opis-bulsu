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
        Schema::create('inventory_transaction_item_property_current_keepers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('inventory_transaction_item_properties_id');
            $table->unsignedBigInteger('supply_end_users_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inventory_transaction_item_property_current_keepers');
    }
};
