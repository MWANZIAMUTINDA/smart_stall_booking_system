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
    Schema::create('feedback', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Assumes 'users' table
        $table->string('message', 160); // Strict 160 char limit
        $table->enum('status', ['pending', 'resolved'])->default('pending');
        $table->timestamps();
    });
}

};
