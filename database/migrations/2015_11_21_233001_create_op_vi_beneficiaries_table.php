<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOpViBeneficiariesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('op_vi_beneficiaries', function (Blueprint $table) {
            $table->integer('id')->unsigned();
            $table->integer('op_vi_detail_id')->unsigned();
            $table->enum('coverage', array_keys(config('base.beneficiary_coverages')));
            $table->string('first_name', 60);
            $table->string('last_name', 60);
            $table->string('mother_last_name', 60);
            $table->string('dni', 15);
            $table->string('extension', 4);
            $table->integer('age')->unsigned();
            $table->string('relationship', 140);
            $table->double('participation', 5, 2);
            $table->timestamps();

            $table->primary('id');
            $table->foreign('op_vi_detail_id')->references('id')->on('op_vi_details');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('op_vi_beneficiaries');
    }
}
