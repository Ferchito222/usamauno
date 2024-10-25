<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOpTjResponsesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('op_tj_responses', function (Blueprint $table) {
            $table->integer('id')->unsigned();
            $table->integer('op_tj_detail_id')->unsigned();
            $table->mediumText('response');
            $table->longText('observation');
            $table->timestamps();

            $table->primary('id');
            $table->foreign('op_tj_detail_id')->references('id')->on('op_tj_details');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('op_tj_responses');
    }
}
