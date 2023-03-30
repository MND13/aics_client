<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateAicsClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('aics_clients', function (Blueprint $table) {
            $table->dropForeign(['payroll_id']);
            $table->dropColumn('status');
            $table->dropColumn('date_claimed');
            $table->dropColumn('payroll_id');
            $table->dropColumn('payroll_insert_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('aics_clients', function (Blueprint $table) {
            $table->bigInteger("payroll_id")->unsigned()->nullable();
            $table->foreign('payroll_id')->references('id')->on('payrolls')->onDelete('cascade');

            $table->string('status')->nullable();
            $table->date("date_claimed")->nullable();
            $table->dateTime('payroll_insert_at')->nullable();
        });
    }
}
