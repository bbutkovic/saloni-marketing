<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMarketingCampaign extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('marketing_campaign', function (Blueprint $table) {
            $table->increments('id');
            $table->string('created_by')->default('salonadmin');
            $table->integer('salon_id')->nullable();
            $table->integer('location_id')->nullable();
            $table->integer('email_template')->nullable();
            $table->integer('sms_template')->nullable();
            $table->integer('viber_template')->nullable();
            $table->integer('messenger_template')->nullable();
            $table->string('name');
            $table->string('inactivity');
            $table->string('gender');
            $table->string('older_than')->nullable();
            $table->string('younger_than')->nullable();
            $table->string('with_label')->nullable();
            $table->string('with_referral')->nullable();
            $table->string('with_staff')->nullable();
            $table->string('with_category')->nullable();
            $table->string('with_service')->nullable();
            $table->string('campaign_frequency');
            $table->string('campaign_time');
            $table->string('loyalty_points')->nullable();
            $table->string('gift_voucher')->nullable();
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
        Schema::dropIfExists('marketing_campaign');
    }
}
