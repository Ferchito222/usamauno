<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTypeOfRequestToOpDeHeadersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('op_de_headers', function (Blueprint $table) {
            $table->enum('type_of_request', array_keys(config('base.types_of_requests')))->nullable()->after('type_term');
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
