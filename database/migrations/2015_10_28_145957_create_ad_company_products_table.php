<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdCompanyProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ad_company_products', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('ad_company_id')->unsigned();
            $table->integer('ad_product_id')->unsigned();
            $table->boolean('active')->default(false);
            $table->timestamps();

            $table->foreign('ad_company_id')->references('id')->on('ad_companies');
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
        Schema::drop('ad_company_products');
    }
}
