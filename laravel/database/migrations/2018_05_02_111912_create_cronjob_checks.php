<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCronjobChecks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cronjob_checks', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('salon_id');
            $table->string('cronjob_type');
            $table->unsignedInteger('client_id');
            $table->foreign('client_id');
            $table->boolean('finished');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cronjob_checks');
    }
}
