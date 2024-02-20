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
        Schema::table('banners', function (Blueprint $table) {
            if (Schema::hasColumn('banners', 'page_url')) {
                $table->dropColumn('page_url');
            }
            if (Schema::hasColumn('banners', 'heading_one')) {
                $table->dropColumn('heading_one');
            }
            $table->string('page_name')->nullable();
            $table->string('title')->nullable();
            $table->string('button_text')->nullable();
            $table->longText('button_link')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('banners', function (Blueprint $table) {
            //
        });
    }
};
