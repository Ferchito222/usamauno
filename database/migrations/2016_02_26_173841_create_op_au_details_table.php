<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOpAuDetailsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('op_au_details', function (Blueprint $table) {
            $table->integer('id')->unsigned();
            $table->integer('op_au_header_id')->unsigned();
            $table->integer('ad_vehicle_type_id')->unsigned();
            $table->integer('ad_vehicle_make_id')->unsigned();
            $table->integer('ad_vehicle_model_id')->unsigned();
            $table->integer('ad_retailer_product_category_id')->unsigned();
            $table->integer('year')->unsigned();
            $table->string('license_plate', 15);
            $table->enum('use', array_keys(config('base.vehicle_use')));
            $table->string('traction', 4);
            $table->boolean('mileage')->default(false);
            $table->string('color', 30);
            $table->string('engine', 30);
            $table->string('chassis', 30);
            $table->integer('tonnage_capacity');
            $table->integer('seat_number');
            $table->double('insured_value', 20, 2);
            $table->double('rate', 10, 2);
            $table->double('premium', 20, 2);
            $table->boolean('approved')->default(false);
            $table->boolean('rejected')->default(false);
            $table->text('file');
            $table->timestamps();

            $table->primary('id');
            $table->foreign('op_au_header_id')->references('id')->on('op_au_headers');
            $table->foreign('ad_vehicle_type_id')->references('id')->on('ad_vehicle_types');
            $table->foreign('ad_vehicle_make_id')->references('id')->on('ad_vehicle_makes');
            $table->foreign('ad_vehicle_model_id')->references('id')->on('ad_vehicle_models');
            $table->foreign('ad_retailer_product_category_id',
                'arp_category_detail_id_foreign')->references('id')->on('ad_retailer_product_categories');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('op_au_details');
    }
}
