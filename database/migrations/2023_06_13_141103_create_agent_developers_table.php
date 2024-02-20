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
        if (!Schema::hasTable('agent_developers')) {
            Schema::create('agent_developers', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('agent_id');
                $table->foreign('agent_id')->references('id')->on('agents');
                $table->unsignedBigInteger('developer_id');
                $table->foreign('developer_id')->references('id')->on('developers');
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
        Schema::dropIfExists('agent_developers');
    }
};
