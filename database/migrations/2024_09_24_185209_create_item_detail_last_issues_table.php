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
        Schema::create('item_detail_last_issues', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('item_details_id');
            $table->double('issued_qty');
            $table->timestamps();

            $table->foreign('item_details_id')->references('id')->on('item_details');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('item_detail_last_issues');
    }
};
