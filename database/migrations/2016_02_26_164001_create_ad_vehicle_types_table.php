<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdVehicleTypesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ad_vehicle_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('vehicle', 140);
            $table->integer('ad_retailer_product_category_id')->unsigned()->nullable();
            $table->double('percentage', 5, 2);
            $table->boolean('active')->default(false);
            $table->timestamps();

            $table->foreign('ad_retailer_product_category_id',
                'arp_category_id_foreign')->references('id')->on('ad_retailer_product_categories');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('ad_vehicle_types');
    }
}
