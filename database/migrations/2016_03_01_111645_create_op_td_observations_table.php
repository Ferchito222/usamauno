<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOpTdObservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('op_td_observations', function (Blueprint $table) {
            $table->integer('id')->unsigned();
            $table->integer('op_td_facultative_id')->unsigned();
            $table->integer('ad_user_id')->unsigned();
            $table->integer('ad_state_id')->unsigned();
            $table->mediumText('observation');
            $table->boolean('response')->default(false);
            $table->mediumText('observation_response');
            $table->timestamp('date_response')->nullable();
            $table->timestamps();

            $table->primary('id');
            $table->foreign('op_td_facultative_id')->references('id')->on('op_td_facultatives');
            $table->foreign('ad_user_id')->references('id')->on('ad_users');
            $table->foreign('ad_state_id')->references('id')->on('ad_states');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('op_td_observations');
    }
}
