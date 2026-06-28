<?php

namespace App\Http\Controllers\Trader;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;
use App\Services\MpesaService;
use App\Services\SmsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MpesaController extends Controller
{
    /**
     * Initiate M-Pesa STK Push payment
     */
    public function initiatePayment(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'phone'      => 'required|string|min:10|max:15',
        ]);

        $booking = Booking::where('id', $request->booking_id)
            ->where('user_id', auth()->id())
            ->where('status', 'pending')
            ->firstOrFail();

        $booking->load('stall');
        $amount = $booking->amount;

        // Generate receipt number if not yet assigned
        if (!$booking->receipt_number) {
            $booking->receipt_number = Booking::generateReceiptNumber();
            $booking->save();
        }

        $mpesa = new MpesaService();
        $result = $mpesa->stkPush(
            $request->phone,
            $amount,
            $booking->receipt_number,
            "Stall {$booking->stall->stall_number} booking"
        );

        if ($result['success']) {
            // Create payment record
            Payment::create([
                'booking_id'          => $booking->id,
                'mpesa_transaction_id' => 'PENDING',
                'phone_number'        => $request->phone,
                'amount'              => $amount,
                'payment_status'      => 'pending',
                'payment_method'      => 'mpesa_stk',
                'daraja_checkout_id'  => $result['data']['CheckoutRequestID'] ?? null,
            ]);

            return response()->json([
                'success' => true,
                'message' => $result['message'],
                'checkout_request_id' => $result['data']['CheckoutRequestID'] ?? null,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => $result['message'],
        ], 422);
    }

    /**
     * M-Pesa callback endpoint (called by Safaricom)
     */
    public function callback(Request $request)
    {
        Log::info('M-Pesa Callback Received', ['data' => $request->all()]);

        $callbackData = $request->input('Body.stkCallback', []);
        $resultCode = $callbackData['ResultCode'] ?? -1;
        $checkoutRequestId = $callbackData['CheckoutRequestID'] ?? null;

        if (!$checkoutRequestId) {
            Log::error('M-Pesa Callback: Missing CheckoutRequestID');
            return response()->json(['ResultCode' => 1, 'ResultDesc' => 'Invalid callback']);
        }

        $payment = Payment::where('daraja_checkout_id', $checkoutRequestId)->first();

        if (!$payment) {
            Log::error('M-Pesa Callback: Payment not found', ['checkout_id' => $checkoutRequestId]);
            return response()->json(['ResultCode' => 1, 'ResultDesc' => 'Payment not found']);
        }

        // Store raw callback data
        $payment->daraja_callback_data = $callbackData;

        if ($resultCode == 0) {
            // SUCCESS: Payment went through
            $callbackMetadata = $callbackData['CallbackMetadata']['Item'] ?? [];
            $mpesaReceiptNumber = null;

            foreach ($callbackMetadata as $item) {
                if ($item['Name'] === 'MpesaReceiptNumber') {
                    $mpesaReceiptNumber = $item['Value'];
                }
            }

            $payment->payment_status = 'success';
            $payment->mpesa_transaction_id = $mpesaReceiptNumber ?? 'SUCCESS';
            $payment->payment_time = now();
            $payment->confirmed_at = now();
            $payment->save();

            // Confirm the booking
            $booking = $payment->booking;
            $booking->update([
                'status'         => 'confirmed',
                'payment_status' => 'paid',
            ]);

            // Lock the stall
            $booking->stall->update(['status' => 'booked']);

            // Send SMS notification
            try {
                $sms = new SmsService();
                $sms->sendPaymentConfirmed(
                    $payment->phone_number,
                    $booking->stall->stall_number,
                    $booking->receipt_number,
                    $mpesaReceiptNumber ?? 'N/A',
                    number_format($payment->amount, 0)
                );
            } catch (\Exception $e) {
                Log::warning('[SMS] Payment-confirmed SMS failed', ['error' => $e->getMessage()]);
            }

            Log::info('M-Pesa Payment Success', ['booking_id' => $booking->id, 'receipt' => $mpesaReceiptNumber]);
        } else {
            // FAILED: Payment rejected or cancelled
            $payment->payment_status = 'failed';
            $payment->save();

            $booking = $payment->booking;
            $booking->update(['payment_status' => 'failed']);

            // Send payment-failed SMS
            try {
                $sms = new SmsService();
                $sms->sendPaymentFailed(
                    $payment->phone_number,
                    $booking->stall->stall_number,
                    $booking->receipt_number
                );
            } catch (\Exception $e) {
                Log::warning('[SMS] Payment-failed SMS failed', ['error' => $e->getMessage()]);
            }

            Log::info('M-Pesa Payment Failed', ['result_code' => $resultCode, 'description' => $callbackData['ResultDesc'] ?? '']);
        }

        return response()->json(['ResultCode' => 0, 'ResultDesc' => 'Accepted']);
    }

    /**
     * Check payment status (AJAX polling endpoint)
     */
    public function checkStatus($bookingId)
    {
        $booking = Booking::where('id', $bookingId)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $latestPayment = $booking->payments()->latest()->first();

        return response()->json([
            'booking_status'  => $booking->status,
            'payment_status'  => $booking->payment_status,
            'receipt_number'  => $booking->receipt_number,
            'mpesa_ref'       => $latestPayment?->mpesa_transaction_id,
            'payment_method'  => $latestPayment?->payment_method,
        ]);
    }

    /**
     * Simulate a successful payment (for development/testing only)
     */
    public function simulateSuccess(Request $request)
    {
        if (app()->environment('production')) {
            abort(403);
        }

        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
        ]);

        $booking = Booking::where('id', $request->booking_id)
            ->where('user_id', auth()->id())
            ->where('status', 'pending')
            ->firstOrFail();

        // Generate receipt number if missing
        if (!$booking->receipt_number) {
            $booking->receipt_number = Booking::generateReceiptNumber();
        }

        // Create a simulated payment record
        Payment::create([
            'booking_id'           => $booking->id,
            'mpesa_transaction_id' => 'SIM' . strtoupper(substr(md5(now()), 0, 8)),
            'phone_number'         => auth()->user()->phone_number ?? '0700000000',
            'amount'               => $booking->amount,
            'payment_status'       => 'success',
            'payment_method'       => 'mpesa_stk',
            'daraja_checkout_id'   => 'SIM_' . time(),
            'payment_time'         => now(),
            'confirmed_at'         => now(),
        ]);

        // Confirm the booking
        $booking->update([
            'status'         => 'confirmed',
            'payment_status' => 'paid',
        ]);

        // Lock the stall
        $booking->stall->update(['status' => 'booked']);

        // Send payment-confirmed SMS
        try {
            $sms = new SmsService();
            $sms->sendPaymentConfirmed(
                auth()->user()->phone_number,
                $booking->stall->stall_number,
                $booking->receipt_number,
                'SIM-' . strtoupper(substr(md5(now()), 0, 6)),
                number_format($booking->amount, 0)
            );
        } catch (\Exception $e) {
            Log::warning('[SMS] Simulate-payment SMS failed', ['error' => $e->getMessage()]);
        }

        return response()->json([
            'success'        => true,
            'message'        => 'Payment simulated successfully!',
            'booking_status' => 'confirmed',
            'payment_status' => 'paid',
            'receipt_number' => $booking->receipt_number,
        ]);
    }
}
