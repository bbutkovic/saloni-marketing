<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStaffWorkingHours extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staff_hours', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('staff_id')->unsigned();
            $table->foreign('staff_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('day');
            $table->boolean('status');
            $table->integer('week');
            $table->time('work_start')->nullable();
            $table->time('work_end')->nullable();
            $table->time('lunch_start')->nullable();
            $table->time('lunch_end')->nullable();
        });
        
        Schema::create('staff_hours_options', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('staff_id')->unsigned();
            $table->foreign('staff_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('display_weeks');
            $table->integer('week');
            $table->date('starting_date');
            $table->date('end_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('staff_hours');
        Schema::dropIfExists('staff_hours_options');
    }
}
