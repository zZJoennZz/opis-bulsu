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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->text('title');
            $table->text('message');
            $table->text('url')->nullable();
            $table->boolean('is_read')->default(false);
            $table->unsignedBigInteger('sent_to');
            $table->unsignedBigInteger('sent_by');
            $table->timestamps();

            $table->foreign('sent_to')->references('id')->on('users');
            $table->foreign('sent_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notifications');
    }
};
