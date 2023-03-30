<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAicsAssistancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aics_assistances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('aics_client_id')->nullable();
            $table->unsignedBigInteger('aics_beneficiary_id')->nullable();
            $table->unsignedBigInteger('aics_type_id')->nullable();
            $table->string('sub_type')->nullable();
            $table->string('uuid')->nullable();
            $table->string('status')->nullable();
            $table->date('status_date')->nullable();
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
        Schema::dropIfExists('aics_assistances');
    }
}
