<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdUserAssignmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ad_user_assignments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('ad_user_id')->unsigned();
            $table->mediumText('assignment');
            $table->timestamps();

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
        Schema::drop('ad_user_assignments');
    }
}
