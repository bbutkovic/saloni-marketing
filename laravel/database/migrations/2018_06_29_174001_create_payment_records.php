<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentRecords extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_records', function (Blueprint $table) {
            $table->increments('id');
            $table->string('payment_method');
            $table->unsignedInteger('salon_id');
            $table->foreign('salon_id')->references('id')->on('salons');
            $table->integer('location_id')->nullable();
            $table->timestamps();
        });

        Schema::create('payment_record_extras', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('payment_id');
            $table->foreign('payment_id')->references('id')->on('payment_records');
            $table->string('sale_id');
            $table->integer('user_id')->nullable();
            $table->integer('client_id')->nullable();
            $table->float('amount_charged', 8, 2);
            $table->string('currency');
            $table->string('payment_for');
            $table->string('identifier');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_record_extras');
        Schema::dropIfExists('payment_records');
    }
}
