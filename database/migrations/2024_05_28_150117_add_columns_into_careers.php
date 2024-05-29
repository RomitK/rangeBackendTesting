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
        Schema::table('careers', function (Blueprint $table) {

            if (!Schema::hasColumn('careers', 'approval_id')) {
                $table->unsignedBigInteger('approval_id')->nullable();
                $table->foreign('approval_id')->references('id')->on('users')->onDelete('cascade');
            }
            if (!Schema::hasColumn('careers', 'is_approved')) {
                $table->string('is_approved')->default('requested');
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
        Schema::table('careers', function (Blueprint $table) {
            //
        });
    }
};
