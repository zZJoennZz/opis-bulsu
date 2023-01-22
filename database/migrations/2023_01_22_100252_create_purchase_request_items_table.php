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
        Schema::create('purchase_request_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('purchase_requests_id');
            $table->unsignedBigInteger('pro_pro_man_plans_id');
            $table->timestamps();

            $table->foreign('purchase_requests_id')->references('id')->on('purchase_requests');
            $table->foreign('pro_pro_man_plans_id')->references('id')->on('pro_pro_man_plans');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchase_request_items');
    }
};
