<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAdCityIdToAdGuestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ad_guests', function (Blueprint $table) {
            $table->integer('ad_city_id')->unsigned()->nullable()->after('email');

            $table->foreign('ad_city_id')->references('id')->on('ad_cities');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ad_guests', function (Blueprint $table) {
            //
        });
    }
}
