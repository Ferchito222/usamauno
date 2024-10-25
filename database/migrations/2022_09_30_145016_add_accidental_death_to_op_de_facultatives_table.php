<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAccidentalDeathToOpDeFacultativesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('op_de_facultatives', function (Blueprint $table) {
            $table->boolean('accidental_death')->default(false)->after('preference_rate');
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
