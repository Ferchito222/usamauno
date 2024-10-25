<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOwnDamageInsuranceToOpAuDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('op_au_details', function (Blueprint $table) {
            $table->boolean('own_damage_insurance')->default(false)->after('guarantee_id');
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
