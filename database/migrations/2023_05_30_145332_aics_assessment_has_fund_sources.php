<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AicsAssessmentHasFundSources extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aics_assessments_fund_sources', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assessment_id')->nullable()->constrained('aics_assessments')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreignId('fund_source_id')->nullable()->constrained('fund_sources')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->double("amount", 8, 2);
            $table->timestamps();
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
            $table->dropForeign(['assessment_id']);
            $table->dropForeign(['fund_source_id']);
        });



        Schema::dropIfExists('aics_assessments_fund_sources');
    }
}
