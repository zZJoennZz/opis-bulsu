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
        Schema::create('purchase_requests', function (Blueprint $table) {
            $table->id();
            $table->string('pr_number');
            $table->string('year');
            $table->string('purpose')->nullable();
            $table->boolean('is_draft')->default(1); //tbh, this might not be needed but I'll still leave this on because users might want to save their record but not ready to submit yet
            $table->boolean('is_approve')->default(0);
            $table->boolean('is_delete')->default(0);
            $table->unsignedBigInteger('branches_id');
            $table->unsignedBigInteger('requested_by');
            $table->timestamps();

            $table->foreign('branches_id')->references('id')->on('branches');
            $table->foreign('requested_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchase_requests');
    }
};
