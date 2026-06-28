<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Muthurwa Market</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #F0F4F8; color: #333; }
        .wrapper { max-width: 600px; margin: 30px auto; background: #ffffff; border-radius: 20px; overflow: hidden; box-shadow: 0 8px 40px rgba(0,0,0,0.12); }

        /* ── Header ── */
        .header {
            background: linear-gradient(135deg, #068930 0%, #046122 60%, #034d1a 100%);
            padding: 48px 40px 60px;
            text-align: center;
            position: relative;
        }
        .header::after {
            content: '';
            position: absolute;
            bottom: -1px; left: 0; right: 0;
            height: 40px;
            background: #ffffff;
            border-radius: 50% 50% 0 0 / 100% 100% 0 0;
        }
        .badge {
            display: inline-block;
            background: rgba(252,221,7,0.15);
            border: 1px solid rgba(252,221,7,0.4);
            color: #FCDD07;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 0.3em;
            text-transform: uppercase;
            padding: 6px 16px;
            border-radius: 999px;
            margin-bottom: 20px;
        }
        .lion { font-size: 60px; display: block; margin-bottom: 12px; }
        .header h1 {
            color: #ffffff;
            font-size: 28px;
            font-weight: 900;
            letter-spacing: -0.5px;
            line-height: 1.2;
        }
        .header h1 span { color: #FCDD07; }
        .header p {
            color: rgba(255,255,255,0.75);
            font-size: 13px;
            margin-top: 8px;
        }

        /* ── Body ── */
        .body { padding: 40px; }

        .greeting {
            font-size: 22px;
            font-weight: 900;
            color: #068930;
            margin-bottom: 12px;
        }
        .intro {
            font-size: 14px;
            color: #555;
            line-height: 1.7;
            margin-bottom: 30px;
        }

        /* ── Info Card ── */
        .info-card {
            background: #F8FFFE;
            border: 1.5px solid #D1FAE5;
            border-radius: 14px;
            padding: 24px;
            margin-bottom: 28px;
        }
        .info-card-title {
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 0.25em;
            text-transform: uppercase;
            color: #068930;
            margin-bottom: 16px;
        }
        .info-row {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 0;
            border-bottom: 1px solid #E8F5EE;
        }
        .info-row:last-child { border-bottom: none; }
        .info-icon {
            width: 36px; height: 36px;
            background: #ECFDF5;
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 16px;
            flex-shrink: 0;
        }
        .info-label {
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: #999;
        }
        .info-value {
            font-size: 14px;
            font-weight: 700;
            color: #222;
            margin-top: 1px;
        }

        /* ── Steps ── */
        .steps-title {
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 0.25em;
            text-transform: uppercase;
            color: #0F47AF;
            margin-bottom: 16px;
        }
        .step {
            display: flex;
            align-items: flex-start;
            gap: 14px;
            margin-bottom: 14px;
        }
        .step-num {
            width: 30px; height: 30px;
            background: #0F47AF;
            color: #fff;
            font-size: 12px;
            font-weight: 900;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
            margin-top: 1px;
        }
        .step-text { font-size: 13px; color: #444; line-height: 1.55; }
        .step-text strong { color: #222; font-weight: 800; }

        /* ── CTA Button ── */
        .cta-wrap { text-align: center; margin: 32px 0; }
        .cta-btn {
            display: inline-block;
            background: linear-gradient(135deg, #068930, #046122);
            color: #ffffff !important;
            text-decoration: none;
            font-size: 14px;
            font-weight: 900;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            padding: 16px 40px;
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(6,137,48,0.35);
        }

        /* ── Notice Box ── */
        .notice {
            background: #FFFBEB;
            border-left: 4px solid #FCDD07;
            border-radius: 0 10px 10px 0;
            padding: 14px 18px;
            margin-top: 28px;
            font-size: 12px;
            color: #78610A;
            line-height: 1.6;
        }
        .notice strong { font-weight: 800; }

        /* ── Footer ── */
        .footer {
            background: #1A1A1B;
            padding: 28px 40px;
            text-align: center;
        }
        .footer-logo { font-size: 22px; margin-bottom: 8px; }
        .footer-name {
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            color: #FCDD07;
            margin-bottom: 4px;
        }
        .footer-sub {
            font-size: 10px;
            color: rgba(255,255,255,0.4);
            margin-bottom: 16px;
        }
        .footer-links { margin-bottom: 12px; }
        .footer-links a {
            color: rgba(255,255,255,0.5);
            text-decoration: none;
            font-size: 11px;
            margin: 0 8px;
        }
        .footer-legal {
            font-size: 10px;
            color: rgba(255,255,255,0.25);
            line-height: 1.6;
        }
    </style>
</head>
<body>
<div class="wrapper">

    {{-- ── Header ── --}}
    <div class="header">
        <div class="badge">Nairobi City County · Official Notice</div>
        <span class="lion">🦁</span>
        <h1>Welcome to<br><span>Muthurwa Market!</span></h1>
        <p>Your trader account has been activated.</p>
    </div>

    {{-- ── Body ── --}}
    <div class="body">

        <p class="greeting">Habari, {{ $user->name }}! 👋</p>
        <p class="intro">
            You have successfully registered on the <strong>Muthurwa Market Smart Booking System</strong> — Nairobi City County's official digital platform for stall reservation and management.<br><br>
            Your account is ready. You can now browse available stalls, make reservations, and manage your bookings — all from your phone or computer.
        </p>

        {{-- Account details card --}}
        <div class="info-card">
            <div class="info-card-title">📋 Your Account Details</div>

            <div class="info-row">
                <div class="info-icon">👤</div>
                <div>
                    <div class="info-label">Full Name</div>
                    <div class="info-value">{{ $user->name }}</div>
                </div>
            </div>

            <div class="info-row">
                <div class="info-icon">📧</div>
                <div>
                    <div class="info-label">Email Address</div>
                    <div class="info-value">{{ $user->email }}</div>
                </div>
            </div>

            <div class="info-row">
                <div class="info-icon">📱</div>
                <div>
                    <div class="info-label">Phone Number</div>
                    <div class="info-value">{{ $user->phone_number }}</div>
                </div>
            </div>

            <div class="info-row">
                <div class="info-icon">🏷️</div>
                <div>
                    <div class="info-label">Account Role</div>
                    <div class="info-value">Trader</div>
                </div>
            </div>

            <div class="info-row">
                <div class="info-icon">📅</div>
                <div>
                    <div class="info-label">Registered On</div>
                    <div class="info-value">{{ $user->created_at->format('d F Y, h:i A') }}</div>
                </div>
            </div>
        </div>

        {{-- Getting started steps --}}
        <div class="steps-title">🚀 Getting Started — 3 Simple Steps</div>

        <div class="step">
            <div class="step-num">1</div>
            <div class="step-text"><strong>Browse the Market Map</strong> — View all 100 available stalls across 5 zones at Muthurwa Market and check real-time availability.</div>
        </div>
        <div class="step">
            <div class="step-num">2</div>
            <div class="step-text"><strong>Book Your Stall</strong> — Select your preferred stall, choose your booking duration (1 day to 1 month), and confirm your reservation.</div>
        </div>
        <div class="step">
            <div class="step-num">3</div>
            <div class="step-text"><strong>Pay via M-Pesa</strong> — Complete your payment securely using M-Pesa. Your digital QR ticket is generated instantly upon payment.</div>
        </div>

        {{-- CTA --}}
        <div class="cta-wrap">
            <a href="{{ config('app.url') }}/trader/stalls" class="cta-btn">
                🏪 Browse Stalls Now
            </a>
        </div>

        {{-- Notice --}}
        <div class="notice">
            <strong>⚠️ Important:</strong> Keep your login credentials safe. Never share your password or M-Pesa PIN with anyone. The Nairobi City County team will never ask for your password by phone or email.
        </div>

    </div>

    {{-- ── Footer ── --}}
    <div class="footer">
        <div class="footer-logo">🦁</div>
        <div class="footer-name">Muthurwa Market</div>
        <div class="footer-sub">Nairobi City County · Digital Services Division</div>
        <div class="footer-links">
            <a href="{{ config('app.url') }}">Visit Portal</a>
            <a href="{{ config('app.url') }}/trader/bookings">My Bookings</a>
        </div>
        <div class="footer-legal">
            © {{ date('Y') }} Nairobi City County Government. All rights reserved.<br>
            This email was sent to {{ $user->email }} because you created an account on our platform.
        </div>
    </div>

</div>
</body>
</html>
