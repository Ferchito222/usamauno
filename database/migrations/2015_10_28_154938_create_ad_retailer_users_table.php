<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdRetailerUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ad_retailer_users', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('ad_retailer_id')->unsigned();
            $table->integer('ad_user_id')->unsigned();
            $table->boolean('active')->default(false);
            $table->timestamps();

            $table->foreign('ad_retailer_id')->references('id')->on('ad_retailers');
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
        Schema::drop('ad_retailer_users');
    }
}
