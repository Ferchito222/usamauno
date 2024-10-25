<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdCashiersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ad_cashiers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('ad_retailer_id')->unsigned();
            $table->string('full_name');
            $table->string('dni', 15);
            $table->integer('ad_city_id')->unsigned();
            $table->text('agency');
            $table->string('position');
            $table->string('code', 10);
            $table->string('email', 140);
            $table->boolean('active')->default();

            $table->foreign('ad_retailer_id')->references('id')->on('ad_retailers');
            $table->foreign('ad_city_id')->references('id')->on('ad_cities');

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
        Schema::drop('ad_cashiers');
    }
}
