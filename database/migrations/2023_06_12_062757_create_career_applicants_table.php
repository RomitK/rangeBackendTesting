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
        if (!Schema::hasTable('career_applicants')) {
            Schema::create('career_applicants', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('career_id')->nullable();
            $table->foreign('career_id')->references('id')->on('careers')->onDelete('cascade');
            $table->string('name');
            $table->string('email');
            $table->string('contact_number');
            $table->string('page_url')->nullable();
            $table->timestamp('submit_date')->nullable();
            $table->string('status')->default('active');
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
        Schema::dropIfExists('career_applicants');
    }
};
