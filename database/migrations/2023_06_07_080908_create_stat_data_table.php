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
        if (!Schema::hasTable('website_settings')) {
            Schema::create('stat_data', function (Blueprint $table) {
                $table->id();
                $table->string('key')->nullable();
                $table->string('value')->nullable()->nullable();
                $table->unsignedBigInteger('stat_id');
                $table->foreign('stat_id')->references('id')->on('stats')->onDelete('cascade');
                $table->softDeletes();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stat_data');
    }
};
