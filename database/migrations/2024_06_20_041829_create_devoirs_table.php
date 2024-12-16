<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDevoirsTable extends Migration
{
    public function up()
    {
        Schema::create('devoirs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('module_id');
            $table->string('name');
            $table->string('title');
            $table->string('ressources');
            $table->string('description');
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('module_id')->references('id')->on('modules')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('groups');
    }
}