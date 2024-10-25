<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddVehicleExclusiveToAdProductParametersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ad_product_parameters', function (Blueprint $table) {
            $table->json('exclusive_plates')->after('old_car');
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
