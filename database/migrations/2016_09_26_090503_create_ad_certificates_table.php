<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdCertificatesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ad_certificates', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('ad_retailer_product_id')->unsigned();
            $table->integer('ad_credit_product_id')->unsigned()->nullable();
            $table->double('version', 11, 1)->unsigned();
            $table->string('template');
            $table->string('css');
            $table->boolean('active')->default(false);
            $table->timestamps();

            $table->foreign('ad_retailer_product_id')->references('id')->on('ad_retailer_products');
            $table->foreign('ad_credit_product_id')->references('id')->on('ad_credit_products');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('ad_certificates');
    }
}
