<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSectionAndGroupeToUsersTable extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('section_id')->nullable();
            $table->unsignedBigInteger('groupe_id')->nullable();

            $table->foreign('section_id')->references('id')->on('sections')->onDelete('set null');
            $table->foreign('groupe_id')->references('id')->on('groups')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['section_id']);
            $table->dropForeign(['groupe_id']);
            $table->dropColumn(['section_id', 'groupe_id']);
        });
    }
}
