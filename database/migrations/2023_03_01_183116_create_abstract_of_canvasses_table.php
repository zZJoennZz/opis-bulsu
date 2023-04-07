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
        Schema::create('abstract_of_canvasses', function (Blueprint $table) {
            $table->id();
            $table->string('year');
            $table->string('type');
            $table->string('abc');
            $table->unsignedBigInteger('purchase_requests_id');
            $table->string('bac_chairman');
            $table->string('vice_chairman');
            $table->string('member_1');
            $table->string('member_2');
            $table->string('member_3');
            $table->string('member_4');
            $table->string('technical_resource_person');
            $table->string('end_user');
            $table->string('president');
            $table->string('procurement_office_rep');
            $table->unsignedBigInteger('added_by');
            $table->boolean('is_delete')->default(0);
            $table->timestamps();

            $table->foreign('purchase_requests_id')->references('id')->on('purchase_requests');
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
        Schema::dropIfExists('abstract_of_canvasses');
    }
};
