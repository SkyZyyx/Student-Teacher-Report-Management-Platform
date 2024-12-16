<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::create('students', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->foreignId('section_id')->constrained()->onDelete('cascade');
        $table->foreignId('group_id')->constrained()->onDelete('cascade');
        $table->timestamps();
    });
}

    
    public function down()
    {
        Schema::dropIfExists('students');
    }
};
