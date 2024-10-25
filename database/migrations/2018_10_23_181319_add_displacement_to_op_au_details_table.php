<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDisplacementToOpAuDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('op_au_details', function (Blueprint $table) {
             $table->string('circulation_plaza', 100)->after('chassis');
            $table->string('displacement', 100)->after('circulation_plaza');
            $table->string('origin', 100)->after('displacement');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('op_au_details', function (Blueprint $table) {
            //
        });
    }
}
