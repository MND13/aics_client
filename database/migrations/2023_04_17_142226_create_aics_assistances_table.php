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
            $table->foreignId('user_id')->constrained('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreignId('aics_type_id')->constrained('aics_types')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->string('uuid')->nullable();
            $table->integer("age");
            $table->string("occupation")->nullable();
            $table->string("monthly_salary")->nullable();
            $table->date('schedule')->nullable();
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


        Schema::table('aics_assistances', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['assistance_type']);
        });


        Schema::dropIfExists('aics_assistance');
    }
}
