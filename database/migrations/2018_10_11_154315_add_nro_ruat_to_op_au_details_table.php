<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNroRuatToOpAuDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('op_au_details', function (Blueprint $table) {
            $table->string('nro_ruat', 100)->after('use');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('op_au_details', function (Blueprint $table) {
            //
        });
    }
}
