<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\WelcomeTraderMail;
use App\Models\User;
use App\Services\SmsService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // ── 6. Input Sanitization ─────────────────────────────────────────
        $request->merge([
            'name'         => strip_tags(trim($request->name)),
            'username'     => strip_tags(trim(strtolower($request->username))),
            'email'        => strtolower(trim($request->email)),
            'phone_number' => trim($request->phone_number),
        ]);

        // Reserved usernames (§3)
        $reserved = ['admin', 'system', 'administrator', 'root', 'superuser', 'staff', 'officer', 'support', 'muthurwa'];

        // ── Validation ────────────────────────────────────────────────────
        $request->validate([

            // §1 — Name
            'name' => ['required', 'string', 'max:255'],

            // §3 — Username
            'username' => [
                'required', 'string', 'min:3', 'max:20',
                'unique:users',
                'regex:/^[a-zA-Z0-9_]+$/',             // letters, numbers, underscores only
                'not_regex:/\s/',                        // no spaces
                function ($attribute, $value, $fail) use ($reserved) {
                    if (in_array(strtolower($value), $reserved)) {
                        $fail('That username is reserved and cannot be used.');
                    }
                },
            ],

            // §2 — Email
            'email' => ['required', 'string', 'email:rfc,dns', 'max:255', 'unique:users'],

            // §4 — Phone (Kenyan format: 07XXXXXXXX or +2547XXXXXXXX)
            'phone_number' => [
                'required',
                'unique:users',
                'regex:/^(07\d{8}|\+2547\d{8})$/',
            ],

            // §1 — Password strength
            'password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols(),
                // Block common weak passwords
                'not_regex:/^(?i)(123456|password|qwerty|abc123|letmein|iloveyou|admin|welcome|monkey|dragon|pass1234)$/',
                // Not similar to name or email
                function ($attribute, $value, $fail) use ($request) {
                    $name      = strtolower($request->input('name', ''));
                    $emailPart = strtolower(explode('@', $request->input('email', '@'))[0]);
                    $pass      = strtolower($value);

                    if (strlen($name) >= 4 && str_contains($pass, $name)) {
                        $fail('Password must not contain your name.');
                        return;
                    }
                    if (strlen($emailPart) >= 4 && str_contains($pass, $emailPart)) {
                        $fail('Password must not contain your email address.');
                    }
                },
            ],

            // §7 — Terms & Conditions
            'terms' => ['accepted'],

        ], [
            // Custom error messages
            'username.regex'         => 'Username may only contain letters, numbers, and underscores (no spaces).',
            'username.not_regex'     => 'Username must not contain spaces.',
            'phone_number.regex'     => 'Phone must be a valid Kenyan number (e.g. 0712345678 or +254712345678).',
            'phone_number.unique'    => 'This phone number is already registered.',
            'password.not_regex'     => 'That password is too common. Please choose a stronger one.',
            'terms.accepted'         => 'You must accept the Terms & Conditions to create an account.',
            'email.email'            => 'Please enter a valid email address.',
        ]);

        // ── Create user ────────────────────────────────────────────────────
        $user = User::create([
            'name'         => $request->name,
            'username'     => $request->username,
            'email'        => $request->email,
            'phone_number' => $request->phone_number,
            'password'     => Hash::make($request->password),
            'role'         => 'trader',
            'status'       => 'active',
        ]);

        Auth::login($user);

        // ── Generate 8-digit OTP ───────────────────────────────────────────
        ['code' => $code] = \App\Models\EmailVerificationCode::generateFor($user->id, $request->ip());

        // ── Send OTP via Email ─────────────────────────────────────────────
        try {
            Mail::to($user->email)->send(new \App\Mail\VerificationCodeMail($user, $code));
        } catch (\Exception $e) {
            Log::error('[OTP Email] Failed for ' . $user->email . ': ' . $e->getMessage());
        }

        // ── Send OTP via SMS (Africa's Talking) ────────────────────────────
        try {
            $sms = new SmsService();
            $sms->sendOtp($user->phone_number, $code, $user->name);
            Log::info('[OTP SMS] Sent to ' . $user->phone_number . ' for user ' . $user->id);
        } catch (\Exception $e) {
            Log::warning('[OTP SMS] Failed for ' . $user->phone_number . ': ' . $e->getMessage());
        }

        // Redirect to the OTP entry page
        return redirect()->route('verification.notice')
            ->with('status', 'A verification code has been sent to your email and phone number.');
    }
}