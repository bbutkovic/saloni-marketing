<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_options', function (Blueprint $table) {
            $table->increments('id');
            $table->string('country');
            $table->boolean('paypal');
            $table->boolean('stripe');
            $table->boolean('wspay');
        });

        Schema::create('salon_payment_options', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('salon_id');
            $table->foreign('salon_id')->references('id')->on('salons')->onDelete('cascade');
            $table->string('payment_gateway');
            $table->boolean('status');
            $table->string('public_key')->nullable();
            $table->string('private_key')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_options');
        Schema::dropIfExists('salon_payment_options');
    }
}
