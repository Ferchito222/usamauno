<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdRatesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ad_rates', function (Blueprint $table) {
            $table->increments('id');
            $table->double('rate_company', 10, 4);
            $table->double('rate_bank', 10, 4);
            $table->double('rate_final', 10, 4);
            $table->integer('year')->unsigned();
            $table->integer('ad_retailer_product_id')->unsigned();
            $table->integer('ad_credit_product_id')->unsigned()->nullable();
            $table->integer('ad_coverage_id')->unsigned()->nullable();
            $table->timestamps();

            $table->foreign('ad_retailer_product_id')->references('id')->on('ad_retailer_products');
            $table->foreign('ad_credit_product_id')->references('id')->on('ad_credit_products');
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
        Schema::drop('ad_rates');
    }
}
