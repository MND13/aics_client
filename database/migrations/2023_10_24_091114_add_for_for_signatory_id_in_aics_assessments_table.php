<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForForSignatoryIdInAicsAssessmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('aics_assessments', function (Blueprint $table) {
            $table->foreignId('gl_for_signatory_id')->nullable()->constrained('signatories')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('aics_assessments', function (Blueprint $table) {
            $table->dropForeign(['gl_for_signatory_id']);
            $table->dropColumn('aics_beneficiary_id');
        });
    }
}
