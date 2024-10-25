<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAmountMaxCaToAdProductParametersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ad_product_parameters', function (Blueprint $table) {
            $table->double('amount_max_ca', 20, 2)->after('amount_max');
            $table->double('amount_max_cb', 20, 2)->after('amount_max_ca');
            $table->integer('year_max_ca')->unsigned()->after('amount_max_cb');
            $table->integer('year_max_cb')->unsigned()->after('year_max_ca');
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
