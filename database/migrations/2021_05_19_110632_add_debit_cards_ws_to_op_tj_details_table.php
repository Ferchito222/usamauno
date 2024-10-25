<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDebitCardsWsToOpTjDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('op_tj_details', function (Blueprint $table) {
            $table->longText('debit_cards_ws')->after('card_number');
            $table->longText('accounts_ws')->after('debit_cards_ws');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('op_tj_details', function (Blueprint $table) {
            //
        });
    }
}
