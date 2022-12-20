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
        Schema::create('item_details', function (Blueprint $table) {
            $table->id();
            $table->text("description");
            $table->text("article");
            $table->decimal("price_catalogue");
            $table->unsignedBigInteger("category_id");
            $table->unsignedBigInteger("unit_id");
            $table->unsignedBigInteger("added_by");
            $table->timestamps();

            $table->foreign("category_id")->references("id")->on("item_categories");
            $table->foreign("unit_id")->references("id")->on("units");
            $table->foreign("added_by")->references("id")->on("users");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('item_details');
    }
};
