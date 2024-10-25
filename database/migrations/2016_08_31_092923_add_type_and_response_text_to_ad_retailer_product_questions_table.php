<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTypeAndResponseTextToAdRetailerProductQuestionsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ad_retailer_product_questions', function (Blueprint $table) {
            $table->boolean('response_text')->default(false)->after('response');
            $table->enum('type', array_keys(config('base.question_types')))->nullable()->after('response_text');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ad_retailer_product_questions', function (Blueprint $table) {
            //
        });
    }
}
