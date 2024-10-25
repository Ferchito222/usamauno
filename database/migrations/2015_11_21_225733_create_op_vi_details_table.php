<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOpViDetailsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('op_vi_details', function (Blueprint $table) {
            $table->integer('id')->unsigned();
            $table->integer('op_vi_header_id')->unsigned();
            $table->integer('op_client_id')->unsigned();
            $table->double('insured_value', 20, 2);
            $table->enum('currency', array_keys(config('base.currencies')));
            $table->enum('account_type', array_keys(config('base.account_types')))->nullable();
            $table->string('voucher', 60);
            $table->string('reference', 140);
            $table->string('client_code', 30);
            $table->string('taker_name', 140);
            $table->string('taker_dni', 30);
            $table->string('bill_name', 140);
            $table->string('bill_dni', 30);
            $table->timestamps();

            $table->primary('id');
            $table->foreign('op_vi_header_id')->references('id')->on('op_vi_headers');
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
        Schema::drop('op_vi_details');
    }
}
