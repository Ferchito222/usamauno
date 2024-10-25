<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOpVersionCertTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('op_version_cert', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('ad_certificate_id')->unsigned()->nullable();
            $table->integer('ad_retailer_product_id')->unsigned();
            $table->integer('ad_city_id')->unsigned()->nullable();
            $table->string('cert_number', 140);
            $table->boolean('active')->default(false);
            $table->timestamps();
            
            $table->foreign('ad_city_id')->references('id')->on('ad_cities');
            $table->foreign('ad_certificate_id')->references('id')->on('ad_certificates');
            $table->foreign('ad_retailer_product_id')->references('id')->on('ad_retailer_products');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('op_version_cert');
    }
}
