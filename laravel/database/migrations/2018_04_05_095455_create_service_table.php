<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('location_id');
            $table->foreign('location_id')->references('id')->on('locations')->onDelete('cascade');
            $table->unsignedInteger('category');
            $table->foreign('category')->references('id')->on('service_category')->onDelete('cascade');
            $table->unsignedInteger('group');
            $table->foreign('group')->references('id')->on('service_group')->onDelete('cascade');
            $table->integer('sub_group')->nullable();
            $table->smallInteger('order');
            $table->boolean('award_points');
            $table->boolean('allow_discounts');
            $table->string('points_awarded')->default('0');
            $table->string('points_needed')->nullable();
            $table->string('discount')->default('0');
        });
        
        Schema::create('service_details', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('service_id');
            $table->foreign('service_id')->references('id')->on('service')->onDelete('cascade');
            $table->string('name');
            $table->string('description')->nullable();
            $table->string('code')->nullable();
            $table->string('barcode')->nullable();
            $table->time('service_length');
            $table->string('price_no_vat')->nullable();
            $table->string('vat');
            $table->string('base_price');
            $table->boolean('available');
        });
        
        Schema::create('service_staff', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('location_id');
            $table->foreign('location_id')->references('id')->on('locations')->onDelete('cascade');
            $table->unsignedInteger('service_id');
            $table->foreign('service_id')->references('id')->on('service')->onDelete('cascade');
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('service_details');
        Schema::dropIfExists('service_staff');
        Schema::dropIfExists('service');
    }
}
