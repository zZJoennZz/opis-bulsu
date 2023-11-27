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
        Schema::create('inventory_transaction_item_properties', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('inventory_transaction_items_id');
            $table->string('serial_number')->nullable();
            $table->string('property_condition')->nullable()->default('Brand New');
            $table->string('accumulated_depreciation')->nullable();
            $table->string('accumulated_impairment_losses')->nullable();
            $table->string('carrying_amount')->nullable();
            $table->string('disposal')->nullable();
            $table->string('appraised_value')->nullable();
            $table->string('or_number')->nullable();
            $table->string('amount')->nullable();
            $table->boolean('is_available')->default(true);
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
        Schema::dropIfExists('inventory_transaction_item_properties');
    }
};
