<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('compterendus', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('content'); // Use text for larger content
            $table->double('note')->nullable();
            $table->string('comment')->nullable();
            $table->string('ressources')->nullable(); // Make it nullable if not always required
            $table->unsignedBigInteger('devoir_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            $table->foreign('devoir_id')->references('id')->on('devoirs')->cascadeOnDelete();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('compterendus');
    }
};
