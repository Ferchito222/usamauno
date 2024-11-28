<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPhoneNumberMobileToOpDeBenefificariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('op_de_beneficiaries', function (Blueprint $table) {
            $table->string('phone_number_mobile', 30)->after('age');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('op_de_beneficiaries', function (Blueprint $table) {
            //
        });
    }
}
