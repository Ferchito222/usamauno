<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCityAndAgencyToOpElpHeadersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('op_elp_headers', function (Blueprint $table) {
            $table->integer('ad_city_id')->unsigned()->nullable()->after('renewal_detail');
            $table->integer('ad_agency_id')->unsigned()->nullable()->after('ad_city_id');

            $table->foreign('ad_city_id')->references('id')->on('ad_cities');
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
        Schema::table('op_elp_headers', function (Blueprint $table) {
            //
        });
    }
}
