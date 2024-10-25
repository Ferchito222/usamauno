<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOpDeCreditLineClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('op_de_credit_line_clients', function (Blueprint $table) {
            $table->integer('id')->unsigned();
            $table->integer('op_de_credit_line_id')->unsigned();
            $table->string('dni', 15);
            $table->string('extension', 4);
            $table->string('complement', 4);
            $table->integer('number');
            $table->json('numbers');
            $table->double('percentage_credit', 5, 2);
            $table->double('rate', 14, 8);
            $table->integer('percentage');
            $table->mediumText('terms');
            $table->boolean('insured')->default(true);
            $table->integer('headline')->unsigned();
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
        Schema::drop('op_de_credit_line_clients');
    }
}
