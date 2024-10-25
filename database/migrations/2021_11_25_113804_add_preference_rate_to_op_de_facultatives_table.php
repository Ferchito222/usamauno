<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPreferenceRateToOpDeFacultativesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('op_de_facultatives', function (Blueprint $table) {
            $table->boolean('preference_rate')->default(false)->after('final_rate');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('op_de_facultatives', function (Blueprint $table) {
            //
        });
    }
}
