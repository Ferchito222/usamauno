<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdAuIncrementsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ad_au_increments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('ad_rate_id')->unsigned();
            $table->integer('ad_retailer_product_category_id')->unsigned();
            $table->double('increment', 10, 3);
            $table->timestamps();

            $table->foreign('ad_rate_id')->references('id')->on('ad_rates');
            $table->foreign('ad_retailer_product_category_id',
                'arp_category_increment_id_foreign')->references('id')->on('ad_retailer_product_categories');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('ad_au_increments');
    }
}
