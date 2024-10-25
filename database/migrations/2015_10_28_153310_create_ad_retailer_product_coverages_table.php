<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdRetailerProductCoveragesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ad_retailer_product_coverages', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('ad_retailer_product_id')->unsigned();
            $table->integer('ad_coverage_id')->unsigned();
            $table->integer('detail')->unsigned();
            $table->boolean('active')->default(false);
            $table->timestamps();

            $table->foreign('ad_retailer_product_id')->references('id')->on('ad_retailer_products');
            $table->foreign('ad_coverage_id')->references('id')->on('ad_coverages');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('ad_retailer_product_coverages');
    }
}
