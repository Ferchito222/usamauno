<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOpAuMinimumPremiumsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('op_au_minimum_premiums', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('ad_city_id')->unsigned()->nullable();
            $table->double('minimum_premium', 20, 2);
            
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
        //
    }
}
