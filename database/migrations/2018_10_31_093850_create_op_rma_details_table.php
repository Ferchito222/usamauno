<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOpRmaDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('op_rma_details', function (Blueprint $table) {
            $table->integer('id')->unsigned();
            $table->integer('op_rma_header_id')->unsigned();
            $table->double('insured_value', 20, 2);
            $table->double('rate', 10, 2);
            $table->double('rate_month', 10, 4);
            $table->double('premium', 20, 2);
            $table->double('premium_month', 20, 2);
            $table->boolean('approved')->default(false);
            $table->boolean('rejected')->default(false);
            $table->text('file');
            $table->timestamps();

            $table->primary('id');
            $table->foreign('op_rma_header_id')->references('id')->on('op_rma_headers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('op_rma_details');
    }
}
