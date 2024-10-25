<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyOpDeFacultativesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('op_de_facultatives', function (Blueprint $table) {
            $table->dropColumn('current_rate');
            $table->dropColumn('final_rate');
        });

        Schema::table('op_de_facultatives', function (Blueprint $table) {
            $table->double('current_rate', 10, 4)->after('percentage');
            $table->double('final_rate', 10, 4)->after('current_rate');
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
