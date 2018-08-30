<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCalendarColors extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('calendar_colors', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('salon_id')->unsigned();
            $table->foreign('salon_id')->references('id')->on('salons')->onDelete('cascade');
            $table->string('status_booked')->default('#FFE763');
            $table->string('status_complete')->default('#7BA7E1');
            $table->string('status_waiting_list')->default('#FF3300');
            $table->string('status_arrived')->default('#4BFE78');
            $table->string('status_confirmed')->default('#FF4AFF');
            $table->string('status_cancelled')->default('#FF4AFF');
            $table->string('status_rebooked')->default('#F2DCDB');
            $table->string('status_noshow')->default('#FF4848');
            $table->string('status_paid')->default('#261758');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('calendar_colors');
    }
}
