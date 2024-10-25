<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUsernameOldAndEmailOldToAdUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ad_users', function (Blueprint $table) {
            $table->string('username_old', 140)->nullable()->after('username');
            $table->string('email_old', 140)->nullable()->after('email');

            $table->index(['username_old', 'email_old']);
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
