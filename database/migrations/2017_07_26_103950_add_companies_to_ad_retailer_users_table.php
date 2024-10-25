<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCompaniesToAdRetailerUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE ad_retailer_users DROP FOREIGN KEY ad_retailer_users_ad_company_id_foreign;');
        DB::statement('DROP INDEX ad_retailer_users_ad_company_id_foreign ON ad_retailer_users;');
        DB::statement('ALTER TABLE ad_retailer_users DROP ad_company_id;');

        Schema::table('ad_retailer_users', function (Blueprint $table) {
            $table->longText('companies')->after('ad_user_id');
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
