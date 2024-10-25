<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdUserProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ad_user_profiles', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('ad_user_id')->unsigned();
            $table->integer('ad_profile_id')->unsigned();
            $table->boolean('active')->default(false);
            $table->timestamps();

            $table->foreign('ad_user_id')->references('id')->on('ad_users');
            $table->foreign('ad_profile_id')->references('id')->on('ad_profiles');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('ad_user_profiles');
    }
}
