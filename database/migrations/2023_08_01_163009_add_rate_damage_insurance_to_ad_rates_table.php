<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRateDamageInsuranceToAdRatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ad_rates', function (Blueprint $table) {
            $table->double('rate_damage_insurance', 10, 4)->after('rate_bank');
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
