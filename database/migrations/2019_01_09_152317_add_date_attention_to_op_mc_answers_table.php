<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDateAttentionToOpMcAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('op_mc_answers', function (Blueprint $table) {
            $table->timestamp('date_attention')->nullable()->after('time_attention');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('op_mc_answers', function (Blueprint $table) {
            //
        });
    }
}
