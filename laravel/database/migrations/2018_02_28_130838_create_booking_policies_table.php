<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookingPoliciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booking_policies', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('salon_id')->unsigned();
            $table->foreign('salon_id')->references('id')->on('salons')->onDelete('cascade');
            $table->integer('staff_selection');
            $table->integer('show_prices');
            $table->integer('first_name_only');
            $table->integer('multiple_staff');
            $table->string('cancel_reschedule_time');
            $table->string('booking_slot');
        });
        
        Schema::create('booking_fields', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('salon_id')->unsigned();
            $table->foreign('salon_id')->references('id')->on('salons')->onDelete('cascade');
            $table->string('field_name');
            $table->string('field_title');
            $table->string('field_status');
            $table->string('field_type');
            $table->string('custom_status');
        });
    
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('booking_policies');
        Schema::dropIfExists('booking_fields');
    }
}
