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
            $table->text('ppmp_year');
            $table->unsignedBigInteger('branches_id');
            $table->boolean('is_draft');
            $table->boolean('is_bo_approve');
            $table->boolean('is_pr_approve');
            $table->unsignedBigInteger('source_of_funds_id');
            $table->unsignedBigInteger('item_purposes_id');
            $table->decimal('estimated_budget');
            $table->integer('is_priority');
            $table->boolean('is_delete');
            $table->longText('remarks')->nullable();
            $table->unsignedBigInteger('submitted_by');
            $table->timestamps();

            $table->foreign('item_details_id')->references('item_details')->on('id');
            $table->foreign('branch_id')->references('item_details')->on('id');
            $table->foreign('source_of_funds_id')->references('source_of_funds')->on('id');
            $table->foreign('item_purposes_id')->references('item_purposes')->on('id');
            $table->foreign('submitted_by')->references('users')->on('id');
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
