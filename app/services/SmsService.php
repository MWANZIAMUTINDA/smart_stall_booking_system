<?php

namespace App\Services;

use AfricasTalking\SDK\AfricasTalking;
use Illuminate\Support\Facades\Log;

/**
 * SmsService — Africa's Talking SMS integration
 *
 * Handles all outbound SMS for the Muthurwa Market platform:
 *  • OTP / verification codes (registration & resend)
 *  • Booking confirmations and cancellations
 *  • Booking renewals
 *  • M-Pesa payment success / failure alerts
 *  • Admin payment prompts
 *  • Violation notices
 *  • Account restriction alerts
 */
class SmsService
{
    protected string $username;
    protected string $apiKey;
    protected ?string $senderId;

    public function __construct()
    {
        $this->username  = config('services.africastalking.username');
        $this->apiKey    = config('services.africastalking.key');
        $this->senderId  = config('services.africastalking.sender_id');
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Core send method — normalises Kenyan numbers, logs all sends/failures
    // ─────────────────────────────────────────────────────────────────────────
    public function send(string $phone, string $message): array
    {
        $phone = $this->normalisePhone($phone);

        try {
            $AT  = new AfricasTalking($this->username, $this->apiKey);
            $sms = $AT->sms();

            $payload = [
                'to'      => $phone,
                'message' => $message,
            ];

            // Only set a sender ID if it is configured (sandbox ignores it anyway)
            if ($this->senderId) {
                $payload['from'] = $this->senderId;
            }

            $result = $sms->send($payload);

            Log::info('[SMS] Sent successfully', [
                'to'      => $phone,
                'message' => substr($message, 0, 80) . '…',
                'result'  => $result,
            ]);

            return ['success' => true, 'result' => $result];

        } catch (\Exception $e) {
            Log::error('[SMS] Send failed', [
                'to'    => $phone,
                'error' => $e->getMessage(),
            ]);

            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    // ─────────────────────────────────────────────────────────────────────────
    // 1. OTP Verification (registration + resend)
    // ─────────────────────────────────────────────────────────────────────────
    public function sendOtp(string $phone, string $otp, string $traderName = ''): array
    {
        $greeting = $traderName ? "Hello {$traderName}," : 'Hello,';

        $message = "{$greeting} Your Muthurwa Market verification code is: {$otp}. "
                 . "It expires in 10 minutes. Do NOT share this code with anyone. "
                 . "– Muthurwa Market";

        return $this->send($phone, $message);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // 2. Welcome SMS (sent after email is verified)
    // ─────────────────────────────────────────────────────────────────────────
    public function sendWelcome(string $phone, string $traderName): array
    {
        $message = "Welcome to Muthurwa Market, {$traderName}! "
                 . "Your account is now verified. You can browse and book stalls via the portal. "
                 . "Need help? Call 0710618973. – Muthurwa Market";

        return $this->send($phone, $message);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // 3. Booking Created (pending payment)
    // ─────────────────────────────────────────────────────────────────────────
    public function sendBookingCreated(string $phone, string $stallNumber, string $receiptNo, string $amount, string $startDate, string $endDate): array
    {
        $message = "MUTHURWA MARKET: Booking created for Stall #{$stallNumber}. "
                 . "Period: {$startDate} – {$endDate}. Amount: KES {$amount}. "
                 . "Ref: {$receiptNo}. Please complete M-Pesa payment to confirm. "
                 . "– Muthurwa Market";

        return $this->send($phone, $message);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // 4. Payment Confirmed
    // ─────────────────────────────────────────────────────────────────────────
    public function sendPaymentConfirmed(string $phone, string $stallNumber, string $receiptNo, string $mpesaRef, string $amount): array
    {
        $message = "MUTHURWA MARKET: ✅ Payment CONFIRMED! "
                 . "Stall #{$stallNumber} is now booked. "
                 . "Amount: KES {$amount}. Receipt: {$receiptNo}. M-Pesa Ref: {$mpesaRef}. "
                 . "Thank you! – Muthurwa Market";

        return $this->send($phone, $message);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // 5. Payment Failed
    // ─────────────────────────────────────────────────────────────────────────
    public function sendPaymentFailed(string $phone, string $stallNumber, string $receiptNo): array
    {
        $message = "MUTHURWA MARKET: ❌ Payment FAILED for Stall #{$stallNumber} (Ref: {$receiptNo}). "
                 . "Please log in and retry. If you believe this is an error, contact 0710618973. "
                 . "– Muthurwa Market";

        return $this->send($phone, $message);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // 6. Booking Renewal
    // ─────────────────────────────────────────────────────────────────────────
    public function sendBookingRenewed(string $phone, string $stallNumber, string $newEndTime): array
    {
        $message = "MUTHURWA MARKET: 🔄 Booking RENEWED! "
                 . "Stall #{$stallNumber} extended to {$newEndTime}. "
                 . "– Muthurwa Market";

        return $this->send($phone, $message);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // 7. Booking Cancelled
    // ─────────────────────────────────────────────────────────────────────────
    public function sendBookingCancelled(string $phone, string $stallNumber, string $receiptNo): array
    {
        $message = "MUTHURWA MARKET: Booking for Stall #{$stallNumber} (Ref: {$receiptNo}) has been CANCELLED. "
                 . "Contact 0710618973 if this was unexpected. – Muthurwa Market";

        return $this->send($phone, $message);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // 8. Admin Payment Prompt
    // ─────────────────────────────────────────────────────────────────────────
    public function sendPaymentPrompt(string $phone, string $traderName, string $stallNumber, string $receiptNo, string $amount): array
    {
        $message = "MUTHURWA MARKET NOTICE: Dear {$traderName}, "
                 . "your Stall #{$stallNumber} reservation (Ref: {$receiptNo}) requires payment of KES {$amount} to be confirmed. "
                 . "Log in to https://muthurwamarket.indevs.in and pay via M-Pesa now. "
                 . "– Muthurwa Market Admin";

        return $this->send($phone, $message);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // 9. Violation Notice SMS
    // ─────────────────────────────────────────────────────────────────────────
    public function sendViolationNotice(string $phone, string $traderName, string $caseId, string $violationType, string $deadline = '24 hours'): array
    {
        $message = "⚠ MUTHURWA MARKET ENFORCEMENT: Dear {$traderName}, "
                 . "a violation notice has been issued against you. "
                 . "Case: {$caseId} – {$violationType}. "
                 . "Compliance deadline: {$deadline}. "
                 . "Check your email for the full notice or contact 0710618973. "
                 . "– Market Enforcement Office";

        return $this->send($phone, $message);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // 10. Account Restriction Alert
    // ─────────────────────────────────────────────────────────────────────────
    public function sendAccountRestriction(string $phone, string $traderName, string $action, string $reason): array
    {
        $actionLabel = match ($action) {
            'warned'  => 'WARNED',
            'blocked' => 'BLOCKED',
            'banned'  => 'BANNED',
            default   => strtoupper($action),
        };

        $message = "MUTHURWA MARKET: Your account has been {$actionLabel}. "
                 . "Reason: {$reason}. "
                 . "Contact market management at 0710618973 to appeal. "
                 . "– Muthurwa Market Admin";

        return $this->send($phone, $message);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Helper: Normalise Kenyan phone numbers to +254 format
    // ─────────────────────────────────────────────────────────────────────────
    protected function normalisePhone(string $phone): string
    {
        $phone = trim($phone);

        // Already in E.164 format
        if (str_starts_with($phone, '+254')) {
            return $phone;
        }

        // 254XXXXXXXXX  → +254XXXXXXXXX
        if (str_starts_with($phone, '254') && strlen($phone) === 12) {
            return '+' . $phone;
        }

        // 07XXXXXXXX or 01XXXXXXXX → +2547XXXXXXXX / +2541XXXXXXXX
        if (str_starts_with($phone, '0') && strlen($phone) === 10) {
            return '+254' . substr($phone, 1);
        }

        // 7XXXXXXXX or 1XXXXXXXX (missing leading 0) → +2547XXXXXXXX / +2541XXXXXXXX
        if ((str_starts_with($phone, '7') || str_starts_with($phone, '1')) && strlen($phone) === 9) {
            return '+254' . $phone;
        }

        // Return as-is and let AT SDK handle the error
        return $phone;
    }
}