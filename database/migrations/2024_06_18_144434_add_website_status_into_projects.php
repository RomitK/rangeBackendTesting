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
            if (!Schema::hasColumn('projects', 'website_status')) {
                $table->string('website_status')->default('available');
            }
        });

        // Update the website_status column using Laravel's query builder
        DB::table('projects')->update([
            'website_status' => DB::raw("CASE 
                    WHEN status = 'active' AND is_approved = 'approved' THEN 'available'
                    WHEN status = 'Inactive' AND is_approved = 'approved' THEN 'NA'
                    WHEN is_approved = 'requested' THEN 'requested'
                    WHEN is_approved = 'rejected' THEN 'rejected'
                    ELSE website_status
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
