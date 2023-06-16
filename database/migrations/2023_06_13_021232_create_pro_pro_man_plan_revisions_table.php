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
        Schema::create('pro_pro_man_plan_revisions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pro_pro_man_plans_id');
            $table->string('type');
            $table->bigInteger('item_details_id')->default(0);
            $table->timestamps();

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
        Schema::dropIfExists('pro_pro_man_plan_revisions');
    }
};
