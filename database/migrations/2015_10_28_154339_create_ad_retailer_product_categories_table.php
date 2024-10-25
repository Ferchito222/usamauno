<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdRetailerProductCategoriesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ad_retailer_product_categories', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('ad_retailer_product_id')->unsigned();
            $table->enum('category', array_keys(config('base.vehicle_category')));
            $table->boolean('active')->default(false);
            $table->timestamps();

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
        Schema::drop('ad_retailer_product_categories');
    }
}
