<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Stall;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // --- Existing Stats ---
        $totalStalls = Stall::count();
        $availableStalls = Stall::where('status', 'available')->count();
        $bookedStalls = Stall::where('status', 'booked')->count();
        $totalBookings = Booking::count();
        $totalTraders = User::where('role', 'trader')->count();

        // --- Fetch All Traders for Management ---
        $traders = User::where('role', 'trader')->latest()->get();

        // --- Existing Heatmap & Trend Stats ---
        $zoneStats = Booking::join('stalls', 'bookings.stall_id', '=', 'stalls.id')
            ->select('stalls.zone', DB::raw('count(*) as count'), DB::raw('sum(stalls.price) as revenue'))
            ->groupBy('stalls.zone')
            ->get();

        $revenueTrend = Booking::join('stalls', 'bookings.stall_id', '=', 'stalls.id')
            ->select(DB::raw('DATE(bookings.created_at) as date'), DB::raw('sum(stalls.price) as daily_total'))
            ->where('bookings.created_at', '>=', Carbon::now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get();

        $peakHours = Booking::select(DB::raw('HOUR(created_at) as hour'), DB::raw('count(*) as count'))
            ->groupBy('hour')
            ->orderBy('hour', 'ASC')
            ->get();

        $recentBookings = Booking::with(['stall', 'user'])->latest()->get();

        return view('admin.dashboard', compact(
            'totalStalls',
            'availableStalls',
            'bookedStalls',
            'totalBookings',
            'totalTraders',
            'recentBookings',
            'zoneStats',
            'revenueTrend',
            'peakHours',
            'traders'
        ));
    }

    /**
     * Show all currently booked stalls (active bookings)
     */
    public function bookedStalls()
    {
        $bookedStalls = Booking::with(['stall', 'user'])
            ->whereHas('stall', function ($query) {
                $query->where('status', 'booked');
            })
            ->latest()
            ->get();

        return view('admin.booked-stalls', compact('bookedStalls'));
    }

    /**
     * Show full booking history of a specific trader
     */
    public function traderHistory(User $user)
    {
        $history = Booking::with('stall')
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        return view('admin.trader-history', compact('user', 'history'));
    }

    /**
     * Logic to act on a Trader (Warn, Block, Ban)
     */
    public function updateRestriction(Request $request, User $user)
    {
        $request->validate([
            'action' => 'required|in:none,warned,blocked,banned',
            'reason' => 'required|string|max:255'
        ]);

        $user->update([
            'account_restriction' => $request->action,
            'restriction_reason' => $request->reason
        ]);

        return back()->with(
            'success',
            "Trader {$user->name} status updated to: " . ucfirst($request->action)
        );
    }

    /**
     * Show the manual assignment page for admin
     */
    public function createAssignment()
    {
        $stalls = Stall::where('status', 'available')->get();
        $traders = User::where('role', 'trader')->latest()->get();

        return view('admin.assignments.create', compact('stalls', 'traders'));
    }

    /**
     * Logic for Admin to manually assign a stall to a specific Trader
     */
    public function assignStall(Request $request)
    {
        $request->validate([
            'stall_id' => 'required|exists:stalls,id',
            'user_id' => 'required|exists:users,id',
            'start_time' => 'required|date|after_or_equal:today',
            'end_time' => 'required|date|after:start_time',
        ]);

        // Ensure the selected user is actually a trader
        $trader = User::where('id', $request->user_id)
            ->where('role', 'trader')
            ->firstOrFail();

        // Check if stall is already booked
        $stall = Stall::findOrFail($request->stall_id);

        if ($stall->status === 'booked') {
            return back()->with('error', 'This stall is already occupied.');
        }

        // Create the booking for the TRADER, including booking_date
        Booking::create([
            'stall_id'     => $stall->id,
            'user_id'      => $trader->id,
            'start_time'   => $request->start_time,
            'end_time'     => $request->end_time,
            'status'       => 'confirmed',
            'booking_date' => now()->toDateString(),
        ]);

        // Update stall status
        $stall->update(['status' => 'booked']);

        return back()->with(
            'success',
            "Stall #{$stall->stall_number} successfully assigned to {$trader->name}."
        );
    }
}