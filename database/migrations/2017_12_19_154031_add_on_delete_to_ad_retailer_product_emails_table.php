<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddOnDeleteToAdRetailerProductEmailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE ad_retailer_product_emails 
                          DROP FOREIGN KEY ad_retailer_product_emails_ad_email_id_foreign;');

        DB::statement('ALTER TABLE ad_retailer_product_emails
                          ADD CONSTRAINT ad_retailer_product_emails_ad_email_id_foreign
                        FOREIGN KEY (ad_email_id)
                        REFERENCES ad_emails (id)
                          ON DELETE CASCADE
                          ON UPDATE NO ACTION;');

        Schema::table('ad_retailer_product_emails', function (Blueprint $table) {
            //
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ad_retailer_product_emails', function (Blueprint $table) {
            //
        });
    }
}
