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
        if (!Schema::hasTable('tags')) {

            Schema::create('tags', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('tag_category_id');
                $table->foreign('tag_category_id')->references('id')->on('tag_categories')->onDelete('cascade');
                $table->unsignedBigInteger('tagable_id');
                $table->string('tagable_type');
                $table->timestamps();
                $table->softDeletes();
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
        Schema::dropIfExists('tags');
    }
};
