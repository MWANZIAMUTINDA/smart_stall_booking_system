<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Stall;
use App\Models\Booking;
use App\Models\User;
use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Show the main admin dashboard
     */
    public function index()
    {
        // ============================================
        // ✅ AUTO-CLEANUP LOGIC (Using System Time)
        // ============================================

        // 1️⃣ Find bookings that have expired but are still marked confirmed
        $expiredBookings = Booking::expiredButStillConfirmed()->get();

        if ($expiredBookings->isNotEmpty()) {
            $stallIdsToRelease = $expiredBookings->pluck('stall_id');

            // 2️⃣ Release those stalls
            Stall::whereIn('id', $stallIdsToRelease)->update(['status' => 'available']);

            // 3️⃣ Mark bookings as expired
            Booking::whereIn('id', $expiredBookings->pluck('id'))->update(['status' => 'expired']);
        }

        // ============================================
        // 📊 UPDATED STATS (After Cleanup)
        // ============================================
        $totalStalls      = Stall::count();
        $availableStalls  = Stall::where('status', 'available')->count();
        $bookedStalls     = Stall::where('status', 'booked')->count();
        $totalBookings    = Booking::count();
        $totalTraders     = User::where('role', 'trader')->count();

        $traders = User::where('role', 'trader')->latest()->get();

        // ============================================
        // 🔥 SHOW ALL CONFIRMED BOOKINGS (Current & Future)
        // ============================================
        // Changed from currentOccupants() to show ALL confirmed bookings that haven't ended yet
        $recentBookings = Booking::with(['stall', 'user'])
            ->where('status', 'confirmed')
            ->where('end_time', '>=', now()) 
            ->latest()
            ->get();

        // ============================================
        // 📈 Heatmap & Revenue Trend
        // ============================================
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

        return view('admin.dashboard', compact(
            'totalStalls', 'availableStalls', 'bookedStalls', 'totalBookings',
            'totalTraders', 'recentBookings', 'zoneStats', 'revenueTrend', 'peakHours', 'traders'
        ));
    }

    /**
     * Show all confirmed bookings (Active & Upcoming)
     */
    public function bookedStalls()
    {
        // Changed to show any booking that is confirmed and has not ended.
        $bookedStalls = Booking::with(['stall', 'user'])
            ->where('status', 'confirmed')
            ->where('end_time', '>=', now())
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
     * Update trader restriction (Warn, Block, Ban)
     */
    public function updateRestriction(Request $request, User $user)
    {
        $request->validate([
            'action' => 'required|in:none,warned,blocked,banned',
            'reason' => 'required|string|max:255'
        ]);

        $user->update([
            'account_restriction' => $request->action,
            'restriction_reason'  => $request->reason
        ]);

        return back()->with('success', "Trader {$user->name} status updated to: " . ucfirst($request->action));
    }

    /**
     * Show manual assignment page
     */
    public function createAssignment()
    {
        $stalls = Stall::where('status', 'available')->get();
        $traders = User::where('role', 'trader')->latest()->get();

        return view('admin.assignments.create', compact('stalls', 'traders'));
    }

    /**
     * Assign stall manually to a trader
     */
    public function assignStall(Request $request)
    {
        $request->validate([
            'stall_id'   => 'required|exists:stalls,id',
            'user_id'    => 'required|exists:users,id',
            'start_time' => 'required|date|after_or_equal:today',
            'end_time'   => 'required|date|after:start_time',
        ]);

        $trader = User::where('id', $request->user_id)->where('role', 'trader')->firstOrFail();
        $stall = Stall::findOrFail($request->stall_id);

        if ($stall->status === 'booked') {
            return back()->with('error', 'This stall is already occupied.');
        }

        Booking::create([
            'stall_id'     => $stall->id,
            'user_id'      => $trader->id,
            'start_time'   => $request->start_time,
            'end_time'     => $request->end_time,
            'status'       => 'confirmed',
            'booking_date' => now()->toDateString(),
        ]);

        $stall->update(['status' => 'booked']);

        return back()->with('success', "Stall #{$stall->stall_number} successfully assigned to {$trader->name}.");
    }

    public function feedbackIndex()
    {
        $feedbacks = Feedback::with('user')->latest()->get();
        return view('admin.feedback.index', compact('feedbacks'));
    }

    public function resolveFeedback($id)
    {
        $feedback = Feedback::findOrFail($id);
        $feedback->update(['status' => 'resolved']);

        return back()->with('success', 'Feedback marked as resolved.');
    }
}
