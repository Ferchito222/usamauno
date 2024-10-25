<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOpViHeadersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('op_vi_headers', function (Blueprint $table) {
            $table->integer('id')->unsigned();
            $table->integer('ad_user_id')->unsigned();
            $table->enum('type', array_keys(config('base.header_types')));
            $table->string('policy_number', 140);
            $table->bigInteger('issue_number')->unsigned();
            $table->text('prefix');
            $table->boolean('pre_printed')->default(false);
            $table->string('pre_printed_number', 140);
            $table->integer('ad_plan_id')->unsigned();
            $table->double('premium', 20, 2);
            $table->enum('payment_method', array_keys(config('base.payment_methods')));
            $table->enum('period', array_keys(config('base.periods')));
            $table->boolean('issued')->default(false);
            $table->timestamp('date_issue')->nullable();
            $table->boolean('canceled')->default(false);
            $table->boolean('pledged')->default(false);
            $table->string('case_number', 140);
            $table->double('amount_pledged', 20, 2);
            $table->text('file');
            $table->integer('copy')->unsigned();
            $table->boolean('read')->default(false);
            $table->timestamps();

            $table->primary('id');
            $table->foreign('ad_user_id')->references('id')->on('ad_users');
            $table->foreign('ad_plan_id')->references('id')->on('ad_plans');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('op_vi_headers');
    }
}
