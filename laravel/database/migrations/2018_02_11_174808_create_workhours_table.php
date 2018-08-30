<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWorkhoursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('working_hours', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('location_id')->unsigned();
            $table->foreign('location_id')->references('id')->on('locations')->references('id')->on('locations')->onDelete('cascade');
            $table->string('day')->nullable();
            $table->string('dayname')->nullable();
            $table->string('status')->nullable();
            $table->string('start_time')->nullable();
            $table->string('closing_time')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('working_hours');
    }
}
