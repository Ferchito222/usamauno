<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOpEbDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('op_eb_details', function (Blueprint $table) {
            $table->integer('id')->unsigned();
            $table->integer('op_eb_header_id')->unsigned();
            $table->boolean('client_eco')->default(false);
            $table->integer('op_client_id')->unsigned();
            $table->integer('age')->unsigned();
            $table->double('premium', 20, 2);
            $table->timestamps();

            $table->primary('id');
            $table->foreign('op_eb_header_id')->references('id')->on('op_eb_headers');
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
        Schema::drop('op_eb_details');
    }
}
