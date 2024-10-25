<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdUserWalletAssignmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ad_user_wallet_assignments', function (Blueprint $table) {
            $table->integer('id')->unsigned();
            $table->integer('ad_user_from_id')->unsigned();
            $table->integer('ad_user_to_id')->unsigned();
            $table->string('product_code', 5);
            $table->longText('certificates');
            $table->boolean('disabled')->default(false);
            $table->timestamps();

            $table->primary('id');
            $table->foreign('ad_user_from_id')->references('id')->on('ad_users');
            $table->foreign('ad_user_to_id')->references('id')->on('ad_users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('ad_user_wallet_assignments');
    }
}
