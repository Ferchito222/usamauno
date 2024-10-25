<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOpDeCoverageWarrantiesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('op_de_coverage_warranties', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('op_de_header_id')->unsigned();
            $table->integer('op_au_header_id')->unsigned()->nullable();
            $table->integer('op_td_header_id')->unsigned()->nullable();
            $table->timestamps();

            $table->foreign('op_de_header_id')->references('id')->on('op_de_headers');
            $table->foreign('op_au_header_id')->references('id')->on('op_au_headers');
            $table->foreign('op_td_header_id')->references('id')->on('op_td_headers');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('op_de_coverage_warranties');
    }
}
