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
        Schema::table('floor_plans', function (Blueprint $table) {
            if (!Schema::hasColumn('floor_plans', 'sub_community_id')) {
                $table->unsignedBigInteger('sub_community_id')->nullable();
                $table->foreign('sub_community_id')->references('id')->on('subcommunities');
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
        Schema::table('floor_plans', function (Blueprint $table) {
            //
        });
    }
};
