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
            if (!Schema::hasColumn('properties', 'CommunityID')) {
                $table->string('CommunityID')->nullable()->after('community_id');
            }
            if (!Schema::hasColumn('properties', 'SubCommunityID')) {
                $table->string('SubCommunityID')->nullable()->after('community_id');
            }
            if (!Schema::hasColumn('properties', 'PropertyID')) {
                $table->string('PropertyID')->nullable()->after('community_id');
            }
            if (!Schema::hasColumn('properties', 'UnitID')) {
                $table->string('UnitID')->nullable()->after('community_id');
            }
            if (!Schema::hasColumn('properties', 'ReferredToID')) {
                $table->string('ReferredToID')->nullable()->after('community_id');
            }
            if (!Schema::hasColumn('properties', 'UnitType')) {
                $table->string('UnitType')->nullable()->after('community_id');
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
