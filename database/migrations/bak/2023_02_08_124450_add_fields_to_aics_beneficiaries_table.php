<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToAicsBeneficiariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('aics_clients', function (Blueprint $table) {
            $table->integer('age')->nullable();
            $table->string('gender')->nullable();
            $table->string('occupation')->nullable();
            $table->string('monthly_salary')->nullable();
            $table->string('civil_status')->nullable();
            $table->string('mode_of_admission')->nullable();

            $table->bigInteger("aics_type_id")->unsigned()->nullable();
            $table->foreign('aics_type_id')->references('id')->on('aics_types')->onDelete('cascade');

            $table->longText("assessment")->nullable();
            $table->string("interviewed_by")->nullable();

            $table->bigInteger("category_id")->unsigned()->nullable();
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');

            $table->bigInteger("subcategory_id")->unsigned()->nullable();
            $table->foreign('subcategory_id')->references('id')->on('subcategories')->onDelete('cascade');
            $table->string("subcategory_others")->nullable();

            $table->bigInteger("payroll_id")->unsigned()->nullable();
            $table->foreign('payroll_id')->references('id')->on('payrolls')->onDelete('cascade');

            $table->string('status')->nullable();
            $table->date("date_claimed")->nullable();
            
            $table->dateTime('payroll_insert_at')->nullable();

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
            $table->dropForeign(['category_id']);
            $table->dropForeign(['subcategory_id']);
            $table->dropColumn('subcategory_others');
            $table->dropColumn('interviewed_by');
            $table->dropColumn('assessment');
            $table->dropColumn('subcategory_id');
            $table->dropColumn('category_id');
           
            $table->dropColumn('status');
            $table->dropColumn('date_claimed');
        });
    }
}
