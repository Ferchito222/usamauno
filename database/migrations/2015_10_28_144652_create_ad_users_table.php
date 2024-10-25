<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ad_users', function (Blueprint $table) {
            $table->integer('id')->unsigned();
            $table->string('username', 140)->unique();
            $table->string('password', 60);
            $table->string('full_name', 140);
            $table->string('email', 140)->unique();
            $table->string('position', 140);
            $table->string('phone_number', 30);
            $table->text('signature');
            $table->boolean('password_change')->default(false);
            $table->timestamp('password_date');
            $table->integer('attempt')->unsigned();
            $table->integer('ad_city_id')->unsigned()->nullable();
            $table->integer('ad_agency_id')->unsigned()->nullable();
            $table->integer('ad_user_type_id')->unsigned();
            $table->boolean('active')->default(false);
            $table->rememberToken();
            $table->timestamps();

            $table->primary('id');
            $table->foreign('ad_city_id')->references('id')->on('ad_cities');
            $table->foreign('ad_agency_id')->references('id')->on('ad_agencies');
            $table->foreign('ad_user_type_id')->references('id')->on('ad_user_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('ad_users');
    }
}
