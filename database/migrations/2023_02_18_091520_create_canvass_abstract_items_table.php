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
        Schema::create('canvass_abstract_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('canvass_abstracts_id');
            $table->unsignedBigInteger('quotation_items_id');
            $table->timestamps();

            $table->foreign('canvass_abstracts_id')->references('id')->on('canvass_abstracts');
            $table->foreign('quotation_items_id')->references('id')->on('quotation_items');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('canvass_abstract_items');
    }
};
