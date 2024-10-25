<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddInfoCustomerIdToOpTjDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('op_tj_details', function (Blueprint $table) {
            $table->integer('card_customer_id')->unsigned()->after('card');
            $table->string('card_account_number', 140)->after('card_customer_id');
            $table->string('card_customer_name', 140)->after('card_account_number');
            $table->string('card_due_date', 140)->after('card_customer_name');
            $table->string('card_customer_type', 10)->after('card_due_date');
            $table->string('card_product_type', 140)->after('card_customer_type');
            $table->string('card_card_name', 140)->after('card_product_type');
            $table->integer('card_card_type_code')->unsigned()->after('card_card_name');
            $table->string('card_card_type', 140)->after('card_card_type_code');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('op_tj_details', function (Blueprint $table) {
            //
        });
    }
}
