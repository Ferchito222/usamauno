<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSlugToAdCashiersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ad_cashiers', function (Blueprint $table) {
            $table->string('slug', 255)->unique()->after('full_name');
            $table->string('agency_slug', 255)->after('agency');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ad_cashiers', function (Blueprint $table) {
            //
        });
    }
}
