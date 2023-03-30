<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDirtyListIdAicsClients extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('aics_clients', function (Blueprint $table) {
            $table->unsignedBigInteger('dirty_list_id')->nullable();
            $table->foreign('dirty_list_id')->references('id')->on('dirty_lists')->onDelete('cascade');
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
            $table->dropForeign(['dirty_list_id']);
            $table->dropColumn('dirty_list_id');
        });
    }
}
