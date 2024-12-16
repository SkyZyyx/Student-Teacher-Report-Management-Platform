<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddModuleIdToDevoirsTable extends Migration
{
    public function up()
    {
        Schema::table('devoirs', function (Blueprint $table) {
            $table->unsignedBigInteger('module_id')->nullable();

            // Optionally, add a foreign key constraint if you have a `modules` table
            $table->foreign('module_id')->references('id')->on('modules')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('devoirs', function (Blueprint $table) {
            $table->dropForeign(['module_id']);
            $table->dropColumn('module_id');
        });
    }
}
