<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLocationSliderImages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('location_slider', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('salon_id');
            $table->foreign('salon_id')->references('id')->on('salons')->onDelete('cascade');
            $table->unsignedInteger('image_id');
            $table->foreign('image_id')->references('id')->on('website_images')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('location_slider');
    }
}
