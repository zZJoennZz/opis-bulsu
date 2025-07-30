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
        Schema::create('item_forecastings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("item_details_id");
            $table->string("alpha");
            $table->string("group");
            $table->date("from_date");
            $table->date("to_date");
            $table->longText("data");
            $table->timestamps();

            $table->foreign("item_details_id")->references("id")->on("item_details");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('item_forecastings');
    }
};
