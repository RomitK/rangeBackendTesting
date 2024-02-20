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
        if (!Schema::hasTable('projects')) {
    
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->longtext('slug');
            $table->string('sub_title')->nullable();
            $table->boolean('is_parent_project')->default(0);
            $table->boolean('is_new_launch')->default(0);
            $table->boolean('is_featured')->default(0);
            $table->string('rating')->nullable();
            $table->boolean('is_display_home')->default(0);
            $table->string('projects')->nullable();
            $table->longText('address')->nullable();
            $table->string('address_longitude')->nullable();
            $table->string('address_latitude')->nullable();
            $table->string('starting_date')->nullable();
            $table->longText('starting_price')->nullable();
            $table->boolean('starting_price_highlight')->default(0);
            $table->string('completion_date')->nullable();
            $table->boolean('completion_date_highlight')->default(0);
            $table->string('emirate')->nullable();
            $table->string('parking_space')->nullable();
            $table->string('price')->nullable();
            $table->string('area')->nullable();
            $table->boolean('area_highlight')->default(0);
            $table->string('area_unit')->nullable();
            $table->string('bedrooms')->nullable();
            $table->string('bathrooms')->nullable();
            $table->string('status')->default('active');
            $table->unsignedBigInteger('developer_id')->nullable();
            $table->foreign('developer_id')->references('id')->on('developers');
            $table->unsignedBigInteger('agent_id')->nullable();
            $table->foreign('agent_id')->references('id')->on('agents');
            $table->unsignedBigInteger('community_id')->nullable();
            $table->foreign('community_id')->references('id')->on('communities');
            $table->boolean('community_id_highlight')->default(0);
            $table->unsignedBigInteger('sub_community_id')->nullable();
            $table->foreign('sub_community_id')->references('id')->on('subcommunities');
            $table->boolean('accommodation_id_highlight')->default(0);
            $table->unsignedBigInteger('parent_project_id')->nullable();
            $table->foreign('parent_project_id')->references('id')->on('projects');
            $table->longText('meta_title')->nullable();
            $table->longText('meta_keywords')->nullable();
            $table->longText('meta_description')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();


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
        Schema::dropIfExists('projects');
    }
};
