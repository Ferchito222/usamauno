<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOpTdDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('op_td_details', function (Blueprint $table) {
            $table->integer('id')->unsigned();
            $table->integer('op_td_header_id')->unsigned();
            $table->longText('matter_insured');
            $table->enum('use', array_keys(config('base.property_uses')));
            $table->text('other_use');
            $table->enum('state', array_keys(config('base.property_states')));
            $table->string('city', 140);
            $table->text('zone');
            $table->text('locality');
            $table->mediumText('address');
            $table->double('insured_value', 20, 2);
            $table->double('rate', 10, 2);
            $table->double('premium', 20, 2);
            $table->boolean('approved')->default(false);
            $table->boolean('rejected')->default(false);
            $table->text('file');
            $table->timestamps();

            $table->primary('id');
            $table->foreign('op_td_header_id')->references('id')->on('op_td_headers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('op_td_details');
    }
}
