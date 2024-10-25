<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSendCompanyToOpTjHeadersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('op_tj_headers', function (Blueprint $table) {
            $table->boolean('cpn_send_company')->default(false)->after('warranty');
            $table->integer('cpn_lot_number')->unsigned()->after('cpn_send_company');
            $table->longText('cpn_result_ws')->after('cpn_lot_number');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('op_tj_headers', function (Blueprint $table) {
            //
        });
    }
}
