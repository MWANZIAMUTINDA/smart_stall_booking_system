<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Your Verification Code — Muthurwa Market</title>
<style>
*{margin:0;padding:0;box-sizing:border-box;}
body{font-family:'Segoe UI',Arial,sans-serif;background:#F0F4F8;color:#333;}
.wrapper{max-width:560px;margin:30px auto;background:#fff;border-radius:20px;overflow:hidden;box-shadow:0 8px 40px rgba(0,0,0,0.12);}
.header{background:linear-gradient(135deg,#068930 0%,#046122 60%,#034d1a 100%);padding:40px 40px 55px;text-align:center;position:relative;}
.header::after{content:'';position:absolute;bottom:-1px;left:0;right:0;height:35px;background:#fff;border-radius:50% 50% 0 0/100% 100% 0 0;}
.badge{display:inline-block;background:rgba(252,221,7,0.15);border:1px solid rgba(252,221,7,0.4);color:#FCDD07;font-size:10px;font-weight:800;letter-spacing:0.3em;text-transform:uppercase;padding:6px 16px;border-radius:999px;margin-bottom:16px;}
.lock{font-size:56px;display:block;margin-bottom:10px;}
.header h1{color:#fff;font-size:26px;font-weight:900;letter-spacing:-0.5px;}
.header p{color:rgba(255,255,255,0.65);font-size:13px;margin-top:6px;}
.body{padding:40px;}
.greeting{font-size:20px;font-weight:900;color:#068930;margin-bottom:10px;}
.intro{font-size:14px;color:#555;line-height:1.7;margin-bottom:28px;}

/* ── OTP Block ── */
.otp-block{background:linear-gradient(135deg,#F0FAF3,#E8F5E9);border:2px solid #D1FAE5;border-radius:16px;padding:28px;text-align:center;margin-bottom:28px;}
.otp-label{font-size:10px;font-weight:800;text-transform:uppercase;letter-spacing:0.25em;color:#068930;margin-bottom:12px;}
.otp-code{
    font-size:52px;font-weight:900;
    letter-spacing:0.35em;color:#1A1A1B;
    font-family:'Courier New',monospace;
    background:#fff;border-radius:12px;
    padding:16px 24px;display:inline-block;
    border:3px solid #D1FAE5;
    box-shadow:0 4px 16px rgba(6,137,48,0.15);
    margin-bottom:14px;
}
.otp-expiry{font-size:12px;font-weight:700;color:#DC2626;background:#FFF5F5;border:1px solid #FECACA;border-radius:8px;padding:8px 16px;display:inline-block;}

/* ── Instructions ── */
.steps-title{font-size:10px;font-weight:800;letter-spacing:0.25em;text-transform:uppercase;color:#0F47AF;margin-bottom:14px;}
.step{display:flex;align-items:flex-start;gap:12px;margin-bottom:12px;}
.step-num{width:28px;height:28px;background:#0F47AF;color:#fff;font-size:11px;font-weight:900;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.step-text{font-size:13px;color:#444;line-height:1.55;}
.step-text strong{color:#222;font-weight:800;}

/* ── Notice ── */
.notice{background:#FFFBEB;border-left:4px solid #FCDD07;border-radius:0 10px 10px 0;padding:14px 18px;margin-top:24px;font-size:12px;color:#78610A;line-height:1.6;}
.notice strong{font-weight:800;}

/* ── Footer ── */
.footer{background:#1A1A1B;padding:28px 40px;text-align:center;}
.footer-name{font-size:11px;font-weight:800;letter-spacing:0.2em;text-transform:uppercase;color:#FCDD07;margin-bottom:4px;}
.footer-sub{font-size:10px;color:rgba(255,255,255,0.35);margin-bottom:14px;}
.footer-legal{font-size:10px;color:rgba(255,255,255,0.2);line-height:1.6;}
</style>
</head>
<body>
<div class="wrapper">

    <!-- Header -->
    <div class="header">
        <div class="badge">Account Verification</div>
        <span class="lock">🔐</span>
        <h1>Email Verification Code</h1>
        <p>Muthurwa Market · Nairobi City County</p>
    </div>

    <!-- Body -->
    <div class="body">
        <p class="greeting">Hello, {{ $user->name }}! 👋</p>
        <p class="intro">
            You recently created a trader account on the <strong>Muthurwa Market Smart Booking System</strong>.
            Use the verification code below to activate your account.
        </p>

        <!-- OTP Code Block -->
        <div class="otp-block">
            <div class="otp-label">🔑 Your 8-Digit Verification Code</div>
            <div class="otp-code">{{ $code }}</div>
            <br>
            <span class="otp-expiry">⏱ Expires in 10 minutes</span>
        </div>

        <!-- Steps -->
        <div class="steps-title">📋 How to verify your account</div>
        <div class="step">
            <div class="step-num">1</div>
            <div class="step-text">Go back to the verification page in your browser.</div>
        </div>
        <div class="step">
            <div class="step-num">2</div>
            <div class="step-text">Enter the <strong>8-digit code: {{ $code }}</strong> in the input boxes shown.</div>
        </div>
        <div class="step">
            <div class="step-num">3</div>
            <div class="step-text">Click <strong>Verify Account</strong> — you will be taken straight to your dashboard.</div>
        </div>

        <!-- Security Notice -->
        <div class="notice">
            <strong>🔒 Security Notice:</strong> This code expires in <strong>10 minutes</strong> and can only be used once.
            If you did not register on Muthurwa Market, please ignore this email.
            Never share this code with anyone — our staff will never ask for it.
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <div class="footer-name">🦁 Muthurwa Market</div>
        <div class="footer-sub">Nairobi City County · Digital Services Division</div>
        <div class="footer-legal">
            © {{ date('Y') }} Nairobi City County Government. All rights reserved.<br>
            This email was sent to {{ $user->email }} because an account was created using this address.
        </div>
    </div>

</div>
</body>
</html>
