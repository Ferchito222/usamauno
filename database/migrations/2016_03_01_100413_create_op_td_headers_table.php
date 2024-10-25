<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOpTdHeadersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('op_td_headers', function (Blueprint $table) {
            $table->integer('id')->unsigned();
            $table->integer('ad_user_id')->unsigned();
            $table->integer('op_client_id')->unsigned();
            $table->enum('type', array_keys(config('base.header_types')));
            $table->bigInteger('issue_number')->unsigned();
            $table->bigInteger('quote_number')->unsigned();
            $table->text('prefix');
            $table->string('policy_number', 140);
            $table->string('operation_number', 140);
            $table->boolean('provisional_certificate')->default(false);
            $table->boolean('warranty')->default(false);
            $table->timestamp('validity_start')->nullable();
            $table->timestamp('validity_end')->nullable();
            $table->enum('payment_method', array_keys(config('base.payment_methods')));
            $table->integer('term')->unsigned();
            $table->enum('type_term', array_keys(config('base.term_types')));
            $table->string('bill_name', 140);
            $table->string('bill_dni', 30);
            $table->boolean('issued')->default(false);
            $table->timestamp('date_issue')->nullable();
            $table->boolean('canceled')->default(false);
            $table->boolean('facultative')->default(false);
            $table->longText('facultative_observation');
            $table->boolean('facultative_sent')->default(false);
            $table->boolean('approved')->default(false);
            $table->boolean('rejected')->default(false);
            $table->double('total_premium', 20, 2);
            $table->integer('copy')->unsigned();
            $table->boolean('read')->default(false);
            $table->timestamps();

            $table->primary('id');
            $table->foreign('ad_user_id')->references('id')->on('ad_users');
            $table->foreign('op_client_id')->references('id')->on('op_clients');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('op_td_headers');
    }
}
