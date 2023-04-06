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
        Schema::create('b_a_c_reso_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('b_a_c_resos_id');
            $table->unsignedBigInteger('quotation_items_id');
            $table->boolean('is_select')->default(0);
            $table->string('note')->nullable();
            $table->timestamps();

            $table->foreign('b_a_c_resos_id')->references('id')->on('b_a_c_resos');
            $table->foreign('quotation_items_id')->references('id')->on('quotation_items');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('b_a_c_reso_items');
    }
};
