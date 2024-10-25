<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdPoliciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ad_policies', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('ad_retailer_product_id')->unsigned();
            $table->integer('ad_city_id')->unsigned()->nullable();
            $table->integer('ad_agency_id')->unsigned()->nullable();
            $table->string('number', 140);
            $table->string('end_policy', 140);
            $table->date('date_begin')->nullable();
            $table->date('date_end')->nullable();
            $table->enum('currency', array_keys(config('base.currencies')))->nullable();
            $table->string('modality', 5);
            $table->boolean('auto_increment')->default(false);
            $table->boolean('active')->default(false);
            $table->timestamps();

            $table->foreign('ad_retailer_product_id')->references('id')->on('ad_retailer_products');
            $table->foreign('ad_city_id')->references('id')->on('ad_cities');
            $table->foreign('ad_agency_id')->references('id')->on('ad_agencies');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('ad_policies');
    }
}
