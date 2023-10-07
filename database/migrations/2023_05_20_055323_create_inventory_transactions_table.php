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
        Schema::create('inventory_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->unsignedBigInteger('branches_id');
            $table->string('number');
            $table->date('date_acquired');
            $table->unsignedBigInteger('purchase_orders_id');
            $table->date('date_issued');
            $table->longText('file_attachments_id')->nullable();
            $table->boolean('is_delete')->default(0);
            $table->unsignedBigInteger('added_by');
            $table->timestamps();

            $table->foreign('branches_id')->references('id')->on('branches');
            $table->foreign('purchase_orders_id')->references('id')->on('purchase_orders');
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
        Schema::dropIfExists('inventory_transactions');
    }
};
