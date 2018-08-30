<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLocationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('locations', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('salon_id');
            $table->foreign('salon_id')->references('id')->on('salons')->onDelete('cascade');
            $table->string('location_name');
            $table->string('business_phone')->nullable();
            $table->string('mobile_phone')->nullable();
            $table->string('country');
            $table->string('address');
            $table->string('city');
            $table->string('zip');
            $table->string('time_format')->nullable();
            $table->string('email_address')->nullable();
            $table->integer('online_booking')->nullable();
            $table->string('lat')->nullable();
            $table->string('lng')->nullable();
            $table->boolean('happy_hour')->default(0);
            $table->string('unique_url')->unique();
        });
        
        Schema::create('location_extras', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('location_id')->unsigned();
            $table->foreign('location_id')->references('id')->on('locations')->onDelete('cascade');
            $table->string('location_photo')->nullable();
            $table->integer('parking')->nullable();
            $table->integer('credit_cards')->nullable();
            $table->integer('accessible_for_disabled')->nullable();
            $table->integer('child-frendly')->nullable();
            $table->integer('wifi')->nullable();
            $table->integer('pets')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('location_extras');
        Schema::dropIfExists('locations');
    }
}
