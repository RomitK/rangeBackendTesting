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

        if (Schema::hasTable('careers')) {
            Schema::table('careers', function (Blueprint $table) {
                if (!Schema::hasColumn('careers', 'meta_title')) {
                    $table->longText('meta_title')->nullable();
                }
                if (!Schema::hasColumn('careers', 'meta_keywords')) {
                    $table->longText('meta_keywords')->nullable();
                }
                if (!Schema::hasColumn('careers', 'meta_description')) {
                    $table->longText('meta_description')->nullable();
                }
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
        Schema::table('careers', function (Blueprint $table) {
            //
        });
    }
};
