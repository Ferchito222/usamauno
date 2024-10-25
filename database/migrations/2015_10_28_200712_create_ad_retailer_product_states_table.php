<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdRetailerProductStatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ad_retailer_product_states', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('ad_retailer_product_id')->unsigned();
            $table->integer('ad_state_id')->unsigned();
            $table->boolean('active')->default(false);
            $table->timestamps();

            $table->foreign('ad_retailer_product_id')->references('id')->on('ad_retailer_products');
            $table->foreign('ad_state_id')->references('id')->on('ad_states');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('ad_retailer_product_states');
    }
}
