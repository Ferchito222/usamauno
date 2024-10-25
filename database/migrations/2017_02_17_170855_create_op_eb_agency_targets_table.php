<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOpEbAgencyTargetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('op_eb_agency_targets', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('ad_agency_id')->unsigned();
            $table->integer('target')->unsigned();

            $table->timestamps();

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
        Schema::drop('op_eb_agency_targets');
    }
}
