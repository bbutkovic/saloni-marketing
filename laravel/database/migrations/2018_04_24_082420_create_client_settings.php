<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('salon_id')->unsigned();
            $table->foreign('salon_id')->references('id')->on('salons')->onDelete('cascade');
            $table->boolean('sms')->default(0);
            $table->boolean('email')->default(0);
            $table->boolean('viber')->default(0);
            $table->boolean('facebook')->default(0);
            $table->string('name_format')->default('first_last');
        });
        
        Schema::create('client_labels', function (Blueprint $table) {
            $table->increments('id');
            $table->string('salon_id')->default('all');
            $table->string('name');
            $table->string('color');
        });
        
        Schema::create('client_referrals', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('salon_id')->unsigned();
            $table->foreign('salon_id')->references('id')->on('salons')->onDelete('cascade');
            $table->string('name');
        });
        
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('client_settings');
        Schema::dropIfExists('client_labels');
        Schema::dropIfExists('client_referrals');
    }
}
