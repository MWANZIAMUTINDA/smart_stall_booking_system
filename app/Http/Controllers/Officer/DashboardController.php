<?php

namespace App\Http\Controllers\Officer;

use App\Http\Controllers\Controller;
use App\Models\Booking;

class DashboardController extends Controller
{
    public function index()
    {
        $bookings = Booking::with(['stall', 'user'])
            ->latest()
            ->get();

        return view('officer.dashboard', compact('bookings'));
    }
}