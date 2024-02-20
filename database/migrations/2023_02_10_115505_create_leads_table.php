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
        if (!Schema::hasTable('leads')) {
            Schema::create('leads', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email');
                $table->string('phone');
                $table->string('form_name')->nullable();
                $table->longText('detail')->nullable();
                $table->longText('message')->nullable();
                $table->longText('property_url')->nullable();
                $table->string('page_url')->nullable();
                $table->timestamp('submit_date')->nullable();
                $table->string('booking_date')->nullable();
                $table->string('booking_time')->nullable();
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
        Schema::dropIfExists('leads');
    }
};
