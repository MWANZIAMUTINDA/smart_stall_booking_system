<?php

namespace App\Http\Controllers\Trader;

use App\Http\Controllers\Controller;
use App\Models\Stall;
use App\Models\Booking;

class StallController extends Controller
{
    public function index()
    {
        // 1. SILENT CLEANUP: 
        // Find stalls that are 'booked' but their booking has actually expired
        $expiredStallIds = Booking::where('status', 'confirmed')
            ->where('end_time', '<', now())
            ->pluck('stall_id');

        if ($expiredStallIds->isNotEmpty()) {
            // Reset these stalls to available
            Stall::whereIn('id', $expiredStallIds)->update(['status' => 'available']);
            
            // Mark the bookings as expired/completed
            Booking::whereIn('stall_id', $expiredStallIds)
                ->where('end_time', '<', now())
                ->update(['status' => 'expired']);
        }

        // 2. Now fetch the genuinely available stalls
        $stalls = Stall::where('status', 'available')->get();

        return view('trader.stalls.index', compact('stalls'));
    }
}
