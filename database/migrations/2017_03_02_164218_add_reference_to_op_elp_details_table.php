<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddReferenceToOpElpDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE op_elp_details DROP FOREIGN KEY op_elp_details_reference_foreign;');
        DB::statement('DROP INDEX op_elp_details_reference_foreign ON op_elp_details;');
        DB::statement('ALTER TABLE op_elp_details DROP reference_id;');

        Schema::table('op_elp_details', function (Blueprint $table) {
            $table->integer('reference_id')->unsigned()->nullable()->after('age');

            $table->foreign('reference_id')->references('id')->on('ad_cashiers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('op_elp_details', function (Blueprint $table) {
            //
        });
    }
}
