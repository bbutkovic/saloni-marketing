<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBusinessTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('salons', function (Blueprint $table) {
            $table->increments('id');
            $table->string('business_name');
            $table->integer('business_type')->nullable();
            $table->string('website')->nullable();
            $table->string('country')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('zip_code')->nullable();
            $table->string('time_format')->nullable();
            $table->string('time_zone')->nullable();
            $table->string('week_starting_on')->nullable();
            $table->string('logo')->nullable();
            $table->string('contact_name')->nullable();
            $table->string('business_phone')->nullable();
            $table->string('mobile_phone')->nullable();
            $table->string('email_address');
            $table->string('currency')->nullable();
            $table->string('unique_url');
            $table->boolean('online_payments')->default(0);
        });
    
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('salons');
    }
}
