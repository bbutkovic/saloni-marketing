<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_category', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('location_id');
            $table->foreign('location_id')->references('id')->on('locations')->onDelete('cascade');;
            $table->string('name');
            $table->string('description')->nullable();
            $table->string('cat_color');
            $table->boolean('active');
        });
        
        Schema::create('service_group', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('category_id');
            $table->foreign('category_id')->references('id')->on('service_category')->onDelete('cascade');
            $table->string('group_color');
            $table->string('name');
            $table->string('description')->nullable();
            $table->boolean('active');
        });
        
        Schema::create('service_subcategory', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('group_id');
            $table->foreign('group_id')->references('id')->on('service_group')->onDelete('cascade');
            $table->string('name');
            $table->string('description')->nullable();
            $table->string('subgroup_color');
            $table->boolean('active');
        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('service_subcategory');
        Schema::dropIfExists('service_group');
        Schema::dropIfExists('service_category');
    }
}
