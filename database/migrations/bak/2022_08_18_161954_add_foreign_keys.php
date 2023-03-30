<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeys extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('aics_assistances', function (Blueprint $table) {
            $table->foreign('aics_type_id')->references('id')->on('aics_types')->onDelete('cascade');
        });
        Schema::table('aics_beneficiaries', function (Blueprint $table) {
            $table->foreign('aics_client_id')->references('id')->on('aics_clients')->onDelete('cascade');
            $table->foreign('psgc_id')->references('id')->on('psgcs')->onDelete('cascade');
        });
        Schema::table('aics_clients', function (Blueprint $table) {
            $table->foreign('psgc_id')->references('id')->on('psgcs')->onDelete('cascade');
        });
        Schema::table('aics_documents', function (Blueprint $table) {
            $table->foreign('aics_requrement_id')->references('id')->on('aics_requrements')->onDelete('cascade');
            $table->foreign('aics_assistance_id')->references('id')->on('aics_assistances')->onDelete('cascade');
        });
        Schema::table('aics_requrements', function (Blueprint $table) {
            $table->foreign('aics_type_id')->references('id')->on('aics_types')->onDelete('cascade');
        });
        Schema::table('users', function (Blueprint $table) {
            $table->foreign('aics_client_id')->references('id')->on('aics_clients')->onDelete('cascade');
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
            $table->dropForeign(['aics_client_id']);
            $table->dropForeign(['aics_beneficiary_id']);
            $table->dropForeign(['aics_type_id']);
        });
        Schema::table('aics_beneficiaries', function (Blueprint $table) {
            $table->dropForeign(['aics_client_id']);
        });
        Schema::table('aics_documents', function (Blueprint $table) {
            $table->dropForeign(['aics_requrement_id']);
            $table->dropForeign(['aics_assistance_id']);
        });
        Schema::table('aics_requrements', function (Blueprint $table) {
            $table->dropForeign(['aics_type_id']);
        });
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['aics_client_id']);
        });
    }
}
