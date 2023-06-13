<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToAicsAssessmentsFundSources extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('aics_assessments_fund_sources', function (Blueprint $table) {
            $table->integer("movement")->nullable();
            $table->string("remarks")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('aics_assessments_fund_sources', function (Blueprint $table) {
            $table->dropColumn('movement');
            $table->dropColumn('remarks');
        });
    }
}
