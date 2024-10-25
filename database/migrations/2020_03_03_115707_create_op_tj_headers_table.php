<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOpTjHeadersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('op_tj_headers', function (Blueprint $table) {
            $table->integer('id')->unsigned();
            $table->integer('ad_user_id')->unsigned();
            $table->integer('ad_retailer_product_id')->unsigned()->nullable();
            $table->integer('ad_retailer_city_agency_id')->unsigned();
            $table->enum('type', array_keys(config('base.header_types')));
            $table->string('policy_number', 140);
            $table->bigInteger('issue_number')->unsigned();
            $table->bigInteger('quote_number')->unsigned();
            $table->text('prefix');
            $table->boolean('pre_printed')->default(false);
            $table->string('pre_printed_number', 140);
            $table->string('bill_name', 140);
            $table->string('bill_dni', 30);
            $table->double('total_premium', 20, 2);
            $table->enum('currency', array_keys(config('base.currencies')))->nullable();
            $table->enum('payment_method', array_keys(config('base.payment_methods')));
            $table->enum('period', array_keys(config('base.periods')));
            $table->integer('term')->unsigned();
            $table->boolean('issued')->default(false);
            $table->timestamp('date_issue')->nullable();
            $table->boolean('canceled')->default(false);
            $table->text('file');
            $table->integer('copy')->unsigned();
            $table->boolean('read')->default(false);
            $table->integer('ad_certificate_id')->unsigned()->nullable();
            $table->boolean('warranty')->default(false);
            $table->timestamp('validity_start')->nullable();
            $table->timestamp('validity_end')->nullable();
            $table->timestamps();

            $table->primary('id');
            $table->foreign('ad_user_id')->references('id')->on('ad_users');
            $table->foreign('ad_retailer_product_id')->references('id')->on('ad_retailer_products');
            $table->foreign('ad_certificate_id')->references('id')->on('ad_certificates');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('op_tj_headers');
    }
}
