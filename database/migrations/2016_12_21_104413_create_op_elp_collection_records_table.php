<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOpElpCollectionRecordsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('op_elp_collection_records', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamp('date');
            $table->integer('total_record')->unsigned();
            $table->double('total_amount', 20, 2);
            $table->timestamp('date_shipping')->nullable();
            $table->boolean('confirmation')->default(false);
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
        Schema::drop('op_elp_collection_records');
    }
}
