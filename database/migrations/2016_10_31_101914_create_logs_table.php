<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logs', function (Blueprint $table) {
            $table->integer('id')->unsigned();
            $table->string('message');
            $table->integer('ad_user_id')->unsigned();
            $table->string('ip');
            $table->longText('data');
            $table->integer('level')->unsigned();
            $table->string('level_name', 140);
            $table->string('channel');
            $table->longText('extra');
            $table->timestamps();

            $table->primary('id');
            $table->foreign('ad_user_id')->references('id')->on('ad_users');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('logs');
    }
}
