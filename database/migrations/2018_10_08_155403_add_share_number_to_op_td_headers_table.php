<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddShareNumberToOpTdHeadersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('op_td_headers', function (Blueprint $table) {
            $table->bigInteger('share_number')->unsigned()->after('type_term');
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
