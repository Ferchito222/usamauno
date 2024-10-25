<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPercentageBankToOpDeFacultativesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('op_de_facultatives', function (Blueprint $table) {
            $table->integer('percentage_bank')->after('percentage');
            $table->integer('percentage_cia')->after('percentage_bank');
            $table->string('monetary_pricing', 140)->after('percentage_cia');
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
