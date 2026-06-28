<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('email_verification_codes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('code_hash', 64);     // sha256 of the 8-digit code
            $table->timestamp('expires_at');       // 10 minutes from creation
            $table->unsignedTinyInteger('attempts')->default(0); // failed attempts
            $table->timestamp('used_at')->nullable();             // null = still active
            $table->string('ip_address', 45)->nullable();        // for audit/logging
            $table->timestamps();

            $table->index(['user_id', 'used_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('email_verification_codes');
    }
};
