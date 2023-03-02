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
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('canvass_abstracts_id');
            $table->string('po_number');
            $table->unsignedBigInteger('purchase_order_mode_of_payments_id');
            $table->boolean('is_delete')->default(0);
            $table->unsignedBigInteger('added_by');
            $table->timestamps();

            $table->foreign('canvass_abstracts_id')->references('id')->on('canvass_abstracts');
            $table->foreign('purchase_order_mode_of_payments_id')->references('id')->on('purchase_order_mode_of_payments');
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
        Schema::dropIfExists('purchase_orders');
    }
};
