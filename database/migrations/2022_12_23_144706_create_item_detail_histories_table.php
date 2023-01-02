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
        Schema::create('item_detail_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('item_details_id');
            $table->unsignedBigInteger('action_by');
            $table->longText('before_change');
            $table->longText('after_change');
            $table->longText('changes');
            $table->boolean('is_approve')->default(0);
            $table->longText('remarks')->nullable();
            $table->timestamps();

            $table->foreign('item_details_id')->references('id')->on('item_details');
            $table->foreign('action_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('item_detail_histories');
    }
};
