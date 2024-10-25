<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOpDeObservationsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('op_de_observations', function (Blueprint $table) {
            $table->integer('id')->unsigned();
            $table->integer('op_de_facultative_id')->unsigned();
            $table->integer('ad_user_id')->unsigned();
            $table->integer('ad_state_id')->unsigned();
            $table->mediumText('observation');
            $table->boolean('response')->default(false);
            $table->mediumText('observation_response');
            $table->timestamp('date_response')->nullable();
            $table->integer('op_mc_answer_id')->unsigned()->nullable();
            $table->timestamps();

            $table->primary('id');
            $table->foreign('op_de_facultative_id')->references('id')->on('op_de_facultatives');
            $table->foreign('ad_user_id')->references('id')->on('ad_users');
            $table->foreign('ad_state_id')->references('id')->on('ad_states');
            $table->foreign('op_mc_answer_id')->references('id')->on('op_mc_answers');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
