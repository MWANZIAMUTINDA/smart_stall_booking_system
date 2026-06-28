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

        // 2️⃣ FETCH ALL STALLS with their active/future bookings for smart availability
        $allStalls = Stall::with(['bookings' => function($q) {
                $q->whereIn('status', ['confirmed', 'pending'])
                  ->where('end_time', '>', now())
                  ->orderBy('start_time', 'asc');
            }, 'bookings.user'])
            ->get();

        // 3️⃣ Build occupancy data for EVERY stall using smart availability
        $occupancyData = $allStalls->map(function ($stall) {
            $now = now();
            $availability = $stall->getSmartAvailability();

            // Find the current confirmed booking (officer needs to see who is there)
            $currentBooking = $stall->bookings->first(function ($booking) use ($now) {
                return $booking->start_time <= $now && $booking->end_time >= $now;
            });

            // Find all future bookings
            $futureBookings = $stall->bookings->filter(function ($booking) use ($now) {
                return $booking->start_time > $now;
            });

            // Find the NEXT occupant who is NOT the current user (if current exists)
            $nextBooking = $futureBookings->first(function ($booking) use ($currentBooking) {
                if (!$currentBooking) return true;
                return $booking->user_id !== $currentBooking->user_id;
            });

            // Check if there's an immediate renewal by same user
            $isRenewing = $futureBookings->first(function ($booking) use ($currentBooking) {
                return $currentBooking && $booking->user_id === $currentBooking->user_id;
            });

            return (object) [
                'stall'           => $stall,
                'current_booking' => $currentBooking,
                'next_booking'    => $nextBooking,
                'is_renewing'     => $isRenewing,
                'availability'    => $availability,
            ];
        });

        // 📊 Counts for dashboard statistics
        $lettersCount = Violation::whereNotNull('final_letter')->count();
        $violationsCount = Violation::count();
        $tradersCount = User::where('role', 'trader')->count();
        $stallsCount = Stall::count();

        // Smart availability counts
        $occupiedCount = $occupancyData->where('availability.status', 'occupied')->count();
        $bookedSoonCount = $occupancyData->where('availability.status', 'booked_soon')->count();
        $availableCount = $occupancyData->filter(fn($d) => $d->availability->can_book)->count();

        return view('officer.dashboard', compact(
            'occupancyData',
            'lettersCount',
            'violationsCount',
            'tradersCount',
            'stallsCount',
            'occupiedCount',
            'bookedSoonCount',
            'availableCount'
        ));
    }
}
