<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdRetailersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ad_retailers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 140);
            $table->text('image');
            $table->string('domain', 30);
            $table->mediumText('about_us');
            $table->string('slug', 140)->unique();
            $table->boolean('active')->default(false);
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
        Schema::drop('ad_retailers');
    }
}
