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
        Schema::table('page_tags', function (Blueprint $table) {

            if (!Schema::hasColumn('page_tags', 'approval_id')) {
                $table->unsignedBigInteger('approval_id')->nullable();
                $table->foreign('approval_id')->references('id')->on('users')->onDelete('cascade');
            }
            if (!Schema::hasColumn('page_tags', 'updated_by')) {
                $table->unsignedBigInteger('updated_by')->nullable();
                $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');
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
        Schema::table('page_tags', function (Blueprint $table) {
            //
        });
    }
};
