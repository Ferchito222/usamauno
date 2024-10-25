<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOpDeDetailCoveragesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('op_de_detail_coverages', function (Blueprint $table) {
            $table->integer('id')->unsigned();
            $table->integer('op_de_detail_id')->unsigned();
            $table->enum('coverage', array_keys(config('base.client_coverages')));
            $table->mediumText('comment');
            $table->boolean('granted')->default(false);
            $table->timestamps();

            $table->primary('id');
            $table->foreign('op_de_detail_id')->references('id')->on('op_de_details');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('op_de_detail_coverages');
    }
}
