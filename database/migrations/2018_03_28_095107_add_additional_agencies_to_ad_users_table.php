<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAdditionalAgenciesToAdUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ad_users', function (Blueprint $table) {
            $table->json('additional_agencies')->after('ad_agency_id');
        });

        DB::statement("UPDATE ad_users SET ad_users.additional_agencies = '[]';");
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
