<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCardNumberToOpDeHeadersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('op_de_headers', function (Blueprint $table) {
            $table->text('card_number')->after('read');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('op_de_headers', function (Blueprint $table) {
            //
        });
    }
}
