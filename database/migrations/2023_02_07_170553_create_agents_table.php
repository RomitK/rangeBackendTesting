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
        if (!Schema::hasTable('agents')) {
        Schema::create('agents', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug');
            $table->string('email')->nullable();
            $table->string('contact_number')->nullable();
            $table->boolean('is_display_home')->default(0);
            $table->string('whatsapp_number')->nullable();
            $table->string('designation')->nullable();
            $table->string('specialization')->nullable();
            $table->string('nationality')->nullable();
            $table->string('experience')->nullable();
            $table->string('start_working')->nullable();
            $table->string('linkedin_profile')->nullable();
            $table->string('license_number')->nullable();
            $table->string('status')->default('active');
            $table->longText('meta_title')->nullable();
            $table->longText('meta_keywords')->nullable();
            $table->longText('meta_description')->nullable();
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
        Schema::dropIfExists('agents');
    }
};
