<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSendFortalezaToOpTjCollectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('op_tj_collections', function (Blueprint $table) {
            $table->double('transaction_amount_usd', 20, 2)->after('transaction_amount');
            $table->double('exchange_rate', 5, 2)->after('transaction_amount_usd');
            $table->integer('currency_fortaleza')->unsigned()->after('exchange_rate');
            $table->boolean('send_fortaleza')->default(false)->after('reason');
            $table->longText('result_fortaleza')->after('send_fortaleza');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('op_tj_collections', function (Blueprint $table) {
            //
        });
    }
}
