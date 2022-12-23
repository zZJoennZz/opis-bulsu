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
        Schema::create('pro_pro_man_plan_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pro_pro_man_plans_id');
            $table->longText('before_state');
            $table->longText('after_state');
            $table->longText('remarks')->nullable();
            $table->boolean('is_confirm')->default(0);
            $table->longText('changes_summary');
            $table->unsignedBigInteger('record_by');
            $table->timestamps();

            $table->foreign('record_by')->references('id')->on('users');
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
        Schema::dropIfExists('pro_pro_man_plan_histories');
    }
};
