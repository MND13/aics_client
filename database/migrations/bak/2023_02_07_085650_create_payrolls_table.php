<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePayrollsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();
            $table->string("title")->nullable();
            $table->string("sdo")->nullable();
            $table->string("approved_by")->nullable();
            $table->string("certified_by1")->nullable();
            $table->string("certified_by2")->nullable();
            $table->bigInteger("psgc_id")->unsigned();
            $table->foreign('psgc_id')->references('id')->on('psgcs')->onDelete('cascade');
            $table->date("schedule")->nullable();
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
        Schema::table('payrolls', function (Blueprint $table) {
            $table->dropForeign(['psgc_id']);
        });

        Schema::dropIfExists('payrolls');
    }
}
