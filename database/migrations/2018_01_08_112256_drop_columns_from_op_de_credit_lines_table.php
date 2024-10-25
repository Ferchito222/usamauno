<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropColumnsFromOpDeCreditLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('DROP TABLE op_de_credit_line_movements;');
        DB::statement('TRUNCATE TABLE op_de_credit_lines;');

        Schema::table('op_de_credit_lines', function (Blueprint $table) {
            $table->dropColumn([
                'dni',
                'extension',
                'complement',
                'percentage_credit',
                'rate',
                'percentage',
                'tendered',
                'terms',
            ]);

            $table->enum('policy_type', [ 'TE', 'TN', 'CA' ])->after('coverage');
            $table->boolean('confirmed')->default(false)->after('free_cover');
            $table->json('data_records')->after('deleted');
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

        });
    }
}
