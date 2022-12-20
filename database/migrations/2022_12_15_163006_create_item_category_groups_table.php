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
        Schema::create('item_category_groups', function (Blueprint $table) {
            $table->id();
            $table->text('title');
            $table->text('report_sub_total_footer');
            $table->integer('order');
            $table->unsignedBigInteger('under_of_section');
            $table->timestamps();

            $table->foreign('under_of_section')->references('id')->on('item_category_group_sections');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('item_category_groups');
    }
};
