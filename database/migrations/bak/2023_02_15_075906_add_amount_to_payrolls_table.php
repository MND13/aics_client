<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAmountToPayrollsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payrolls', function (Blueprint $table) {
            $table->double('amount', 8, 2)->nullable();
            $table->string('source_of_fund')->nullable();
            $table->string('charging')->nullable();
            
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
            $table->dropColumn('amount');
            $table->dropColumn('source_of_fund');
            $table->dropColumn('charging');
            
        });
    }
}
