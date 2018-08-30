<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLoyaltyProgramsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loyalty_programs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('location_id');
            $table->foreign('location_id')->references('id')->on('locations')->onDelete('cascade');
            $table->integer('loyalty_type');
            $table->string('arrival_points')->nullable();
            $table->string('max_amount')->nullable();
            $table->string('service_group')->nullable();
            $table->string('social_points')->nullable();
            $table->string('referral_points')->nullable();
            $table->string('money_spent')->nullable();
            $table->string('max_points')->nullable();
            $table->string('expire_date')->nullable();
            $table->string('share_title')->nullable();
            $table->string('share_desc')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('loyalty_programs');
    }
}
