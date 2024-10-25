<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdHolidaysTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ad_holidays', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('ad_retailer_id')->unsigned();
            $table->string('holiday_name');
            $table->date('holiday_date');
            $table->timestamps();

            $table->foreign('ad_retailer_id')->references('id')->on('ad_retailers');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('ad_holidays');
    }
}
