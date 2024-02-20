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
        if (!Schema::hasTable('subcommunities')) {

       
            Schema::create('subcommunities', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->unsignedBigInteger('community_id')->nullable();
                $table->foreign('community_id')->references('id')->on('communities');
                $table->string('status')->default('active');
                $table->unsignedBigInteger('user_id');
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('subcommunities');
    }
};
