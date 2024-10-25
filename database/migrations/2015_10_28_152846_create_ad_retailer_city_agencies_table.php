<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdRetailerCityAgenciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ad_retailer_city_agencies', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('ad_retailer_city_id')->unsigned();
            $table->integer('ad_agency_id')->unsigned();
            $table->boolean('active')->default(false);
            $table->timestamps();

            $table->foreign('ad_retailer_city_id')->references('id')->on('ad_retailer_cities');
            $table->foreign('ad_agency_id')->references('id')->on('ad_agencies');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('ad_retailer_city_agencies');
    }
}
