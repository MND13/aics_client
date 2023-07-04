<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFundSourceStatement extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fund_source_statements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fund_source_id')->nullable()->constrained('fund_sources')
            ->onUpdate('cascade')
            ->onDelete('cascade');
            $table->date("date");
            $table->double("closing_balance", 12, 2);
            $table->double("total_credit", 12, 2);
            $table->double("total_debit", 12, 2);
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
        Schema::dropIfExists('fund_source_statement');
    }
}
