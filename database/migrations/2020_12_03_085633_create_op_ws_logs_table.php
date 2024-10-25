<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOpWsLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('op_ws_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('op_header_id')->unsigned();
            $table->text('prefix');
            $table->text('url');
            $table->text('input_parameters');
            $table->text('output_parameters');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('op_ws_logs');
    }
}
