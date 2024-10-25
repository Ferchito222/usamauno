<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOpTdCancellationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('op_td_cancellations', function (Blueprint $table) {
            $table->integer('id')->unsigned();
            $table->integer('op_td_header_id')->unsigned();
            $table->integer('ad_user_id')->unsigned();
            $table->mediumText('reason');
            $table->timestamps();

            $table->primary('id');
            $table->foreign('op_td_header_id')->references('id')->on('op_td_headers');
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
        Schema::drop('op_td_cancellations');
    }
}
