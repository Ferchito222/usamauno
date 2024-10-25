<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdCitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ad_cities', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 140);
            $table->string('abbreviation', 5)->unique();
            $table->boolean('type_ci')->default(false);
            $table->boolean('type_re')->default(false);
            $table->boolean('type_de')->default(false);
            $table->string('slug', 140)->unique();
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
        Schema::drop('ad_cities');
    }
}
