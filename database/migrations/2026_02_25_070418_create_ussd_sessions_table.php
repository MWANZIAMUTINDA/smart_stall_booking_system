<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up()
{
    Schema::create('ussd_sessions', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
        $table->string('msisdn');
        $table->string('session_code');
        $table->enum('session_status',['active','completed','expired'])->default('active');
        $table->string('current_step')->nullable();
        $table->json('input_history')->nullable();
        $table->timestamps();
    });
}
};
