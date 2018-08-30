<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->longText('email');
            $table->string('password');
            $table->rememberToken();
            $table->smallInteger('language');
            $table->string('facebook_id')->nullable();
            $table->string('google_id')->nullable();
            $table->smallInteger('email_verified');
            $table->integer('salon_id')->nullable();
            $table->string('location_id')->nullable();
            $table->boolean('gdpr_consent');
            $table->timestamps();
        });

        Schema::create('user_extras', function (Blueprint $table) {
           $table->increments('id');
           $table->unsignedInteger('user_id');
           $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
           $table->longText('first_name');
           $table->longText('last_name');
           $table->longText('birthday')->nullable();
           $table->longText('phone_number')->nullable();
           $table->longText('address')->nullable();
           $table->longText('city')->nullable();
           $table->longText('state')->nullable();
           $table->longText('zip')->nullable();
           $table->longText('country')->nullable();
           $table->longText('photo');
           $table->integer('available_booking')->nullable();
           $table->longText('gender')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_extras');
        Schema::dropIfExists('users');
    }
}
