<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCompanyApprovalToOpDeDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('op_de_details', function (Blueprint $table) {
            $table->enum('company_approval', array_keys(config('base.product_parameters')))->nullable()
                ->after('percentage_credit');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('op_de_details', function (Blueprint $table) {
            //
        });
    }
}
