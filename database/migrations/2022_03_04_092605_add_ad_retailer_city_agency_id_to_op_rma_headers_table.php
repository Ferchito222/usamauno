<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAdRetailerCityAgencyIdToOpRmaHeadersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('op_rma_headers', function (Blueprint $table) {
            $table->integer('ad_retailer_city_agency_id')->unsigned()->after('ad_retailer_product_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('op_rma_headers', function (Blueprint $table) {
            //
        });
    }
}
