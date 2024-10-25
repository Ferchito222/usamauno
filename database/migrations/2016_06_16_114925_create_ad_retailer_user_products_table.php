<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdRetailerUserProductsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ad_retailer_user_products', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('ad_retailer_user_id')->unsigned();
            $table->integer('ad_product_id')->unsigned();
            $table->timestamps();

            $table->foreign('ad_retailer_user_id')->references('id')->on('ad_retailer_users');
            $table->foreign('ad_product_id')->references('id')->on('ad_products');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('ad_retailer_user_products');
    }
}
