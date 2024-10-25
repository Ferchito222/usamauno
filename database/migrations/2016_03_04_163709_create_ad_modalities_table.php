<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdModalitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ad_modalities', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('ad_retailer_product_id')->unsigned();
            $table->enum('modality', array_keys(config('base.sp_modalities')));
            $table->integer('order')->unsigned();
            $table->double('rank_min', 20, 2);
            $table->double('rank_max', 20, 2);
            $table->double('amount', 20, 2);
            $table->double('amount_min', 20, 2);
            $table->double('amount_max', 20, 2);
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
        Schema::drop('ad_modalities');
    }
}
