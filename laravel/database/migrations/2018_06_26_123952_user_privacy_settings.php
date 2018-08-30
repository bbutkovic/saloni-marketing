<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UserPrivacySettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('privacy_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->boolean('sms_reminder')->default(0);
            $table->boolean('email_reminder')->default(0);
            $table->boolean('viber_reminder')->default(0);
            $table->boolean('facebook_reminder')->default(0);
            $table->boolean('push_reminder')->default(0);
            $table->boolean('sms_marketing')->default(0);
            $table->boolean('email_marketing')->default(0);
            $table->boolean('viber_marketing')->default(0);
            $table->boolean('facebook_marketing')->default(0);
            $table->boolean('push_marketing')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('privacy_settings');
    }
}
