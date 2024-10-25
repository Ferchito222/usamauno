<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOpTjDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('op_tj_details', function (Blueprint $table) {
            $table->integer('id')->unsigned();
            $table->integer('op_tj_header_id')->unsigned();
            $table->integer('op_client_id')->unsigned();
            $table->double('insured_value', 20, 2);
            $table->enum('account_type', array_keys(config('base.account_types')))->nullable();
            $table->string('account_number', 140);
            $table->string('voucher', 60);
            $table->string('reference', 140);
            $table->string('client_code', 30);
            $table->string('card_number', 50);
            $table->integer('expiration_card_year')->unsigned();
            $table->integer('expiration_card_month')->unsigned();
            $table->boolean('holder')->default(false);
            $table->timestamps();

            $table->primary('id');
            $table->foreign('op_tj_header_id')->references('id')->on('op_tj_headers');
            $table->foreign('op_client_id')->references('id')->on('op_clients');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('op_tj_details');
    }
}
