<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOpDeCreditLineMovementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('op_de_credit_line_movements', function (Blueprint $table) {
            $table->integer('id')->unsigned();
            $table->integer('op_de_credit_line_id')->unsigned();
            $table->json('data_records');
            $table->timestamps();

            $table->primary('id');
            $table->foreign('op_de_credit_line_id')->references('id')->on('op_de_credit_lines');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('op_de_credit_line_movements');
    }
}
