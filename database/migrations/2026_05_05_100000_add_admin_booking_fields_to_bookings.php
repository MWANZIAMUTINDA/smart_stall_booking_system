<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // Which admin/officer manually booked this
            $table->unsignedBigInteger('booked_by_admin_id')->nullable()->after('user_id');

            // When the admin last sent a payment prompt to the trader
            $table->timestamp('payment_prompt_sent_at')->nullable()->after('receipt_number');

            // Notes the admin recorded about this manual booking
            $table->text('admin_notes')->nullable()->after('payment_prompt_sent_at');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['booked_by_admin_id', 'payment_prompt_sent_at', 'admin_notes']);
        });
    }
};
