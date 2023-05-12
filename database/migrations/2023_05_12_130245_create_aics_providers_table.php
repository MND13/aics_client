<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAicsProvidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aics_providers', function (Blueprint $table) {
            $table->id();
            $table->string("addressee_name")->nullable();
            $table->string("addressee_position")->nullable();
            $table->string("company_name")->nullable();
            $table->string("company_address")->nullable();
            $table->string("action_executed_by")->nullable();
            $table->string("to_mention")->nullable();
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
        Schema::dropIfExists('aics_providers');
    }
}
