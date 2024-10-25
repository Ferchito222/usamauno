<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdRetailerProductsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ad_retailer_products', function (Blueprint $table) {
            $table->integer('id')->unsigned();
            $table->integer('ad_retailer_id')->unsigned();
            $table->integer('ad_company_product_id')->unsigned();
            $table->enum('type', array_keys(config('base.retailer_product_types')));
            $table->boolean('billing')->default(false);
            $table->boolean('provisional_certificate')->default(false);
            $table->boolean('modality')->default(false);
            $table->boolean('facultative')->default(false);
            $table->boolean('ws')->default(false);
            $table->mediumText('landing');
            $table->mediumText('questions');
            $table->boolean('active')->default(false);
            $table->timestamps();

            $table->primary('id');
            $table->foreign('ad_retailer_id')->references('id')->on('ad_retailers');
            $table->foreign('ad_company_product_id')->references('id')->on('ad_company_products');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('ad_retailer_products');
    }
}
