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
        Schema::table('sub_floor_plans', function (Blueprint $table) {
            if (!Schema::hasColumn('sub_floor_plans', 'accommodation_id')) {
            $table->unsignedBigInteger('accommodation_id')->nullable();
            $table->foreign('accommodation_id')->references('id')->on('accommodations')->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sub_floor_plans', function (Blueprint $table) {
            //
        });
    }
};
