<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Adds admin-controlled stall blocking capability.
     */
    public function up(): void
    {
        Schema::table('stalls', function (Blueprint $table) {
            // Whether the stall is manually blocked by an admin
            $table->boolean('is_blocked')->default(false)->after('status');

            // Reason the admin provided when blocking (required on block)
            $table->string('blocked_reason')->nullable()->after('is_blocked');

            // When the stall was blocked
            $table->timestamp('blocked_at')->nullable()->after('blocked_reason');

            // Which admin blocked it (optional audit trail)
            $table->unsignedBigInteger('blocked_by_admin_id')->nullable()->after('blocked_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stalls', function (Blueprint $table) {
            $table->dropColumn(['is_blocked', 'blocked_reason', 'blocked_at', 'blocked_by_admin_id']);
        });
    }
};
