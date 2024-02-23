<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Make the gender column nullable
            $table->string('gender')->nullable()->change();
            
            // OR Set a default value
             $table->string('gender')->default('Lalake')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Revert the changes made in the "up" method
            $table->string('gender')->nullable(false)->change();
            
          
        });
    }
}
