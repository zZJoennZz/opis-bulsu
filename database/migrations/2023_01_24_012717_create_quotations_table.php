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
        Schema::create('quotations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('companies_id');
            $table->string('quotation_number');
            $table->unsignedBigInteger('pro_pro_man_plans_id');
            $table->string('purpose'); //not sure if this is really needed.
            $table->timestamps();

            $table->foreign('companies_id')->references('id')->on('companies');
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
        Schema::dropIfExists('quotations');
    }
};
