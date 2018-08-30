<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('location_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->longText('first_name')->nullable();
            $table->longText('last_name')->nullable();
            $table->longText('email');
            $table->longText('phone')->nullable();
            $table->longText('address')->nullable();
            $table->longText('gender')->nullable();
            $table->longText('city')->nullable();
            $table->longText('zip')->nullable();
            $table->longText('birthday')->nullable();
            $table->boolean('sms_reminders')->default(0);
            $table->boolean('sms_marketing')->default(0);
            $table->boolean('email_reminders')->default(0);
            $table->boolean('email_marketing')->default(0);
            $table->boolean('viber_reminders')->default(0);
            $table->boolean('viber_marketing')->default(0);
            $table->boolean('facebook_reminders')->default(0);
            $table->boolean('facebook_marketing')->default(0);
            $table->longText('custom_field_1')->nullable();
            $table->longText('custom_field_2')->nullable();
            $table->longText('custom_field_3')->nullable();
            $table->longText('custom_field_4')->nullable();
            $table->longText('note')->nullable();
            $table->integer('label')->nullable();
            $table->integer('referral')->nullable();
            $table->string('loyalty_points')->default(0);
            $table->integer('arrival_points')->default(0);
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
        Schema::dropIfExists('clients');
    }
}
