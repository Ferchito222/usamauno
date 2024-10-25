<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdSettingLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ad_setting_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('ad_setting_id')->unsigned();
            $table->string('value');
            $table->timestamp('action_time');
            $table->longText('change_message');
            $table->integer('ad_user_id')->unsigned();
            $table->timestamps();

            $table->foreign('ad_setting_id')->references('id')->on('ad_settings');
            $table->foreign('ad_user_id')->references('id')->on('ad_users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('ad_admin_settings');
    }
}
