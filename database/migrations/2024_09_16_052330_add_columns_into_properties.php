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
        Schema::table('properties', function (Blueprint $table) {
            if (!Schema::hasColumn('properties', 'CountryID')) {
                $table->string('CountryID')->nullable()->after('address');
            }
            if (!Schema::hasColumn('properties', 'StateID')) {
                $table->string('StateID')->nullable()->after('address');
            }
            if (!Schema::hasColumn('properties', 'CityID')) {
                $table->string('CityID')->nullable()->after('address');
            }
            if (!Schema::hasColumn('properties', 'DistrictID')) {
                $table->string('DistrictID')->nullable()->after('address');
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
        Schema::table('properties', function (Blueprint $table) {
            //
        });
    }
};
