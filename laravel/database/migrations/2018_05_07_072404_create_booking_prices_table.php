<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookingPricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booking_price', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('location_id');
            $table->foreign('location_id')->references('id')->on('locations')->onDelete('cascade');
            $table->string('type');
            $table->string('booking_id');
            $table->string('code_used')->default(0);
            $table->string('total_base_price');
            $table->string('price');
            $table->string('currency');
            $table->string('points_used')->nullable();
            $table->string('selected_discount')->nullable();
            $table->string('free_service')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('booking_price');
    }
}
