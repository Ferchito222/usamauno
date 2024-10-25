<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOpRmaObservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('op_rma_observations', function (Blueprint $table) {
            $table->integer('id')->unsigned();
            $table->integer('op_rma_facultative_id')->unsigned();
            $table->integer('ad_user_id')->unsigned();
            $table->integer('ad_state_id')->unsigned();
            $table->mediumText('observation');
            $table->boolean('response')->default(false);
            $table->mediumText('observation_response');
            $table->timestamp('date_response')->nullable();
            $table->timestamps();

            $table->primary('id');
            $table->foreign('op_rma_facultative_id')->references('id')->on('op_rma_facultatives');
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
        Schema::drop('op_rma_observations');
    }
}
