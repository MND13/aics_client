<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFullname extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string("meta_full_name")->nullable();
            $table->string("full_name")->nullable();
            $table->index("meta_full_name");
            $table->index("full_name");
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
            $table->dropIndex(["meta_full_name"]);
            $table->dropIndex(["full_name"]);
            $table->dropColumn('meta_full_name');
            $table->dropColumn('full_name');
        });
    }
}
