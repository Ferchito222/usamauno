<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBrandToOpAuDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('op_au_details', function (Blueprint $table) {
            $table->string('brand', 100)->after('origin');
            $table->string('brand_id', 100)->after('brand');
            $table->string('model', 100)->after('brand_id');
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
