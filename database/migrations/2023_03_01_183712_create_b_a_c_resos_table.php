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
        Schema::create('b_a_c_resos', function (Blueprint $table) {
            $table->id();
            $table->string('b_a_c_reso_number');
            $table->unsignedBigInteger('abstract_of_canvasses_id');
            $table->string('header_remarks')->nullable();
            $table->string('rfq_reference_numbers')->nullable();
            $table->date('rfq_date')->nullable();
            $table->string('opening_quotation_location')->nullable();
            $table->date('opening_quotation_date')->nullable();
            $table->boolean('is_draft')->default(1);
            $table->boolean('is_delete')->default(0);
            $table->unsignedBigInteger('added_by');
            $table->timestamps();

            $table->foreign('abstract_of_canvasses_id')->references('id')->on('abstract_of_canvasses');
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
        Schema::dropIfExists('b_a_c_resos');
    }
};
