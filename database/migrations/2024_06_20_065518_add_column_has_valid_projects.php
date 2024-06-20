<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('projects', function (Blueprint $table) {
            if (!Schema::hasColumn('projects', 'is_valid')) {
                $table->boolean('is_valid')->default(0);
            }
        });

        DB::table('projects')->update([
            'is_valid' => DB::raw("CASE
                WHEN permit_number IS NOT NULL AND permit_number != '' AND qr_link IS NOT NULL AND qr_link != '' THEN 1
                ELSE 0
            END")
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('projects', function (Blueprint $table) {
            //
        });
    }
};
