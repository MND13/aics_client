<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBeneIdToAicsAssistancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('aics_assistances', function (Blueprint $table) {
            $table->unsignedBigInteger('aics_beneficiary_id')->nullable();
            $table->foreign('aics_beneficiary_id')->references('id')->on('aics_beneficiaries')->onDelete('cascade');
            $table->string('rel_beneficiary')->nullable();            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('aics_assistances', function (Blueprint $table) {
            $table->dropForeign(['aics_beneficiary_id']);
            $table->dropColumn('rel_beneficiary');
        });
    }
}
