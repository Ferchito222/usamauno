<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOpAuRatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('op_au_rates', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('ad_rate_id')->unsigned();
            $table->integer('ad_city_id')->unsigned()->nullable();
            $table->double('rate', 10, 4);
            
            $table->timestamps();

            $table->foreign('ad_rate_id')->references('id')->on('ad_rates');
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
        //
    }
}
