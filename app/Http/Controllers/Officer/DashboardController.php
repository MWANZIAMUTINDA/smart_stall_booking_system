<?php

namespace App\Http\Controllers\Officer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Violation;
use App\Models\User;
use App\Models\Stall;

class DashboardController extends Controller
{
    public function index()
    {
        // 1️⃣ AUTO-CLEANUP: Release any stalls that just expired
        $expiredBookings = Booking::expiredButStillConfirmed()->get();

        if ($expiredBookings->isNotEmpty()) {
            $stallIds = $expiredBookings->pluck('stall_id');
            Stall::whereIn('id', $stallIds)->update(['status' => 'available']);
            Booking::whereIn('id', $expiredBookings->pluck('id'))->update(['status' => 'expired']);
        }

        // 2️⃣ FILTERED BOOKINGS: Only show traders whose time hasn't run out
        // This removes the "1 day ago" traders from the Officer's view
        $bookings = Booking::with(['stall', 'user'])
            ->where('status', 'confirmed')
            ->where('end_time', '>=', now()) 
            ->latest()
            ->get();

        // 📊 Counts for dashboard statistics
        $lettersCount = Violation::whereNotNull('final_letter')->count();
        $violationsCount = Violation::count();
        $tradersCount = User::where('role', 'trader')->count();
        $stallsCount = Stall::count();

        return view('officer.dashboard', compact(
            'bookings',
            'lettersCount',
            'violationsCount',
            'tradersCount',
            'stallsCount'
        ));
    }
}
