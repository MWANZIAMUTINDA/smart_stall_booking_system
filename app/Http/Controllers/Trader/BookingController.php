<?php

namespace App\Http\Controllers\Trader;

use App\Http\Controllers\Controller;
use App\Models\Stall;
use App\Models\Booking;
use App\Services\SmsService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BookingController extends Controller
{
    // Show booking form
    public function create($stallId)
    {
        $stall = Stall::where('id', $stallId)
                      ->where('status', 'available')
                      ->firstOrFail();

        return view('trader.bookings.create', compact('stall'));
    }

    // =====================================================
    // ✅ UPDATED STORE METHOD (With Safety Cleanup)
    // =====================================================
    public function store(Request $request)
    {
        $request->validate([
            'stall_id'     => 'required|exists:stalls,id',
            'booking_date' => 'required|date',
            'start_time'   => 'required',
            'end_time'     => 'required',
        ]);

        // 1️⃣ SAFETY CLEANUP:
        // If previous confirmed booking expired, release stall
        $expiredBooking = Booking::where('stall_id', $request->stall_id)
            ->where('status', 'confirmed')
            ->where('end_time', '<', now())
            ->first();

        if ($expiredBooking) {
            $expiredBooking->update(['status' => 'expired']);
            Stall::where('id', $request->stall_id)
                ->update(['status' => 'available']);
        }

        // 2️⃣ Check availability AFTER cleanup
        $stall = Stall::where('id', $request->stall_id)
                      ->where('status', 'available')
                      ->first();

        if (!$stall) {
            return redirect()->back()
                ->with('error', 'This stall is currently occupied by another trader.');
        }

        $user = auth()->user();

        // 3️⃣ Create booking
        $booking = Booking::create([
            'user_id'      => $user->id,
            'stall_id'     => $stall->id,
            'booking_date' => $request->booking_date,
            'start_time'   => $request->start_time,
            'end_time'     => $request->end_time,
            'status'       => 'confirmed'
        ]);

        // 4️⃣ Mark stall as booked
        $stall->update(['status' => 'booked']);

        // 5️⃣ SMS Notification
        $sms = new SmsService();
        $message = "Your stall {$stall->stall_number} has been successfully booked.";
        $sms->send($user->phone_number, $message);

        return redirect('/trader/dashboard')
            ->with('success', 'Stall booked successfully.');
    }

    // Show trader bookings
    public function index()
    {
        $bookings = Booking::where('user_id', auth()->id())
                            ->with('stall')
                            ->latest()
                            ->get();

        return view('trader.bookings.index', compact('bookings'));
    }

    // =====================================================
    // 🔄 UPDATED RENEW METHOD (Smart & Safe Renewal)
    // =====================================================
    public function renew($id)
    {
        $booking = Booking::where('id', $id)
                          ->where('user_id', auth()->id())
                          ->firstOrFail();

        // 1️⃣ Prevent renewal if stall already taken
        $isStallTaken = Booking::where('stall_id', $booking->stall_id)
            ->where('id', '!=', $id)
            ->where('status', 'confirmed')
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

        $booking->update([
            'end_time' => $newEndTime,
            'status'   => 'confirmed'
        ]);

        // Ensure stall remains booked
        $booking->stall->update(['status' => 'booked']);

        // 3️⃣ SMS Renewal Notification
        $sms = new SmsService();
        $message = "Your booking for stall {$booking->stall->stall_number} has been extended to "
                 . $newEndTime->format('d M, H:i') . ".";

        $sms->send(auth()->user()->phone_number, $message);

        return redirect()->back()
            ->with('success', 'Stall booking renewed for another 24 hours!');
    }

    // Cancel booking
    public function cancel($id)
    {
        $booking = Booking::where('id', $id)
                          ->where('user_id', auth()->id())
                          ->firstOrFail();

        if ($booking->status === 'cancelled') {
            return redirect()->back()
                ->with('error', 'Booking already cancelled.');
        }

        // Update booking status
        $booking->update(['status' => 'cancelled']);

        // Release stall
        $booking->stall->update(['status' => 'available']);

        return redirect()->back()
            ->with('success', 'Booking cancelled successfully.');
    }
}