<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddWarrantyToAdRetailerProductsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ad_retailer_products', function (Blueprint $table) {
            $table->boolean('warranty')->default(false)->after('type');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ad_retailer_products', function (Blueprint $table) {
            //
        });
    }
}
