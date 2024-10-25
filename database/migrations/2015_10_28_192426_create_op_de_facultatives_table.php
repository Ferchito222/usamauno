<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOpDeFacultativesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('op_de_facultatives', function (Blueprint $table) {
            $table->integer('id')->unsigned();
            $table->integer('op_de_detail_id')->unsigned();
            $table->mediumText('reason');
            $table->enum('state', array_keys(config('base.facultative_states')));
            $table->integer('ad_user_id')->unsigned()->nullable();
            $table->boolean('approved')->default(false);
            $table->boolean('surcharge')->default(false);
            $table->integer('percentage');
            $table->double('current_rate', 10, 2);
            $table->double('final_rate', 10, 2);
            $table->mediumText('observation');
            $table->boolean('reminder')->default(false);
            $table->timestamp('date_reminder')->nullable();
            $table->boolean('read')->default(false);
            $table->timestamps();

            $table->primary('id');
            $table->foreign('op_de_detail_id')->references('id')->on('op_de_details');
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
        Schema::drop('op_de_facultatives');
    }
}
