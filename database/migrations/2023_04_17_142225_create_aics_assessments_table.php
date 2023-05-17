<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAicsAssessmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aics_assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('categories')
            ->onUpdate('cascade')
            ->onDelete('cascade');
            $table->foreignId('subcategory_id')->nullable()->constrained('subcategories')
            ->onUpdate('cascade')
            ->onDelete('cascade');
            $table->string("subcategory_others")->nullable();
            $table->string("mode_of_admission")->nullable();
            $table->longText("assessment")->nullable();
            $table->longText("purpose")->nullable();
            $table->double("amount",8,2);
            $table->string("mode_of_assistance")->nullable();
            $table->foreignId('fund_source_id')->nullable()->constrained('fund_sources')
            ->onUpdate('cascade')
            ->onDelete('cascade');
            $table->string("interviewed_by")->nullable();
            $table->string("approved_by")->nullable();
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
        Schema::table('aics_assessments', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropForeign(['subcategory_id']);
            $table->dropForeign(['fund_source_id']);
           
        });

        Schema::dropIfExists('aics_assessments');
    }
}
