<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAdCreditProductIdToAdProductParametersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ad_product_parameters', function (Blueprint $table) {
            $table->integer('ad_credit_product_id')->unsigned()->nullable()->after('slug');

            $table->foreign('ad_credit_product_id')->references('id')->on('ad_credit_products');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ad_product_parameters', function (Blueprint $table) {
            //
        });
    }
}
