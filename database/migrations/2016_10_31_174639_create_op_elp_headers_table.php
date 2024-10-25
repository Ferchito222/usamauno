<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateOpElpHeadersTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('CREATE UNIQUE INDEX ad_states_slug_uindex ON ad_states (slug)');

        $states = config('base.states.lp');

        foreach ($states as $key => $state) {
            DB::insert('insert into ad_states (id, state, slug) values (?, ?, ?)', [ '', $state, $key ]);
        }

        Schema::create('op_elp_headers', function (Blueprint $table) {
            $table->integer('id')->unsigned();
            $table->integer('ad_user_id')->unsigned();
            $table->integer('ad_retailer_product_id')->unsigned()->nullable();
            $table->enum('type', array_keys(config('base.header_types')));
            $table->bigInteger('issue_number')->unsigned();
            $table->bigInteger('quote_number')->unsigned();
            $table->text('prefix');
            $table->string('policy_number', 140);
            $table->integer('ad_coverage_id')->unsigned();
            $table->boolean('canceled')->default(false);
            $table->boolean('issued')->default(false);
            $table->timestamp('date_issue')->nullable();
            $table->boolean('approved')->default(false);
            $table->boolean('rejected')->default(false);
            $table->integer('ad_state_id')->unsigned()->nullable();
            $table->boolean('renewal')->default(false);
            $table->longText('renewal_detail');
            $table->integer('copy')->unsigned();
            $table->boolean('read')->default(false);
            $table->integer('ad_certificate_id')->unsigned()->nullable();
            $table->timestamps();

            $table->primary('id');
            $table->foreign('ad_user_id')->references('id')->on('ad_users');
            $table->foreign('ad_retailer_product_id')->references('id')->on('ad_retailer_products');
            $table->foreign('ad_coverage_id')->references('id')->on('ad_coverages');
            $table->foreign('ad_state_id')->references('id')->on('ad_states');
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
        Schema::drop('op_elp_headers');
    }
}
