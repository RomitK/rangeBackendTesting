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
        if (!Schema::hasTable('page_tags')) {
            Schema::create('page_tags', function (Blueprint $table) {
                $table->id();
                $table->string('page_name');
                $table->longText('page_url');
                $table->longText('meta_title')->nullable();
                $table->longText('meta_keywords')->nullable();
                $table->longText('meta_description')->nullable();
                $table->longText('meta_author')->nullable();
                $table->longText('meta_viewport')->nullable();
                $table->string('status')->default('active');
                $table->unsignedBigInteger('user_id');
                $table->foreign('user_id')->references('id')->on('users');
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
        Schema::dropIfExists('page_tags');
    }
};
