<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOpClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('op_clients', function (Blueprint $table) {
            $table->integer('id')->unsigned();
            $table->integer('ad_retailer_id')->unsigned();
            $table->string('code', 10);
            $table->enum('type', array_keys(config('base.client_types')));
            $table->string('business_name', 140);
            $table->string('first_name', 60);
            $table->string('last_name', 60);
            $table->string('mother_last_name', 60);
            $table->string('married_name', 60);
            $table->date('birthdate');
            $table->integer('age')->unsigned();
            $table->text('birth_place');
            $table->string('dni', 15);
            $table->string('extension', 4);
            $table->string('complement', 4);
            $table->enum('document_type', array_keys(config('base.client_document_types')));
            $table->text('dni_file');
            $table->enum('civil_status', array_keys(config('base.client_civil_status')));
            $table->enum('gender', array_keys(config('base.client_genders')));
            $table->string('place_residence', 140);
            $table->text('locality');
            $table->mediumText('home_address');
            $table->string('home_number', 10);
            $table->enum('avenue_street', array_keys(config('base.avenue_street')))->nullable();
            $table->mediumText('business_address');
            $table->string('country', 60);
            $table->integer('ad_activity_id')->unsigned();
            $table->mediumText('occupation_description');
            $table->string('phone_number_home', 30);
            $table->string('phone_number_office', 30);
            $table->string('phone_number_mobile', 30);
            $table->string('email', 140);
            $table->integer('weight')->unsigned();
            $table->integer('height')->unsigned();
            $table->enum('hand', array_keys(config('base.client_hands')))->nullable();
            $table->double('debit_balance', 20, 2);
            $table->timestamps();

            $table->primary('id');
            $table->foreign('ad_retailer_id')->references('id')->on('ad_retailers');
            $table->foreign('ad_activity_id')->references('id')->on('ad_activities');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('op_clients');
    }
}
