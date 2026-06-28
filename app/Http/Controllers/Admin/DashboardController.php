<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Stall;
use App\Models\Booking;
use App\Models\User;
use App\Models\Feedback;
use App\Services\SmsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
        // 📊 SMART STATS (After Cleanup)
        // ============================================
        $totalStalls = Stall::count();

        // Smart availability counts — use the engine instead of raw status
        $allStalls = Stall::with(['bookings' => function($q) {
            $q->whereIn('status', ['confirmed', 'pending'])
              ->where('end_time', '>', now())
              ->orderBy('start_time', 'asc');
        }])->get();

        $smartStats = $allStalls->map(fn($s) => $s->getSmartAvailability());
        $availableStalls = $smartStats->where('can_book', true)->count();
        $occupiedStalls  = $smartStats->where('status', 'occupied')->count();
        $bookedSoonStalls = $smartStats->where('status', 'booked_soon')->count();
        $bookedStalls    = $occupiedStalls + $bookedSoonStalls;
        $blockedStalls   = $smartStats->where('status', 'blocked')->count();

        $totalBookings    = Booking::count();
        $totalTraders     = User::where('role', 'trader')->count();

        $traders = User::where('role', 'trader')->latest()->get();

        // ============================================
        // 🔥 SHOW CONFIRMED + PENDING bookings (admin-assigned awaiting payment)
        // ============================================
        $recentBookings = Booking::with(['stall', 'user', 'bookedByAdmin'])
            ->whereIn('status', ['confirmed', 'pending'])
            ->where('end_time', '>=', now())
            ->orderByRaw("FIELD(status,'pending','confirmed')")   // pending first
            ->orderBy('created_at', 'desc')
            ->get();

        // Pending bookings that still need a payment prompt
        $pendingBookings = $recentBookings->where('status', 'pending');

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
            'totalTraders', 'recentBookings', 'pendingBookings',
            'zoneStats', 'revenueTrend', 'peakHours', 'traders',
            'occupiedStalls', 'bookedSoonStalls', 'blockedStalls'
        ));
    }

    /**
     * Show all confirmed bookings (Active & Upcoming)
     */
    public function bookedStalls()
    {
        // Show confirmed (active) AND pending (awaiting payment) bookings.
        // Pending = admin-assigned, trader has not yet paid.
        $bookedStalls = Booking::with(['stall', 'user', 'bookedByAdmin'])
            ->whereIn('status', ['confirmed', 'pending'])
            ->where('end_time', '>=', now())
            ->orderByRaw("FIELD(status, 'pending', 'confirmed')")  // pending first
            ->orderBy('created_at', 'desc')
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

        // Send SMS alert (only for restrictive actions, not when lifting restrictions)
        if ($request->action !== 'none') {
            try {
                $sms = new SmsService();
                $sms->sendAccountRestriction(
                    $user->phone_number,
                    $user->name,
                    $request->action,
                    $request->reason
                );
                Log::info('[SMS] Account restriction SMS sent', [
                    'trader' => $user->name,
                    'action' => $request->action,
                ]);
            } catch (\Exception $e) {
                Log::warning('[SMS] Restriction SMS failed', ['error' => $e->getMessage()]);
            }
        }

        return back()->with('success', "Trader {$user->name} status updated to: " . ucfirst($request->action));
    }

    /**
     * Show manual assignment page — uses smart availability
     */
    public function createAssignment()
    {
        // Fetch ALL stalls with bookings for smart availability check
        $allStalls = Stall::with(['bookings' => function($q) {
            $q->whereIn('status', ['confirmed', 'pending'])
              ->where('end_time', '>', now())
              ->orderBy('start_time', 'asc');
        }])->get();

        // Only show stalls that are currently bookable
        $stalls = $allStalls->filter(fn($s) => $s->getSmartAvailability()->can_book);

        // Also pass all stalls for the "all stalls overview" section
        $allStallsWithAvailability = $allStalls->map(function($s) {
            return (object) [
                'stall'        => $s,
                'availability' => $s->getSmartAvailability(),
            ];
        });

        $traders = User::where('role', 'trader')->latest()->get();

        return view('admin.assignments.create', compact('stalls', 'traders', 'allStallsWithAvailability'));
    }

    /**
     * Assign stall manually to a trader — with smart buffer enforcement
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
        $stall = Stall::with(['bookings' => function($q) {
            $q->whereIn('status', ['confirmed', 'pending'])
              ->where('end_time', '>', now())
              ->orderBy('start_time', 'asc');
        }])->findOrFail($request->stall_id);

        // 1. Smart availability check
        $availability = $stall->getSmartAvailability();
        if (!$availability->can_book) {
            return back()->with('error', 'This stall is not currently available for booking. ' . $availability->message);
        }

        // 2. Check if the STALL is available for this window (with buffer)
        if (!$stall->isAvailableForTimeSlot($request->start_time, $request->end_time)) {
            return back()->with('error', 'This stall already has a booking that overlaps or falls within the ' . Stall::BUFFER_HOURS . '-hour preparation buffer.');
        }

        // 3. Max end time check
        $maxEnd = $stall->getMaxBookingEndTime();
        $proposedEnd = Carbon::parse($request->end_time);
        if ($maxEnd && $proposedEnd->gt($maxEnd)) {
            return back()->with('error', "Booking cannot extend past {$maxEnd->format('d M Y, H:i')} due to the " . Stall::BUFFER_HOURS . "-hour buffer before the next reservation.");
        }

        // 4. Check if the TRADER is already busy at another stall during this time
        if ($trader->isBusyDuring($request->start_time, $request->end_time)) {
            return back()->with('error', "{$trader->name} already has another active or pending booking during this period. Traders cannot occupy multiple stalls simultaneously.");
        }

        // Generate receipt number for the booking
        $receiptNumber = Booking::generateReceiptNumber();

        Booking::create([
            'stall_id'            => $stall->id,
            'user_id'             => $trader->id,
            'booked_by_admin_id'  => auth()->id(),
            'start_time'          => $request->start_time,
            'end_time'            => $request->end_time,
            'status'              => 'pending',          // awaiting trader payment
            'payment_status'      => 'unpaid',
            'booking_date'        => now()->toDateString(),
            'receipt_number'      => $receiptNumber,
            'admin_notes'         => $request->admin_notes,
        ]);

        // Don't lock the stall yet — it becomes 'booked' only after M-Pesa payment
        return back()->with('success', "Booking created for {$trader->name} on Stall #{$stall->stall_number}. Awaiting trader M-Pesa payment.");
    }

    /**
     * Prompt a trader to pay for their manually-assigned booking.
     * Records the timestamp + which admin sent the prompt, then fires an SMS.
     */
    public function promptPayment(Request $request, Booking $booking)
    {
        // Only pending bookings can be prompted
        if ($booking->status !== 'pending') {
            return back()->with('error', 'This booking is not awaiting payment.');
        }

        // Stamp the prompt time (always update so admin can re-send)
        $booking->update([
            'payment_prompt_sent_at' => now(),
            'booked_by_admin_id'     => auth()->id(), // keep latest admin who acted
        ]);

        // Send SMS via SmsService
        try {
            $sms    = new SmsService();
            $stall  = $booking->stall;
            $trader = $booking->user;
            $amount = number_format($stall->price, 0);
            $receipt = $booking->receipt_number;

            $sms->sendPaymentPrompt(
                $trader->phone_number,
                $trader->name,
                $stall->stall_number,
                $receipt,
                $amount
            );

            Log::info('[SMS] Admin payment prompt sent', [
                'booking_id' => $booking->id,
                'trader'     => $trader->name,
                'admin'      => auth()->user()->name,
            ]);

            return back()->with('success', "Payment prompt sent to {$trader->name} ({$trader->phone_number}) via SMS.");
        } catch (\Exception $e) {
            Log::warning('[SMS] Payment prompt SMS failed', ['error' => $e->getMessage()]);
            return back()->with('warning', "Payment prompt recorded but SMS delivery failed: {$e->getMessage()}");
        }
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
