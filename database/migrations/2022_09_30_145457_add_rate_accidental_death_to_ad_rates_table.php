<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRateAccidentalDeathToAdRatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ad_rates', function (Blueprint $table) {
            $table->double('rate_accidental_death', 10, 6)->after('rate_company');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ad_rates', function (Blueprint $table) {
            //
        });
    }
}
