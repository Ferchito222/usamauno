<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOpElpDetailsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('op_elp_details', function (Blueprint $table) {
            $table->integer('id')->unsigned();
            $table->integer('op_elp_header_id')->unsigned();
            $table->boolean('client_eco')->default(false);
            $table->integer('op_client_id')->unsigned();
            $table->integer('age')->unsigned();
            $table->integer('reference')->unsigned()->nullable();
            $table->text('reference_other');
            $table->double('premium', 20, 2);
            $table->enum('payment_method', array_keys(config('base.payment_methods')))->nullable();
            $table->enum('account_type', array_keys(config('base.account_types')))->nullable();
            $table->enum('currency', array_keys(config('base.currencies')))->nullable();
            $table->boolean('charge')->default(false);
            $table->mediumText('charge_response');
            $table->mediumText('charge_period');
            $table->string('operation_number', 140);
            $table->enum('operation_currency', array_keys(config('base.currencies')))->nullable();
            $table->string('voucher', 60);
            $table->string('subsidiary_code', 10);
            $table->timestamps();

            $table->primary('id');
            $table->foreign('op_elp_header_id')->references('id')->on('op_elp_headers');
            $table->foreign('op_client_id')->references('id')->on('op_clients');
            $table->foreign('reference')->references('id')->on('ad_users');
        });

        Schema::table('op_accounts', function (Blueprint $table) {
            $table->boolean('main_account')->default(false)->after('type');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('op_elp_details');
    }
}
