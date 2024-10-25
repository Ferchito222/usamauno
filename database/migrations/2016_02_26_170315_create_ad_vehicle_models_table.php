<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdVehicleModelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ad_vehicle_models', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('ad_vehicle_make_id')->unsigned();
            $table->string('model', 140);
            $table->boolean('active')->default(false);
            $table->timestamps();

            $table->foreign('ad_vehicle_make_id')->references('id')->on('ad_vehicle_makes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('ad_vehicle_models');
    }
}
