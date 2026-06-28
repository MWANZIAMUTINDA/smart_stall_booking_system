<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\VerificationCodeMail;
use App\Mail\WelcomeTraderMail;
use App\Models\EmailVerificationCode;
use App\Services\SmsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class EmailVerificationController extends Controller
{
    // ── Max failed attempts before code is locked ──────────────────────────
    const MAX_ATTEMPTS = 5;

    // ── Resend cooldown in seconds ─────────────────────────────────────────
    const RESEND_COOLDOWN = 120; // 2 minutes

    // ─────────────────────────────────────────────────────────────────────
    // Show the OTP entry page
    // ─────────────────────────────────────────────────────────────────────
    public function show(Request $request): View|RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('trader.dashboard'));
        }

        // Pass seconds remaining for the countdown timer
        $activeCode = EmailVerificationCode::latestActiveFor($request->user()->id);
        $secondsLeft = $activeCode ? $activeCode->secondsRemaining() : 0;

        return view('auth.verify-email', compact('secondsLeft'));
    }

    // ─────────────────────────────────────────────────────────────────────
    // Verify the submitted code
    // ─────────────────────────────────────────────────────────────────────
    public function verify(Request $request): RedirectResponse
    {
        $request->validate([
            'code' => ['required', 'string', 'size:8', 'regex:/^\d{8}$/'],
        ], [
            'code.size'  => 'The verification code must be exactly 8 digits.',
            'code.regex' => 'The verification code must contain digits only.',
        ]);

        $user = $request->user();

        // 1. Find latest active code (unused + not expired)
        $record = EmailVerificationCode::where('user_id', $user->id)
            ->whereNull('used_at')
            ->latest()
            ->first();

        // 2. No code found at all
        if (!$record) {
            return back()->withErrors(['code' => 'No active verification code found. Please request a new one.']);
        }

        // 3. Code has expired
        if ($record->isExpired()) {
            return back()->withErrors(['code' => 'Your code has expired. Please request a new one below.'])
                         ->with('show_resend', true);
        }

        // 4. Too many failed attempts — lock
        if ($record->isLocked()) {
            Log::warning("[OTP] User {$user->id} ({$user->email}) locked after max attempts. IP: {$request->ip()}");
            return back()->withErrors(['code' => 'Too many incorrect attempts. Please request a new code.'])
                         ->with('show_resend', true);
        }

        // 5. Compare hashed code (timing-safe)
        $inputHash = hash('sha256', $request->input('code'));
        if (!hash_equals($record->code_hash, $inputHash)) {
            $record->increment('attempts');
            $remaining = $record->remainingAttempts();

            Log::warning("[OTP] Failed attempt for user {$user->id}. Remaining: {$remaining}. IP: {$request->ip()}");

            return back()->withErrors([
                'code' => "Incorrect code. You have {$remaining} attempt(s) remaining.",
            ]);
        }

        // ── SUCCESS ───────────────────────────────────────────────────────

        // 6. Mark code as used (one-time use)
        $record->markUsed();

        // 7. Mark email as verified
        $user->markEmailAsVerified();

        // 8. Send the welcome email now (after verification)
        try {
            Mail::to($user->email)->send(new WelcomeTraderMail($user));
        } catch (\Exception $e) {
            Log::error("[Welcome Email] Failed for {$user->email}: " . $e->getMessage());
        }

        // 9. Send welcome SMS
        try {
            $sms = new SmsService();
            $sms->sendWelcome($user->phone_number, $user->name);
            Log::info("[Welcome SMS] Sent to {$user->phone_number} for user {$user->id}");
        } catch (\Exception $e) {
            Log::warning("[Welcome SMS] Failed for {$user->phone_number}: " . $e->getMessage());
        }

        Log::info("[OTP] Email verified successfully for user {$user->id} ({$user->email}). IP: {$request->ip()}");

        return redirect()->route('trader.dashboard')
            ->with('success', '✅ Email verified! Welcome to Muthurwa Market, ' . $user->name . '!');
    }

    // ─────────────────────────────────────────────────────────────────────
    // Resend a fresh verification code
    // ─────────────────────────────────────────────────────────────────────
    public function resend(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('trader.dashboard');
        }

        // Cooldown check — prevent spam
        $lastCode = EmailVerificationCode::latestFor($user->id);
        if ($lastCode) {
            $elapsed = $lastCode->created_at->diffInSeconds(now());
            if ($elapsed < self::RESEND_COOLDOWN) {
                $wait = self::RESEND_COOLDOWN - $elapsed;
                return back()->with('resend_error', "Please wait {$wait} seconds before requesting a new code.");
            }
        }

        // Generate and send new code
        ['code' => $code] = EmailVerificationCode::generateFor($user->id, $request->ip());

        // Resend via Email
        try {
            Mail::to($user->email)->send(new VerificationCodeMail($user, $code));
        } catch (\Exception $e) {
            Log::error("[OTP Resend Email] Failed for {$user->email}: " . $e->getMessage());
            return back()->with('resend_error', 'Failed to send email. Please try again shortly.');
        }

        // Resend via SMS
        try {
            $sms = new SmsService();
            $sms->sendOtp($user->phone_number, $code, $user->name);
            Log::info("[OTP Resend SMS] Sent to {$user->phone_number} for user {$user->id}");
        } catch (\Exception $e) {
            Log::warning("[OTP Resend SMS] Failed for {$user->phone_number}: " . $e->getMessage());
        }

        Log::info("[OTP] Code resent (email + SMS) for user {$user->id}. IP: {$request->ip()}");

        return back()->with('status', 'verification-code-sent');
    }
}
