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
        Schema::create('item_categories', function (Blueprint $table) {
            $table->id();
            $table->text('description');
            $table->unsignedBigInteger('under_of_group')->nullable();
            $table->unsignedBigInteger('added_by');
            $table->boolean('is_delete')->default(false);
            $table->timestamps();

            $table->foreign('under_of_group')->references('id')->on('item_category_groups');
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
        Schema::dropIfExists('item_categories');
    }
};
