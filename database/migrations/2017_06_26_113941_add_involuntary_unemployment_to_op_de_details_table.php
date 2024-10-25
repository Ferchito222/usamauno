<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddInvoluntaryUnemploymentToOpDeDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('op_de_details', function (Blueprint $table) {
            $table->boolean('involuntary_unemployment')->default(false)->after('headline');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('op_de_details', function (Blueprint $table) {
            //
        });
    }
}
