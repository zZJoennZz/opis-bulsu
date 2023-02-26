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
        Schema::create('quotation_items', function (Blueprint $table) {
            $table->id();
            $table->string('item_number')->default('n/a');
            $table->unsignedBigInteger('quotations_id');
            // $table->unsignedBigInteger('pro_pro_man_plans_id'); //will be using pr item instead of ppmp
            $table->unsignedBigInteger('purchase_request_items_id');
            $table->string('brand_and_model_offered');
            $table->decimal('offered_unit_price');
            $table->timestamps();

            $table->foreign('quotations_id')->references('id')->on('quotations');
            // $table->foreign('pro_pro_man_plans_id')->references('id')->on('pro_pro_man_plans'); //will be using pr item instead of ppmp
            $table->foreign('purchase_request_items_id')->references('id')->on('purchase_request_items');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('quotation_items');
    }
};
