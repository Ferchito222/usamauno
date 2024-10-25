<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOpEbCollectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('op_eb_collections', function (Blueprint $table) {
            $table->integer('id')->unsigned();
            $table->integer('op_eb_header_id')->unsigned();
            $table->bigInteger('fee_number')->unsigned();
            $table->timestamp('fee_date');
            $table->double('fee_amount', 20, 2);
            $table->boolean('charged')->default(false);
            $table->integer('transaction_number')->unsigned();
            $table->timestamp('transaction_date')->nullable();
            $table->double('transaction_amount', 20, 2);
            $table->integer('reason');
            $table->timestamps();

            $table->primary('id');
            $table->foreign('op_eb_header_id')->references('id')->on('op_eb_headers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('op_eb_collections');
    }
}
