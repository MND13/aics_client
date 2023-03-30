<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePayrollClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payroll_clients', function (Blueprint $table) {
            $table->id();
            $table->integer('sequence')->nullable();
            $table->unsignedBigInteger('aics_client_id')->nullable();
            $table->unsignedBigInteger("payroll_id")->unsigned()->nullable();
            $table->unsignedBigInteger("new_payroll_client_id")->unsigned()->nullable();
            $table->string('status')->nullable();
            $table->date("date_claimed")->nullable();
            $table->foreign('aics_client_id')->references('id')->on('aics_clients')->onDelete('cascade');
            $table->foreign('payroll_id')->references('id')->on('payrolls')->onDelete('cascade');
            $table->foreign('new_payroll_client_id')->references('id')->on('payroll_clients')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payroll_clients');
    }
}
