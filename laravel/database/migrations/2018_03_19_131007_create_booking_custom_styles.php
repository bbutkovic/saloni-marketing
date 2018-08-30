<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookingCustomStyles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booking_custom_styles', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('salon_id')->unsigned();
            $table->foreign('salon_id')->references('id')->on('salons')->onDelete('cascade');
            $table->string('action_button')->default('#4CAF50');
            $table->string('select_button')->default('#2196F3');
            $table->string('group_header')->default('#FF9800');
            $table->string('summary')->default('#2196F3');
            $table->string('background')->default('#575F5B');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('booking_custom_styles');
    }
}
