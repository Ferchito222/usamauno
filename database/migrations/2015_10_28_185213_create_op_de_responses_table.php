<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOpDeResponsesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('op_de_responses', function (Blueprint $table) {
            $table->integer('id')->unsigned();
            $table->integer('op_de_detail_id')->unsigned();
            $table->mediumText('response');
            $table->longText('observation');
            $table->timestamps();

            $table->primary('id');
            $table->foreign('op_de_detail_id')->references('id')->on('op_de_details');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('op_de_responses');
    }
}
