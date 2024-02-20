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
        Schema::create('sub_floor_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('area')->nullable();
            $table->unsignedBigInteger('floor_plan_id');
            $table->foreign('floor_plan_id')->references('id')->on('floor_plans');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sub_floor_plans');
    }
};
