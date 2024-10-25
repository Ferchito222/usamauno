<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOpDeDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('op_de_details', function (Blueprint $table) {
            $table->integer('id')->unsigned();
            $table->integer('op_de_header_id')->unsigned();
            $table->integer('op_client_id')->unsigned();
            $table->double('percentage_credit', 5, 2);
            $table->double('rate', 10, 2);
            $table->double('balance', 20, 2);
            $table->double('cumulus', 20, 2);
            $table->double('amount', 20, 2);
            $table->boolean('approved')->default(false);
            $table->boolean('rejected')->default(false);
            $table->enum('headline', array_keys(config('base.headlines')));
            $table->timestamps();

            $table->primary('id');
            $table->foreign('op_de_header_id')->references('id')->on('op_de_headers');
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
        Schema::drop('op_de_details');
    }
}
