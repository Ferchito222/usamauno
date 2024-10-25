<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOpDeHeadersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('op_de_headers', function (Blueprint $table) {
            $table->integer('id')->unsigned();
            $table->integer('ad_user_id')->unsigned();
            $table->enum('type', array_keys(config('base.header_types')));
            $table->bigInteger('issue_number')->unsigned();
            $table->bigInteger('quote_number')->unsigned();
            $table->text('prefix');
            $table->string('policy_number', 140);
            $table->string('operation_number', 140);
            $table->integer('ad_coverage_id')->unsigned();
            $table->integer('ad_credit_product_id')->unsigned()->nullable();
            $table->double('amount_requested', 20, 2);
            $table->enum('currency', array_keys(config('base.currencies')));
            $table->integer('term')->unsigned();
            $table->enum('type_term', array_keys(config('base.term_types')));
            $table->double('total_rate', 10, 2);
            $table->double('total_premium', 20, 2);
            $table->boolean('issued')->default(false);
            $table->timestamp('date_issue')->nullable();
            $table->boolean('canceled')->default(false);
            $table->boolean('facultative')->default(false);
            $table->longText('facultative_observation');
            $table->boolean('facultative_sent')->default(false);
            $table->boolean('approved')->default(false);
            $table->boolean('rejected')->default(false);
            $table->integer('copy')->unsigned();
            $table->boolean('read')->default(false);
            $table->timestamps();

            $table->primary('id');
            $table->foreign('ad_user_id')->references('id')->on('ad_users');
            $table->foreign('ad_coverage_id')->references('id')->on('ad_coverages');
            $table->foreign('ad_credit_product_id')->references('id')->on('ad_credit_products');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('op_de_headers');
    }
}
