<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddProcessToOpDeDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE op_de_headers DROP COLUMN process;");

        Schema::table('op_de_details', function (Blueprint $table) {
            $table->boolean('process')->default(false)->after('validated');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('op_de_details', function (Blueprint $table) {
            //
        });
    }
}
