<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAicsBeneficiariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aics_beneficiaries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('aics_client_id')->nullable();
            $table->string('last_name')->nullable();
            $table->string('first_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->string('ext_name')->nullable();
            $table->string('street_number')->nullable();
            $table->unsignedBigInteger('psgc_id')->nullable();
            $table->string('mobile_number')->nullable();
            $table->date('birth_date')->nullable();
            $table->integer('age')->nullable();
            $table->string('gender')->nullable();
            $table->string('occupation')->nullable();
            $table->float('monthly_salary', 15, 2)->nullable();
            $table->string('rel_client')->nullable();
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
        Schema::dropIfExists('aics_beneficiaries');
    }
}
