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
        Schema::create('canvass_abstracts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('companies_id');
            $table->string('year');
            $table->decimal('abc');
            $table->unsignedBigInteger('added_by');
            $table->boolean('is_delete')->default(0);
            $table->timestamps();

            $table->foreign('companies_id')->references('id')->on('companies');
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
        Schema::dropIfExists('canvass_abstracts');
    }
};
