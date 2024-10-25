<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOpDeHeaderIdToOpDeCreditLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE op_de_credit_lines DROP FOREIGN KEY op_de_credit_lines_op_de_detail_id_foreign;');
        DB::statement('DROP INDEX op_de_credit_lines_op_de_detail_id_foreign ON op_de_credit_lines;');
        DB::statement('ALTER TABLE op_de_credit_lines DROP op_de_detail_id;');

        Schema::table('op_de_credit_lines', function (Blueprint $table) {
            $table->integer('op_de_header_id', false, true)->nullable()->after('ad_user_id');

            $table->foreign('op_de_header_id')->references('id')->on('op_de_headers');
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
