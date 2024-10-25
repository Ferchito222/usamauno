<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddClientIdOldToOpClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('op_clients', function (Blueprint $table) {
            $table->integer('client_id_old')->unsigned()->nullable()->after('ad_retailer_id');

            $table->index('client_id_old');
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
