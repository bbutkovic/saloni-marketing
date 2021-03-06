<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWebsiteContent extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('website_content', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('salon_id');
            $table->foreign('salon_id')->references('id')->on('salons')->onDelete('cascade');
            $table->text('company_introduction')->nullable();
            $table->text('website_service_text')->nullable();
            $table->text('website_products_text')->nullable();
            $table->text('website_booking_text')->nullable();
            $table->text('terms_and_conditions')->nullable();
            $table->string('facebook_link')->nullable();
            $table->string('twitter_link')->nullable();
            $table->string('instagram_link')->nullable();
            $table->string('pinterest_link')->nullable();
            $table->boolean('display_booking_btn')->default(0);
            $table->string('book_btn_text')->nullable();
            $table->string('book_btn_bg')->nullable();
            $table->string('book_btn_color')->nullable();
            $table->string('about-image')->nullable();
            $table->boolean('display_pricing')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('website_content');
    }
}
