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
        Schema::create('milestone_of_activities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pro_pro_man_plans_id');
            $table->unsignedBigInteger('milestone_formats_id');
            $table->text('milestone_value_id');
            $table->integer('milestone_value');
            $table->timestamps();

            $table->foreign('pro_pro_man_plans_id')->references('id')->on('pro_pro_man_plans');
            $table->foreign('milestone_formats_id')->references('id')->on('milestone_formats');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('milestone_of_activities');
    }
};
