<?php

namespace App\Http\Controllers\Trader;

use App\Http\Controllers\Controller;
use App\Models\Stall;
use App\Models\Booking;
use App\Services\SmsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class BookingController extends Controller
{
    // Show booking form
    public function create($stallId)
    {
        $stall = Stall::with(['bookings' => function($q) {
            $q->whereIn('status', ['confirmed', 'pending'])
              ->where('end_time', '>', now())
              ->orderBy('start_time', 'asc');
        }])->findOrFail($stallId);

        // Run cleanup if it's currently marked booked but should be available
        if ($stall->status === 'booked' && !$stall->hasActiveBooking()) {
            $stall->update(['status' => 'available']);
            $stall->bookings()
                ->where('status', 'confirmed')
                ->where('end_time', '<', now())
                ->update(['status' => 'expired']);
        }

        // 🔒 ADMIN BLOCK GUARD — highest priority check
        if ($stall->isBlocked()) {
            return redirect()->route('trader.stalls.index')
                ->with('error', 'Stall #' . $stall->stall_number . ' is currently unavailable: ' . ($stall->blocked_reason ?? 'Temporarily blocked by management.'));
        }

        // 🧠 SMART AVAILABILITY CHECK
        $availability = $stall->getSmartAvailability();

        // If the stall is not bookable (occupied or within buffer window), redirect back
        if (!$availability->can_book) {
            return redirect()->route('trader.stalls.index')
                ->with('error', 'This stall is currently not available for booking. ' . $availability->message);
        }

        // Get the max allowed end time (if restricted by a future booking)
        $maxEndTime = $stall->getMaxBookingEndTime();

        // Fetch upcoming confirmed bookings to show on the form (privacy-safe: no user info)
        $upcomingBookings = $stall->bookings()
            ->whereIn('status', ['confirmed', 'pending'])
            ->where('start_time', '>', now())
            ->orderBy('start_time', 'asc')
            ->get(['id', 'start_time', 'end_time', 'status']); // Only select non-private columns

        return view('trader.bookings.create', compact('stall', 'upcomingBookings', 'availability', 'maxEndTime'));
    }

    // =====================================================
    // ✅ UPDATED STORE METHOD — Duration-based pricing
    // =====================================================
    public function store(Request $request)
    {
        $request->validate([
            'stall_id'      => 'required|exists:stalls,id',
            'booking_date'  => 'required|date',
            'start_time'    => 'required|date',
            'duration_days' => 'required|integer|min:1|max:365',
            'amount'        => 'required|numeric|min:1',
        ]);

        $days   = (int) $request->duration_days;
        $startTime = Carbon::parse($request->start_time);
        $endTime   = $startTime->copy()->addDays($days);

        // ── Server-side amount validation ───────────────────────────────
        // Preset exact prices (encourage longer bookings)
        $presetPrices = [1 => 1, 7 => 6, 14 => 12, 21 => 18, 30 => 23];
        if (isset($presetPrices[$days])) {
            $expectedAmount = $presetPrices[$days];
        } else {
            // Formula: every 7 days → 1 free day
            $expectedAmount = $days - (int) floor($days / 7);
        }

        if ((float) $request->amount !== (float) $expectedAmount) {
            return redirect()->back()
                ->with('error', "Amount mismatch. Expected KES {$expectedAmount} for {$days} days.");
        }

        // 1️⃣ SAFETY CLEANUP: Release any expired confirmed bookings
        $expiredBooking = Booking::where('stall_id', $request->stall_id)
            ->where('status', 'confirmed')
            ->where('end_time', '<', now())
            ->first();

        if ($expiredBooking) {
            $expiredBooking->update(['status' => 'expired']);
            Stall::where('id', $request->stall_id)->update(['status' => 'available']);
        }

        $stall = Stall::with(['bookings' => function($q) {
            $q->whereIn('status', ['confirmed', 'pending'])
              ->where('end_time', '>', now())
              ->orderBy('start_time', 'asc');
        }])->findOrFail($request->stall_id);

        // 2️⃣ ADMIN BLOCK GUARD
        if ($stall->isBlocked()) {
            return redirect()->back()
                ->with('error', 'Stall #' . $stall->stall_number . ' is currently unavailable: ' . ($stall->blocked_reason ?? 'Temporarily blocked by management.'));
        }

        // 3️⃣ SMART AVAILABILITY CHECK
        $availability = $stall->getSmartAvailability();
        if (!$availability->can_book) {
            return redirect()->back()
                ->with('error', 'This stall is no longer available. ' . $availability->message);
        }

        // 3️⃣ OVERLAP + BUFFER CHECK
        if (!$stall->isAvailableForTimeSlot($startTime, $endTime)) {
            return redirect()->back()
                ->with('error', 'Your selected time slot conflicts with an existing booking or falls within the ' . Stall::BUFFER_HOURS . '-hour preparation buffer.');
        }

        // 4️⃣ MAX END TIME CHECK
        $maxEndTime = $stall->getMaxBookingEndTime();
        if ($maxEndTime && $endTime->gt($maxEndTime)) {
            return redirect()->back()
                ->with('error', 'Your booking cannot extend past ' . $maxEndTime->format('d M Y, H:i') . ' due to an upcoming reservation.');
        }

        $user = auth()->user();

        // 5️⃣ USER BUSY CHECK
        if ($user->isBusyDuring($startTime, $endTime)) {
            return redirect()->back()
                ->with('error', 'You already have another active or pending reservation during this period.');
        }

        // 6️⃣ Cancel stale pending bookings for this user on this stall
        Booking::where('user_id', $user->id)
            ->where('stall_id', $stall->id)
            ->where('status', 'pending')
            ->where('payment_status', 'unpaid')
            ->update(['status' => 'cancelled']);

        // 7️⃣ Create PENDING booking with duration + calculated amount
        $booking = Booking::create([
            'user_id'        => $user->id,
            'stall_id'       => $stall->id,
            'booking_date'   => $request->booking_date,
            'start_time'     => $startTime,
            'end_time'       => $endTime,
            'duration_days'  => $days,
            'amount'         => $expectedAmount,
            'status'         => 'pending',
            'payment_status' => 'unpaid',
            'receipt_number' => Booking::generateReceiptNumber(),
        ]);

        // 8️⃣ Send booking-created SMS
        try {
            $sms = new SmsService();
            $sms->sendBookingCreated(
                $user->phone_number,
                $stall->stall_number,
                $booking->receipt_number,
                number_format($expectedAmount, 0),
                $startTime->format('d M Y'),
                $endTime->format('d M Y')
            );
        } catch (\Exception $e) {
            Log::warning('[SMS] Booking-created SMS failed', ['error' => $e->getMessage()]);
        }

        // 9️⃣ Redirect to payment page
        return redirect()->route('trader.bookings.pay', $booking->id)
            ->with('info', 'Booking created. Please complete payment to confirm your reservation.');
    }


    // =====================================================
    // 💳 PAYMENT PAGE
    // =====================================================
    public function pay($id)
    {
        $booking = Booking::where('id', $id)
            ->where('user_id', auth()->id())
            ->with('stall')
            ->firstOrFail();

        // If already paid, redirect to bookings
        if ($booking->isPaid()) {
            return redirect()->route('trader.bookings.index')
                ->with('success', 'This booking is already paid!');
        }

        return view('trader.bookings.pay', compact('booking'));
    }

    // Show trader bookings
    public function index()
    {
        $bookings = Booking::where('user_id', auth()->id())
                            ->with(['stall', 'payments'])
                            ->latest()
                            ->get();

        return view('trader.bookings.index', compact('bookings'));
    }

    // =====================================================
    // 🧾 RECEIPT DOWNLOAD (PDF)
    // =====================================================
    public function receipt($id)
    {
        $booking = Booking::where('id', $id)
            ->where('user_id', auth()->id())
            ->where('payment_status', 'paid')
            ->with(['stall', 'user', 'payments' => function ($q) {
                $q->where('payment_status', 'success')->latest();
            }])
            ->firstOrFail();

        $payment = $booking->payments->first();

        $pdf = Pdf::loadView('trader.bookings.receipt', compact('booking', 'payment'));
        $pdf->setPaper('A5', 'portrait');

        return $pdf->download("receipt-{$booking->receipt_number}.pdf");
    }

    // =====================================================
    // 🔄 UPDATED RENEW METHOD (Smart & Safe Renewal + Buffer Check)
    // =====================================================
    public function renew($id)
    {
        $booking = Booking::where('id', $id)
                          ->where('user_id', auth()->id())
                          ->firstOrFail();

        // 1️⃣ Prevent renewal if stall already taken by someone else
        $isStallTaken = Booking::where('stall_id', $booking->stall_id)
            ->where('id', '!=', $id)
            ->where('status', 'confirmed')
            ->where('payment_status', 'paid')
            ->where('end_time', '>', now())
            ->exists();

        if ($isStallTaken) {
            return redirect()->back()
                ->with('error', 'Too late! This stall has already been booked by someone else.');
        }

        // 2️⃣ Smart Time Extension
        // If expired → extend from NOW
        // If still active → extend from existing end_time
        $baseTime = $booking->end_time->isPast()
            ? now()
            : $booking->end_time;

        $newEndTime = $baseTime->copy()->addHours(24);

        // 3️⃣ Buffer Check: Ensure renewal doesn't conflict with upcoming bookings
        $stall = Stall::with(['bookings' => function($q) use ($id) {
            $q->whereIn('status', ['confirmed', 'pending'])
              ->where('id', '!=', $id)
              ->where('end_time', '>', now())
              ->orderBy('start_time', 'asc');
        }])->find($booking->stall_id);

        $maxEnd = $stall->getMaxBookingEndTime();
        if ($maxEnd && $newEndTime->gt($maxEnd)) {
            return redirect()->back()
                ->with('error', 'Cannot renew — another booking starts within the ' . Stall::BUFFER_HOURS . '-hour buffer window. Maximum extension: ' . $maxEnd->format('d M, H:i'));
        }

        $booking->update([
            'end_time' => $newEndTime,
            'status'   => 'confirmed'
        ]);

        // Ensure stall remains booked
        $booking->stall->update(['status' => 'booked']);

        // 4️⃣ SMS Renewal Notification
        try {
            $sms = new SmsService();
            $sms->sendBookingRenewed(
                auth()->user()->phone_number,
                $booking->stall->stall_number,
                $newEndTime->format('d M Y, H:i')
            );
        } catch (\Exception $e) {
            Log::warning('[SMS] Renewal SMS failed', ['error' => $e->getMessage()]);
        }

        return redirect()->back()
            ->with('success', 'Stall booking renewed for another 24 hours!');
    }

    // Cancel booking
    public function cancel($id)
    {
        $booking = Booking::where('id', $id)
                          ->where('user_id', auth()->id())
                          ->with('stall')
                          ->firstOrFail();

        if ($booking->status === 'cancelled') {
            return redirect()->back()
                ->with('error', 'Booking already cancelled.');
        }

        // Update booking status
        $booking->update(['status' => 'cancelled']);

        // Release stall
        $booking->stall->update(['status' => 'available']);

        // SMS Cancellation notification
        try {
            $sms = new SmsService();
            $sms->sendBookingCancelled(
                auth()->user()->phone_number,
                $booking->stall->stall_number,
                $booking->receipt_number
            );
        } catch (\Exception $e) {
            Log::warning('[SMS] Cancellation SMS failed', ['error' => $e->getMessage()]);
        }

        return redirect()->back()
            ->with('success', 'Booking cancelled successfully.');
    }
}