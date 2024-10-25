<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDniCodeToAdUsersTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ad_users', function (Blueprint $table) {
            $table->string('dni', 15)->after('email');
            $table->string('code', 10)->after('dni');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ad_users', function (Blueprint $table) {
            //
        });
    }
}
