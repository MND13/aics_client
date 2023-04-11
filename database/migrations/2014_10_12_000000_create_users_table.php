<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {

           


            $table->id();
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('ext_name')->nullable();
            $table->string('gender');
            $table->string('mobile_number');
            $table->date('birth_date');
            $table->string('street_number')->nullable();
            $table->unsignedBigInteger('psgc_id');
            $table->string('email')->unique()->nullable();
            $table->string('username')->unique();
            $table->string('password');
            $table->string('uuid');
            $table->rememberToken();
            $table->timestamps();
            

            /*#$table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('uuid');
            $table->rememberToken();
            $table->timestamps();
            $table->unsignedBigInteger('aics_client_id')->nullable();*/
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
