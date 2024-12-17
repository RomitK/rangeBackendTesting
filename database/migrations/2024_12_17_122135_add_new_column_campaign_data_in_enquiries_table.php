<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('enquiries', function (Blueprint $table) {
            $table->string('group_id')->nullable()->after('campaign_id');
            $table->string('campaign_name')->nullable()->after('group_id');
            $table->string('group_name')->nullable()->after('campaign_name');
        });
    }

    public function down(): void
    {
        Schema::table('enquiries', function (Blueprint $table) {
            $table->dropColumn(['group_id', 'campaign_name', 'group_name']);
        });
    }
};
