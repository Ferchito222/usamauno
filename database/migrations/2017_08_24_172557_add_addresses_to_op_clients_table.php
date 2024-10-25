<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAddressesToOpClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('op_clients', function (Blueprint $table) {
            $table->longText('addresses')->after('debit_balance');
            $table->longText('operations')->after('addresses');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('op_clients', function (Blueprint $table) {
            //
        });
    }
}
