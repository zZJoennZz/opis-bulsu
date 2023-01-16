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
        Schema::create('pro_pro_man_plans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('item_details_id');
            $table->text('year');
            $table->unsignedBigInteger('branches_id');
            $table->boolean('is_draft')->default(1);
            $table->boolean('is_bo_approve')->default(0);
            $table->boolean('is_pr_approve')->default(0);
            $table->boolean('is_consolidate')->default(0);
            $table->unsignedBigInteger('source_of_funds_id');
            $table->unsignedBigInteger('item_purposes_id');
            $table->double('estimated_budget');
            $table->integer('is_priority');
            $table->boolean('is_delete')->default(false);
            $table->longText('remarks')->nullable();
            $table->unsignedBigInteger('submitted_by');
            $table->timestamps();

            $table->foreign('item_details_id')->references('id')->on('item_details');
            $table->foreign('branches_id')->references('id')->on('branches');
            $table->foreign('source_of_funds_id')->references('id')->on('source_of_funds');
            $table->foreign('item_purposes_id')->references('id')->on('item_purposes');
            $table->foreign('submitted_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pro_pro_man_plans');
    }
};
