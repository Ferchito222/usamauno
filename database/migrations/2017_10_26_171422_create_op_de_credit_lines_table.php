<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOpDeCreditLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('op_de_credit_lines', function (Blueprint $table) {
            $table->integer('id')->unsigned();
            $table->integer('ad_user_id')->unsigned()->nullable();
            $table->integer('op_de_detail_id')->unsigned()->nullable();
            $table->string('number_line', 140);
            $table->string('dni', 15);
            $table->string('extension', 4);
            $table->string('complement', 4);
            $table->enum('coverage', array_keys(config('base.credit_line.coverages')));
            $table->double('percentage_credit', 5, 2);
            $table->double('rate', 14, 8);
            $table->integer('percentage');
            $table->boolean('tendered')->default(false);
            $table->boolean('free_cover')->default(false);
            $table->mediumText('terms');
            $table->boolean('deleted')->default(false);
            $table->timestamps();

            $table->primary('id');
            $table->foreign('ad_user_id')->references('id')->on('ad_users');
            $table->foreign('op_de_detail_id')->references('id')->on('op_de_details');
            $table->index('number_line');
            $table->index('dni');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('op_de_credit_lines');
    }
}
