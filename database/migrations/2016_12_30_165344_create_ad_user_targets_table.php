<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdUserTargetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ad_user_targets', function (Blueprint $table) {
            $table->increments('id');
            $table->string('position');
            $table->integer('ep_a');
            $table->integer('ep_b');
            $table->integer('el_a');
            $table->integer('el_b');

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
        Schema::drop('ad_user_targets');
    }
}
