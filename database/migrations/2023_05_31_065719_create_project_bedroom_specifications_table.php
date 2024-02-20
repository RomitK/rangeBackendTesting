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
        if (!Schema::hasTable('project_bedroom_specifications')) {
           
    
        Schema::create('project_bedroom_specifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_bedroom_id');
            $table->foreign('project_bedroom_id')->references('id')->on('project_bedrooms')->onDelete('cascade');
            $table->string('key');
            $table->string('value')->nullable();
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
        Schema::dropIfExists('project_bedroom_specifications');
    }
};
