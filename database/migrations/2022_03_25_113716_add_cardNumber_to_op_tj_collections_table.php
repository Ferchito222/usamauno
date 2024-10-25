<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCardNumberToOpTjCollectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('op_tj_collections', function (Blueprint $table) {
            $table->string('cardNumber', 100)->after('result_fortaleza');
            $table->string('customerName', 100)->after('cardNumber');
            $table->string('accountNumber', 100)->after('customerName');
            $table->string('transactionDate', 100)->after('accountNumber');
            $table->string('observation', 100)->after('transactionDate');
            $table->string('paymentStatus', 100)->after('observation');
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
