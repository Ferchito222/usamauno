<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddYearOfEntryToWorkToOpClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('op_clients', function (Blueprint $table) {
            $table->string('company_work', 140)->after('workplace');
            $table->integer('year_of_entry_to_work')->unsigned()->after('company_work');
            $table->string('type_of_contract', 140)->after('year_of_entry_to_work');
            $table->boolean('public_work')->default(false)->after('type_of_contract');
            $table->string('position', 140)->after('public_work');
            $table->string('period', 100)->after('position');
            $table->string('institution', 100)->after('period');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('op_clients', function (Blueprint $table) {
            //
        });
    }
}
