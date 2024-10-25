<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdCareCentersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ad_care_centers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('ad_city_id')->unsigned();
            $table->string('place_care');
            $table->string('responsible', 140);
            $table->longText('studies');
            $table->string('address');
            $table->string('working_hour', 140);
            $table->timestamps();

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
        Schema::drop('ad_care_centers');
    }
}
