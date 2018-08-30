<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRemindersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reminders', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('location_id');
            $table->foreign('location_id')->references('id')->on('locations')->onDelete('cascade');
            $table->integer('reminder_type');
            $table->integer('reminder_status');
            $table->integer('email_template')->nullable();
            $table->integer('sms_template')->nullable();
            $table->integer('viber_template')->nullable();
            $table->integer('messenger_template')->nullable();
            $table->integer('push_template')->nullable();
            $table->integer('gift_voucher')->nullable();
            $table->time('send_before')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reminders');
    }
}
