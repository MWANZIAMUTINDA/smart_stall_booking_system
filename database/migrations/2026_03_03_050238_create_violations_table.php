<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('violations', function (Blueprint $table) {
        $table->id();

        $table->foreignId('trader_id')->constrained('users')->onDelete('cascade');
        $table->foreignId('officer_id')->constrained('users')->onDelete('cascade');

        $table->string('violation_type');
        $table->text('officer_notes');

        $table->text('ai_raw_message')->nullable();
        $table->text('final_letter')->nullable();

        $table->enum('status', ['pending_ai', 'draft_ready', 'approved', 'sent'])
              ->default('pending_ai');

        $table->timestamps();
    });
}
};
