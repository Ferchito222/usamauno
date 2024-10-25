<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddShareToOpTdHeadersTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('op_td_headers', function (Blueprint $table) {
            $table->longText('share')->after('total_premium');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('op_td_headers', function (Blueprint $table) {
            //
        });
    }
}
