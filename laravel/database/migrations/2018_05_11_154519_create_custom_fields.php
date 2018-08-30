<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('custom_fields', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('location_id');
            $table->foreign('location_id')->references('id')->on('locations')->onDelete('cascade');
            $table->string('field_name');
            $table->string('field_title');
            $table->string('field_type');
            $table->string('field_status');
            $table->string('field_location')->nullable();
        });
        
        Schema::create('select_options', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('field_id');
            $table->foreign('field_id')->references('id')->on('custom_fields')->onDelete('cascade');
            $table->string('option_name');
            $table->string('option_value');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('select_options');
        Schema::dropIfExists('custom_fields');
    }
}
