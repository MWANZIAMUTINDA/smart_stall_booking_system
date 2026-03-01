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

    // Store booking
    public function store(Request $request)
    {
        $request->validate([
            'stall_id' => 'required|exists:stalls,id',
            'booking_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
        ]);

        $stall = Stall::where('id', $request->stall_id)
                      ->where('status', 'available')
                      ->first();

        if (!$stall) {
            return redirect()->back()
                ->with('error', 'This stall is no longer available.');
        }

        $user = auth()->user();

        // Create booking
        $booking = Booking::create([
            'user_id' => $user->id,
            'stall_id' => $stall->id,
            'booking_date' => $request->booking_date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'status' => 'confirmed'
        ]);

        // Mark stall as booked
        $stall->update([
            'status' => 'booked'
        ]);

        // Send SMS Notification
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

    /**
     * 🔄 Renew / Extend Booking
     */
    public function renew($id)
    {
        $booking = Booking::where('id', $id)
                          ->where('user_id', auth()->id())
                          ->firstOrFail();

        // Check if the stall is still associated with this booking
        if (!$booking->stall) {
            return redirect()->back()->with('error', 'Stall information missing.');
        }

        // Extend the end_time by 24 hours
        // Note: Carbon handles the math automatically because we casted the date in the Model
        $newEndTime = $booking->end_time->addHours(24);

        $booking->update([
            'end_time' => $newEndTime,
            'status' => 'confirmed' // Ensure it's active
        ]);

        // Ensure the stall status is 'booked' (not 'available')
        $booking->stall->update(['status' => 'booked']);

        // Optional: Send Renewal SMS
        $sms = new SmsService();
        $message = "Your booking for stall {$booking->stall->stall_number} has been extended to " . $newEndTime->format('d M, H:i') . ".";
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
        $booking->update([
            'status' => 'cancelled'
        ]);

        // Make stall available again
        $booking->stall->update([
            'status' => 'available'
        ]);

        return redirect()->back()
            ->with('success', 'Booking cancelled successfully.');
    }
}
