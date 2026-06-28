<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>Verify Your Account — Muthurwa Market</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;900&display=swap" rel="stylesheet">
@vite(['resources/css/app.css', 'resources/js/app.js'])
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
body{font-family:'Inter',sans-serif;min-height:100vh;display:flex;align-items:center;justify-content:center;padding:1rem;
    background:linear-gradient(135deg,#068930,#046122,#0F47AF);background-size:400% 400%;animation:flow 15s ease infinite;}
@keyframes flow{0%,100%{background-position:0% 50%;}50%{background-position:100% 50%;}}

.card{background:rgba(255,255,255,0.09);backdrop-filter:blur(28px);-webkit-backdrop-filter:blur(28px);
    border:1px solid rgba(255,255,255,0.2);border-radius:2rem;padding:2.5rem 2rem;
    max-width:460px;width:100%;box-shadow:0 30px 70px rgba(0,0,0,0.3);}

/* ── Top Section ── */
.icon-wrap{width:80px;height:80px;background:rgba(252,221,7,0.15);border:2px solid rgba(252,221,7,0.35);
    border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 1.25rem;font-size:2.2rem;}
.badge{display:inline-block;background:rgba(252,221,7,0.12);border:1px solid rgba(252,221,7,0.3);
    color:#FCDD07;font-size:0.6rem;font-weight:900;letter-spacing:0.3em;text-transform:uppercase;
    padding:4px 12px;border-radius:999px;margin-bottom:0.875rem;}
h1{color:#fff;font-size:1.65rem;font-weight:900;letter-spacing:-0.03em;margin-bottom:0.5rem;text-align:center;}
.subtitle{color:rgba(255,255,255,0.6);font-size:0.82rem;font-weight:500;line-height:1.6;text-align:center;margin-bottom:0.5rem;}
.email-chip{display:inline-flex;align-items:center;gap:6px;background:rgba(255,255,255,0.1);
    border:1px solid rgba(255,255,255,0.2);border-radius:999px;padding:5px 14px;
    color:#fff;font-size:0.78rem;font-weight:700;margin-bottom:1.5rem;}

/* ── Countdown ── */
.countdown-wrap{display:flex;align-items:center;justify-content:center;gap:8px;margin-bottom:1.5rem;}
.countdown-badge{background:rgba(239,68,68,0.15);border:1px solid rgba(239,68,68,0.3);
    border-radius:999px;padding:5px 14px;font-size:0.75rem;font-weight:800;color:#FCA5A5;}
.countdown-badge.safe{background:rgba(6,137,48,0.15);border-color:rgba(6,137,48,0.3);color:#6EE7B7;}

/* ── Alerts ── */
.alert{border-radius:0.875rem;padding:0.75rem 1rem;font-size:0.78rem;font-weight:700;margin-bottom:1rem;text-align:left;}
.alert-error{background:rgba(239,68,68,0.15);border:1px solid rgba(239,68,68,0.3);color:#FCA5A5;}
.alert-success{background:rgba(6,137,48,0.15);border:1px solid rgba(6,137,48,0.3);color:#6EE7B7;}
.alert-info{background:rgba(15,71,175,0.15);border:1px solid rgba(15,71,175,0.3);color:#93C5FD;}

/* ── OTP Digit Inputs ── */
.otp-row{display:flex;gap:8px;justify-content:center;margin-bottom:1rem;}
.otp-row input{
    width:48px;height:60px;text-align:center;
    font-size:1.5rem;font-weight:900;
    background:rgba(255,255,255,0.12);
    border:2px solid rgba(255,255,255,0.2);
    border-radius:0.875rem;color:#fff;outline:none;
    transition:all 0.2s;caret-color:transparent;
    -webkit-appearance:none;
}
.otp-row input:focus{border-color:#FCDD07;background:rgba(252,221,7,0.1);box-shadow:0 0 0 4px rgba(252,221,7,0.15);}
.otp-row input.filled{border-color:rgba(6,137,48,0.6);background:rgba(6,137,48,0.15);}
.otp-row input.error{border-color:rgba(239,68,68,0.7);background:rgba(239,68,68,0.1);}

/* Hidden real input */
#code-hidden{display:none;}

/* ── Submit Button ── */
.btn-verify{width:100%;height:52px;border:none;border-radius:1rem;
    background:#FCDD07;color:#068930;font-size:0.95rem;font-weight:900;
    cursor:pointer;transition:all 0.25s;
    box-shadow:0 6px 24px rgba(252,221,7,0.3);
    display:flex;align-items:center;justify-content:center;gap:8px;}
.btn-verify:hover:not(:disabled){transform:translateY(-2px);box-shadow:0 10px 32px rgba(252,221,7,0.4);}
.btn-verify:disabled{opacity:0.5;cursor:not-allowed;transform:none;}

/* ── Resend ── */
.resend-row{text-align:center;margin-top:1.25rem;}
.btn-resend{background:none;border:none;color:rgba(255,255,255,0.5);font-size:0.78rem;font-weight:700;
    cursor:pointer;text-decoration:underline;transition:color 0.2s;}
.btn-resend:hover:not(:disabled){color:#fff;}
.btn-resend:disabled{cursor:not-allowed;color:rgba(255,255,255,0.25);text-decoration:none;}

/* ── Divider ── */
.divider{height:1px;background:rgba(255,255,255,0.1);margin:1.25rem 0;}

/* ── Footer ── */
.card-footer{text-align:center;margin-top:0.75rem;font-size:0.6rem;color:rgba(255,255,255,0.2);
    font-weight:700;text-transform:uppercase;letter-spacing:0.15em;}
</style>
</head>
<body>

<div class="card">
  <div style="text-align:center;">
    <div class="icon-wrap">🔐</div>
    <div class="badge">Account Verification</div>
    <h1>Enter Your Code</h1>
    <p class="subtitle">
      We sent an <strong style="color:#fff;">8-digit verification code</strong> to your email address.
      Enter it below to activate your account.
    </p>
    <div class="email-chip">
      📧 {{ auth()->user()->email }}
    </div>
  </div>

  {{-- Countdown Timer --}}
  <div class="countdown-wrap">
    <span style="font-size:0.75rem;font-weight:700;color:rgba(255,255,255,0.5);">Code expires in:</span>
    <span class="countdown-badge" id="countdown-badge">
      <span id="countdown-timer">--:--</span>
    </span>
  </div>

  {{-- Alerts --}}
  @if(session('status') === 'verification-code-sent')
    <div class="alert alert-success">✅ A fresh code has been sent to your email!</div>
  @endif

  @if(session('resend_error'))
    <div class="alert alert-error">⏳ {{ session('resend_error') }}</div>
  @endif

  @if($errors->has('code'))
    <div class="alert alert-error">⚠ {{ $errors->first('code') }}</div>
  @endif

  {{-- OTP Form --}}
  <form method="POST" action="{{ route('verification.verify') }}" id="otp-form">
    @csrf

    {{-- 8 visual digit boxes --}}
    <div class="otp-row" id="otp-boxes">
      <input type="text" inputmode="numeric" maxlength="1" class="otp-digit" data-index="0" autocomplete="off">
      <input type="text" inputmode="numeric" maxlength="1" class="otp-digit" data-index="1" autocomplete="off">
      <input type="text" inputmode="numeric" maxlength="1" class="otp-digit" data-index="2" autocomplete="off">
      <input type="text" inputmode="numeric" maxlength="1" class="otp-digit" data-index="3" autocomplete="off">
      <input type="text" inputmode="numeric" maxlength="1" class="otp-digit" data-index="4" autocomplete="off">
      <input type="text" inputmode="numeric" maxlength="1" class="otp-digit" data-index="5" autocomplete="off">
      <input type="text" inputmode="numeric" maxlength="1" class="otp-digit" data-index="6" autocomplete="off">
      <input type="text" inputmode="numeric" maxlength="1" class="otp-digit" data-index="7" autocomplete="off">
    </div>

    {{-- Hidden real input that gets submitted --}}
    <input type="hidden" name="code" id="code-hidden">

    <button type="submit" class="btn-verify" id="verify-btn" disabled>
      <svg style="width:18px;height:18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
      Verify Account
    </button>
  </form>

  <div class="divider"></div>

  {{-- Resend --}}
  <div class="resend-row">
    <p style="color:rgba(255,255,255,0.5);font-size:0.78rem;font-weight:600;margin-bottom:6px;">
      Didn't receive the code?
    </p>
    <form method="POST" action="{{ route('verification.send') }}" id="resend-form" style="display:inline;">
      @csrf
      <button type="submit" class="btn-resend" id="resend-btn">
        📨 Resend Code
      </button>
    </form>
    <p id="resend-countdown" style="color:rgba(255,255,255,0.35);font-size:0.72rem;font-weight:700;display:none;margin-top:4px;"></p>
  </div>

  {{-- Logout --}}
  <div style="text-align:center;margin-top:0.75rem;">
    <form method="POST" action="{{ route('logout') }}" style="display:inline;">
      @csrf
      <button type="submit" style="background:none;border:none;color:rgba(255,255,255,0.3);font-size:0.7rem;font-weight:700;cursor:pointer;text-decoration:underline;">
        Use a different account? Log out
      </button>
    </form>
  </div>

  <div class="card-footer">Nairobi City County · Muthurwa Market · {{ date('Y') }}</div>
</div>

<script>
const digits      = document.querySelectorAll('.otp-digit');
const hiddenInput = document.getElementById('code-hidden');
const verifyBtn   = document.getElementById('verify-btn');
const otpForm     = document.getElementById('otp-form');

// ── OTP Digit Input Logic ────────────────────────────────────────────────
digits.forEach((input, idx) => {
    // Allow digits only
    input.addEventListener('keydown', e => {
        if (!/^\d$/.test(e.key) && !['Backspace','Delete','ArrowLeft','ArrowRight','Tab'].includes(e.key)) {
            e.preventDefault();
        }
    });

    input.addEventListener('input', e => {
        const val = e.target.value.replace(/\D/g, '');
        e.target.value = val.slice(-1);
        e.target.classList.toggle('filled', val.length > 0);
        e.target.classList.remove('error');
        updateHidden();
        if (val && idx < digits.length - 1) digits[idx + 1].focus();
    });

    input.addEventListener('keydown', e => {
        if (e.key === 'Backspace' && !e.target.value && idx > 0) {
            digits[idx - 1].value = '';
            digits[idx - 1].classList.remove('filled');
            digits[idx - 1].focus();
            updateHidden();
        }
    });

    // Paste support: paste full 8-digit code
    input.addEventListener('paste', e => {
        e.preventDefault();
        const pasted = (e.clipboardData || window.clipboardData).getData('text').replace(/\D/g, '');
        [...pasted.slice(0, 8)].forEach((ch, i) => {
            if (digits[i]) {
                digits[i].value = ch;
                digits[i].classList.add('filled');
            }
        });
        updateHidden();
        const next = Math.min(pasted.length, 7);
        digits[next].focus();
    });
});

function updateHidden() {
    const code = [...digits].map(d => d.value).join('');
    hiddenInput.value = code;
    verifyBtn.disabled = code.length < 8;
}

// Highlight errors on previously submitted code
@if($errors->has('code'))
    digits.forEach(d => d.classList.add('error'));
@endif

// ── Countdown Timer ──────────────────────────────────────────────────────
const secondsLeft  = {{ $secondsLeft ?? 0 }};
const endTime      = Date.now() + secondsLeft * 1000;
const badge        = document.getElementById('countdown-badge');
const timerEl      = document.getElementById('countdown-timer');

function updateCountdown() {
    const diff = Math.max(0, Math.floor((endTime - Date.now()) / 1000));
    const m = Math.floor(diff / 60);
    const s = diff % 60;
    timerEl.textContent = `${String(m).padStart(2,'0')}:${String(s).padStart(2,'0')}`;
    badge.className = 'countdown-badge' + (diff > 60 ? ' safe' : '');
    if (diff <= 0) {
        timerEl.textContent = 'EXPIRED';
        badge.className = 'countdown-badge';
    }
}
updateCountdown();
setInterval(updateCountdown, 1000);

// ── Resend cooldown UI ───────────────────────────────────────────────────
const resendBtn  = document.getElementById('resend-btn');
const resendInfo = document.getElementById('resend-countdown');
let resendWait   = 0;

@if(session('status') === 'verification-code-sent')
    resendWait = 120;
@endif

function startResendCooldown(seconds) {
    resendBtn.disabled = true;
    resendInfo.style.display = 'block';
    const end = Date.now() + seconds * 1000;
    const iv = setInterval(() => {
        const left = Math.max(0, Math.floor((end - Date.now()) / 1000));
        resendInfo.textContent = `You can resend again in ${left}s`;
        if (left <= 0) {
            clearInterval(iv);
            resendBtn.disabled = false;
            resendInfo.style.display = 'none';
        }
    }, 1000);
}

if (resendWait > 0) startResendCooldown(resendWait);

// ── Submit feedback ──────────────────────────────────────────────────────
otpForm.addEventListener('submit', () => {
    verifyBtn.disabled = true;
    verifyBtn.innerHTML = '⏳ Verifying...';
});
</script>
</body>
</html>
