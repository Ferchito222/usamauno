<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLastNameToAdGuestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ad_guests', function (Blueprint $table) {
            $table->renameColumn('full_name', 'first_name');
            $table->string('last_name', 140)->after('full_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ad_guests', function (Blueprint $table) {
            //
        });
    }
}
