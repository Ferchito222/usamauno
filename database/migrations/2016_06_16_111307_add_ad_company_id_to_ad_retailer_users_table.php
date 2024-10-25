<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAdCompanyIdToAdRetailerUsersTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ad_retailer_users', function (Blueprint $table) {
            $table->integer('ad_company_id')->unsigned()->nullable()->after('ad_user_id');

            $table->foreign('ad_company_id')->references('id')->on('ad_companies');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ad_retailer_users', function (Blueprint $table) {
            //
        });
    }
}
