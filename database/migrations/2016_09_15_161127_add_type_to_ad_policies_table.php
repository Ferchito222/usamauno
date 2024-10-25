<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTypeToAdPoliciesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ad_policies', function (Blueprint $table) {
            $table->enum('type', array_keys(config('base.policy_types')))->nullable()->after('modality');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ad_policies', function (Blueprint $table) {
            //
        });
    }
}
