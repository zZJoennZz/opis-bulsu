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
        Schema::create('inspection_and_acceptances', function (Blueprint $table) {
            $table->id();
            $table->string('iar_no')->nullable();
            $table->unsignedBigInteger('purchase_orders_id');
            $table->date('iar_date');
            $table->string('responsibility_center_code')->nullable();
            $table->boolean('is_draft')->default(1);
            $table->boolean('is_delete')->default(0);
            $table->unsignedBigInteger('added_by');
            $table->timestamps();

            $table->foreign('purchase_orders_id')->references('id')->on('purchase_orders');;
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
        Schema::dropIfExists('inspection_and_acceptances');
    }
};
