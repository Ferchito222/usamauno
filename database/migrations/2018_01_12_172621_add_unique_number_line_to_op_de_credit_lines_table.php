<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUniqueNumberLineToOpDeCreditLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('DROP INDEX op_de_credit_lines_number_line_index ON op_de_credit_lines;');
        DB::statement('CREATE UNIQUE INDEX op_de_credit_lines_number_line_uindex ON op_de_credit_lines (number_line);');

        Schema::table('op_de_credit_lines', function (Blueprint $table) {

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
