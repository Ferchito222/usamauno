<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveNumberLineUniqueKeyFromOpDeCreditLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('op_de_credit_lines', function (Blueprint $table) {
            // $table->dropUnique(['number_line']);
            $table->dropUnique('op_de_credit_lines_number_line_uindex');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('op_de_credit_lines', function (Blueprint $table) {
            //
        });
    }
}
