<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddModelAndActiveToAdPermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ad_permissions', function (Blueprint $table) {
            $table->string('model', 10)->after('slug');
            $table->boolean('active')->default(false)->after('model');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ad_permissions', function (Blueprint $table) {
            //
        });
    }
}
