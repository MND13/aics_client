<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAicsClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aics_clients', function (Blueprint $table) {
            $table->id();
            $table->string('last_name')->nullable();
            $table->string('first_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->string('ext_name')->nullable();
            $table->string('street_number')->nullable();
            $table->string('rel_beneficiary')->nullable();
            $table->unsignedBigInteger('psgc_id')->nullable();
            $table->string('mobile_number')->nullable();
            $table->date('birth_date')->nullable();
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
        Schema::dropIfExists('aics_clients');
    }
}
