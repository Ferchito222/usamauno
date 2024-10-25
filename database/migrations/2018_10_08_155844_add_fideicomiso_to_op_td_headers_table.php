<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFideicomisoToOpTdHeadersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('op_td_headers', function (Blueprint $table) {
            $table->boolean('fideicomiso')->default(false)->after('read');
            $table->double('insolute_balance', 20, 2)->after('fideicomiso');
            $table->integer('loan_number')->unsigned()->after('quote_number');
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
