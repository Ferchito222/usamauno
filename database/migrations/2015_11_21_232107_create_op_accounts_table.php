<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOpAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('op_accounts', function (Blueprint $table) {
            $table->integer('id')->unsigned();
            $table->integer('op_client_id')->unsigned();
            $table->string('number', 140);
            $table->enum('type', array_keys(config('base.account_number_types')));
            $table->boolean('active')->default(false);
            $table->timestamps();

            $table->primary('id');
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
        Schema::drop('op_accounts');
    }
}
