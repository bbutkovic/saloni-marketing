<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCalendarOptions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('calendar_options', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('salon_id')->unsigned();
            $table->foreign('salon_id')->references('id')->on('salons')->onDelete('cascade');
            $table->string('appointment_interval')->default('5');
            $table->string('default_tab')->default('month');
            $table->string('appointment_colors')->default('status');
            $table->integer('staff_photo')->default('0');
            $table->integer('drag_and_drop')->default('0');
            $table->integer('waiting_list')->default('0');
            $table->integer('appointment_number')->default('4');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('calendar_options');
    }
}
