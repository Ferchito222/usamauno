<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdProductParametersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ad_product_parameters', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('ad_retailer_product_id')->unsigned();
            $table->string('name', 140);
            $table->enum('slug', array_keys(config('base.product_parameters')));
            $table->integer('age_min')->unsigned();
            $table->integer('age_max')->unsigned();
            $table->double('amount_min', 20, 2);
            $table->double('amount_max', 20, 2);
            $table->integer('expiration')->unsigned();
            $table->integer('detail')->unsigned();
            $table->integer('old_car')->unsigned();
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
        Schema::drop('ad_product_parameters');
    }
}
