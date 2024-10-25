<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCapitalToOpAuHeadersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('op_au_headers', function (Blueprint $table) {
            $table->enum('capital', array_keys(config('base.capital')))->nullable()->after('type_term');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('op_au_headers', function (Blueprint $table) {
            //
        });
    }
}
