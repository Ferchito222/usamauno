<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMovementTypeToOpDeHeadersTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('op_de_headers', function (Blueprint $table) {
            $table->enum('movement_type', array_keys(config('base.movement_types')))->after('operation_number');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('op_de_headers', function (Blueprint $table) {
            //
        });
    }
}
