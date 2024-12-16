<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeNomNullableInDevoirsTable extends Migration
{
    public function up()
    {
        Schema::table('devoirs', function (Blueprint $table) {
            $table->string('nom')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('devoirs', function (Blueprint $table) {
            $table->string('nom')->nullable(false)->change();
        });
    }
}
