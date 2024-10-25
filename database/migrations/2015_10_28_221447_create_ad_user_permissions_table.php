<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdUserPermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ad_user_permissions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('ad_user_id')->unsigned();
            $table->integer('ad_permission_id')->unsigned();
            $table->boolean('active')->default(false);
            $table->timestamps();

            $table->foreign('ad_user_id')->references('id')->on('ad_users');
            $table->foreign('ad_permission_id')->references('id')->on('ad_permissions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('ad_user_permissions');
    }
}
