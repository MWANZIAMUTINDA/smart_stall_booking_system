<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Booking;
use App\Models\Stall;
use Carbon\Carbon;

class VacateExpiredStalls extends Command
{
    protected $signature = 'stalls:vacate';
    protected $description = 'Automatically vacate stalls where the booking time has lapsed.';

    public function handle()
    {
        // 1. Find all 'confirmed' bookings where the end_time has passed
        $expiredBookings = Booking::where('status', 'confirmed')
            ->where('end_time', '<', Carbon::now())
            ->get();

        foreach ($expiredBookings as $booking) {
            // 2. Mark the Booking as Expired
            $booking->update(['status' => 'expired']);

            // 3. Mark the Stall as Available again
            $booking->stall->update(['status' => 'available']);

            $this->info("Stall #{$booking->stall->stall_number} has been vacated.");
        }
    }
}
