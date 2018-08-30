<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCalendarSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('calendar_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('salon_id')->unsigned();
            $table->foreign('salon_id')->references('id')->on('salons')->onDelete('cascade');
            $table->integer('client_notes')->nullable();
            $table->integer('phone_number')->nullable();
            $table->integer('email_address')->nullable();
            $table->integer('address')->nullable();
            $table->integer('new_client_indicator')->nullable();
            $table->integer('referrer')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('calendar_settings');
    }
}
