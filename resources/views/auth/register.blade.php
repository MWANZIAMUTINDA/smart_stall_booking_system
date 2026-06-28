<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="description" content="Create your Muthurwa Stall Booking account — Nairobi County.">
<title>Create Account — Muthurwa Stall Booking</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;900&display=swap" rel="stylesheet">
@vite(['resources/css/app.css', 'resources/js/app.js'])
<style>
*,*::before,*::after{box-sizing:border-box;}
html,body{margin:0;padding:0;min-height:100vh;font-family:'Inter',sans-serif;}

:root {
  --green: #068930;
  --yellow: #FCDD07;
  --blue: #0F47AF;
  --black: #1A1A1B;
}

@keyframes nairobiFlow {
  0%,100% { background-position: 0% 50%; }
  50%      { background-position: 100% 50%; }
}
.bg-nairobi {
  background: linear-gradient(135deg, #068930, #057529, #046122, #0F47AF);
  background-size: 400% 400%;
  animation: nairobiFlow 15s ease infinite;
}

/* ── Layout ── */
.split { display: flex; min-height: 100vh; }
.pl { flex:0 0 42%; position:relative; overflow:hidden; display:flex; flex-direction:column; justify-content:center; padding:4rem 3rem; }
.pr { flex:1; background:#fff; display:flex; flex-direction:column; justify-content:center; padding:3rem; position:relative; border-left:8px solid var(--yellow); overflow-y:auto; }

@media(max-width:1024px){ .pl{flex:0 0 35%;padding:2rem;} }
@media(max-width:768px){
  .split{flex-direction:column;}
  .pl{flex:0 0 auto;padding:2.5rem 2rem;}
  .pr{padding:2rem;border-left:none;border-top:8px solid var(--yellow);}
}

.skyline-watermark{position:absolute;bottom:0;left:0;width:100%;opacity:.1;pointer-events:none;}

/* ── Field Groups ── */
.fg { position:relative; margin-bottom:0.85rem; }
.fg input {
  width:100%; height:52px;
  padding:1.4rem 1rem 0.4rem;
  border:2px solid #F1F5F9;
  border-radius:0.875rem;
  font-size:0.875rem; font-weight:600; color:var(--black);
  background:#F8FAFC; outline:none;
  transition:all 0.25s;
}
.fg input:focus { border-color:var(--blue); background:#fff; box-shadow:0 0 0 4px rgba(15,71,175,0.1); }
.fg input.is-valid   { border-color:#068930; background:#f0faf3; }
.fg input.is-invalid { border-color:#EF4444; background:#fff5f5; }
.fg label {
  position:absolute; left:1rem; top:50%;
  transform:translateY(-50%);
  font-size:0.875rem; font-weight:500; color:#94A3B8;
  pointer-events:none; transition:all 0.2s;
}
.fg input:focus+label,
.fg input:not(:placeholder-shown)+label {
  top:0.65rem; transform:none;
  font-size:0.6rem; font-weight:900;
  color:var(--blue); text-transform:uppercase; letter-spacing:0.05em;
}
.fg .icon {
  position:absolute; right:1rem; top:50%; transform:translateY(-50%);
  font-size:0.9rem; pointer-events:none;
}

/* ── Password Strength ── */
.strength-bar { display:flex; gap:4px; margin-top:6px; }
.strength-bar span { flex:1; height:4px; border-radius:999px; background:#E2E8F0; transition:background 0.3s; }
.strength-label { font-size:0.65rem; font-weight:800; text-transform:uppercase; letter-spacing:0.1em; margin-top:4px; }

/* ── Requirements List ── */
.req-list { display:grid; grid-template-columns:1fr 1fr; gap:2px 8px; margin-top:6px; }
.req { font-size:0.65rem; font-weight:700; display:flex; align-items:center; gap:4px; color:#94A3B8; transition:color 0.25s; }
.req.met { color:#068930; }
.req::before { content:'○'; font-size:0.7rem; }
.req.met::before { content:'✓'; }

/* ── Submit Button ── */
.btn-main {
  width:100%; height:54px; border:none; border-radius:1rem;
  background:var(--green); color:#fff;
  font-size:1rem; font-weight:900; cursor:pointer;
  transition:all 0.3s; box-shadow:0 8px 24px rgba(6,137,48,0.3);
  display:flex; align-items:center; justify-content:center; gap:0.75rem;
}
.btn-main:hover:not(:disabled) { transform:translateY(-2px); box-shadow:0 12px 32px rgba(6,137,48,0.4); background:#057529; }
.btn-main:disabled { opacity:0.65; cursor:not-allowed; transform:none; }

/* ── Feature Tags (Left Panel) ── */
.emblem-box { background:rgba(255,255,255,0.15); backdrop-filter:blur(20px); border:1px solid rgba(255,255,255,0.2); padding:1.1rem; border-radius:1.75rem; width:fit-content; margin-bottom:1.75rem; }
.feature-tag { background:rgba(255,255,255,0.08); border:1px solid rgba(255,255,255,0.12); padding:0.6rem 1rem; border-radius:1rem; display:flex; align-items:center; gap:0.75rem; margin-bottom:0.6rem; }
.pill { display:inline-flex; align-items:center; gap:0.5rem; background:#E7F3EF; border:1px solid rgba(6,137,48,0.2); padding:0.35rem 0.9rem; border-radius:999px; font-size:0.7rem; font-weight:900; color:var(--green); text-transform:uppercase; letter-spacing:0.1em; margin-bottom:1.5rem; }

/* ── Error / Alert ── */
.err { color:#EF4444; font-size:0.68rem; font-weight:700; margin-top:3px; display:flex; align-items:center; gap:4px; }
.alert-danger { background:#FFF5F5; border:1.5px solid #FCA5A5; border-radius:1rem; padding:0.9rem 1rem; margin-bottom:1rem; }
.alert-danger p { color:#DC2626; font-size:0.75rem; font-weight:700; margin:2px 0; }

/* ── Terms Checkbox ── */
.terms-row { display:flex; align-items:flex-start; gap:0.75rem; margin:0.85rem 0; cursor:pointer; }
.terms-row input[type=checkbox] { width:18px; height:18px; accent-color:var(--green); cursor:pointer; flex-shrink:0; margin-top:2px; border-radius:4px; }
.terms-row label { font-size:0.78rem; color:#475569; font-weight:600; cursor:pointer; line-height:1.5; }
.terms-row label a { color:var(--blue); font-weight:800; text-decoration:none; }
.terms-row label a:hover { text-decoration:underline; }

/* ── Divider ── */
.divider { height:1px; background:#F1F5F9; margin:0.75rem 0; }

/* ── Phone prefix ── */
.phone-prefix { position:absolute; left:1rem; top:50%; transform:translateY(-50%); font-size:0.85rem; font-weight:900; color:var(--blue); z-index:1; pointer-events:none; }
</style>
</head>
<body>
<div class="split">

  <!-- ══ LEFT PANEL ══ -->
  <div class="pl bg-nairobi">
    <svg class="skyline-watermark" viewBox="0 0 800 200" fill="#FFFFFF">
      <path d="M0 200V180H20V150H40V180H60V140H80V180H100V100H130V180H150V120H180V180H200V60H240V180H260V140H290V180H310V90H350V180H370V130H400V180H420V40H460V180H480V120H510V180H530V80H570V180H590V140H620V180H640V110H680V180H700V150H730V180H750V130H780V180H800V200H0Z"/>
    </svg>
    <div style="position:relative;z-index:10;">
      <div class="emblem-box"><span style="font-size:2.5rem;">🦁</span></div>
      <h1 style="font-size:2.2rem;font-weight:900;color:#fff;line-height:1;letter-spacing:-0.03em;">
        <span style="color:var(--yellow);font-size:0.65rem;letter-spacing:0.4em;text-transform:uppercase;display:block;margin-bottom:0.6rem;">Nairobi City County</span>
        Muthurwa<br><span style="color:rgba(255,255,255,0.55);font-size:1.3rem;">Digital Portal</span>
      </h1>
      <p style="color:rgba(255,255,255,0.35);font-weight:700;text-transform:uppercase;letter-spacing:0.2em;font-size:0.55rem;margin:1rem 0 2rem;">Green City in the Sun</p>

      <div class="feature-tag">
        <div style="width:30px;height:30px;background:var(--yellow);border-radius:0.65rem;display:flex;align-items:center;justify-content:center;">🏪</div>
        <span style="color:#fff;font-weight:800;font-size:0.78rem;">100 Market Stalls</span>
      </div>
      <div class="feature-tag">
        <div style="width:30px;height:30px;background:var(--blue);border-radius:0.65rem;display:flex;align-items:center;justify-content:center;">📱</div>
        <span style="color:#fff;font-weight:800;font-size:0.78rem;">M-Pesa Payments</span>
      </div>
      <div class="feature-tag">
        <div style="width:30px;height:30px;background:rgba(255,255,255,0.2);border-radius:0.65rem;display:flex;align-items:center;justify-content:center;">🔒</div>
        <span style="color:#fff;font-weight:800;font-size:0.78rem;">Secure & Verified</span>
      </div>
      <div class="feature-tag">
        <div style="width:30px;height:30px;background:rgba(252,221,7,0.25);border-radius:0.65rem;display:flex;align-items:center;justify-content:center;">✅</div>
        <span style="color:#fff;font-weight:800;font-size:0.78rem;">Email Verified Accounts</span>
      </div>

      <p style="color:rgba(255,255,255,0.2);font-size:0.5rem;font-weight:700;text-transform:uppercase;letter-spacing:0.1em;margin-top:2.5rem;">
        © {{ date('Y') }} Nairobi City County. All rights reserved.
      </p>
    </div>
  </div>

  <!-- ══ RIGHT PANEL (Form) ══ -->
  <div class="pr">
    <div style="max-width:500px;width:100%;margin:auto;">

      <div class="pill">
        <span style="width:8px;height:8px;background:#068930;border-radius:50%;animation:pulse 2s infinite;"></span>
        Trader Registration
      </div>

      <h2 style="font-size:1.8rem;font-weight:900;color:var(--black);margin:0 0 0.25rem;letter-spacing:-0.02em;">Create Account ✨</h2>
      <p style="color:#64748B;font-weight:500;margin-bottom:1.25rem;font-size:0.85rem;">Join the official Muthurwa trading community. All fields are required.</p>

      {{-- ── Validation Error Summary ── --}}
      @if($errors->any())
        <div class="alert-danger">
          <p style="font-weight:900;margin-bottom:4px;">⚠ Please fix the following:</p>
          @foreach($errors->all() as $err)
            <p>• {{ $err }}</p>
          @endforeach
        </div>
      @endif

      <form method="POST" action="{{ route('register') }}" novalidate id="reg-form">
        @csrf

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:0.75rem;">

          {{-- Full Name --}}
          <div class="fg" style="grid-column:span 2;">
            <input type="text" id="fn" name="name" value="{{ old('name') }}" placeholder=" " required autocomplete="name"
                   class="{{ $errors->has('name') ? 'is-invalid' : (old('name') ? 'is-valid' : '') }}">
            <label for="fn">Full Name</label>
            <span class="icon">👤</span>
            @error('name')<p class="err">⚠ {{ $message }}</p>@enderror
          </div>

          {{-- Username --}}
          <div class="fg">
            <input type="text" id="un" name="username" value="{{ old('username') }}" placeholder=" " required
                   autocomplete="username" maxlength="20"
                   class="{{ $errors->has('username') ? 'is-invalid' : (old('username') ? 'is-valid' : '') }}">
            <label for="un">Username</label>
            <span class="icon">🏷️</span>
            @error('username')<p class="err">⚠ {{ $message }}</p>@enderror
          </div>

          {{-- Email --}}
          <div class="fg">
            <input type="email" id="re" name="email" value="{{ old('email') }}" placeholder=" " required autocomplete="email"
                   class="{{ $errors->has('email') ? 'is-invalid' : (old('email') ? 'is-valid' : '') }}">
            <label for="re">Email Address</label>
            <span class="icon">📧</span>
            @error('email')<p class="err">⚠ {{ $message }}</p>@enderror
          </div>

          {{-- Phone --}}
          <div class="fg" style="grid-column:span 2;position:relative;">
            <span class="phone-prefix">🇰🇪 +254</span>
            <input type="tel" id="ph" name="phone_number" value="{{ old('phone_number') }}" placeholder=" "
                   required autocomplete="tel"
                   style="padding-left:5.5rem;"
                   class="{{ $errors->has('phone_number') ? 'is-invalid' : (old('phone_number') ? 'is-valid' : '') }}">
            <label for="ph" style="left:5.5rem;">Phone Number (07XXXXXXXX)</label>
            @error('phone_number')<p class="err">⚠ {{ $message }}</p>@enderror
          </div>

          {{-- Password --}}
          <div class="fg">
            <input type="password" id="rp" name="password" placeholder=" " required autocomplete="new-password"
                   oninput="checkStrength(this.value)"
                   class="{{ $errors->has('password') ? 'is-invalid' : '' }}">
            <label for="rp">Password</label>
          </div>

          {{-- Confirm Password --}}
          <div class="fg">
            <input type="password" id="rpc" name="password_confirmation" placeholder=" " required autocomplete="new-password"
                   oninput="checkMatch()"
                   class="{{ $errors->has('password') ? 'is-invalid' : '' }}">
            <label for="rpc">Confirm Password</label>
          </div>

          {{-- Password Strength Meter (full width) --}}
          <div style="grid-column:span 2;margin-top:-4px;margin-bottom:4px;">
            <div class="strength-bar">
              <span id="s1"></span><span id="s2"></span><span id="s3"></span><span id="s4"></span>
            </div>
            <div id="strength-label" class="strength-label" style="color:#94A3B8;">Enter a password</div>
            {{-- Requirements checklist --}}
            <div class="req-list" style="margin-top:6px;">
              <span class="req" id="r-len">Min. 8 characters</span>
              <span class="req" id="r-up">Uppercase letter</span>
              <span class="req" id="r-low">Lowercase letter</span>
              <span class="req" id="r-num">Number</span>
              <span class="req" id="r-sym">Special character</span>
              <span class="req" id="r-match">Passwords match</span>
            </div>
            @error('password')<p class="err" style="margin-top:4px;">⚠ {{ $message }}</p>@enderror
          </div>

        </div>

        <div class="divider"></div>

        {{-- §7 — Terms & Conditions --}}
        <div style="background:#F0FAF3;border:1.5px solid #D1FAE5;border-radius:0.875rem;padding:1rem 1.25rem;margin-bottom:0.75rem;">
          <div class="terms-row" style="margin:0;">
            <input type="checkbox" id="terms" name="terms" value="1" {{ old('terms') ? 'checked' : '' }} required>
            <label for="terms">
              I have read and agree to the
              <a href="{{ route('terms') }}" target="_blank" rel="noopener" style="color:var(--blue);font-weight:800;text-decoration:none;">
                Terms &amp; Conditions
              </a>
              and
              <a href="{{ route('terms') }}#s5" target="_blank" rel="noopener" style="color:var(--blue);font-weight:800;text-decoration:none;">
                Privacy Policy
              </a>
              of the Muthurwa Market Digital Platform, Nairobi City County.
            </label>
          </div>
          <a href="{{ route('terms') }}" target="_blank" rel="noopener"
             style="display:inline-flex;align-items:center;gap:5px;margin-top:8px;margin-left:28px;font-size:0.7rem;font-weight:800;text-transform:uppercase;letter-spacing:0.1em;color:var(--green);text-decoration:none;">
            📄 View Full Terms &amp; Conditions ↗
          </a>
        </div>
        @error('terms')<p class="err" style="margin-bottom:0.5rem;">⚠ {{ $message }}</p>@enderror

        {{-- M-Pesa Notice --}}
        <div style="background:#FFFBEB;border:1px solid #FDE68A;padding:0.75rem 1rem;border-radius:0.875rem;display:flex;gap:0.65rem;margin:0.85rem 0 1.1rem;align-items:flex-start;">
          <span style="font-size:1.1rem;flex-shrink:0;">💡</span>
          <p style="font-size:0.72rem;color:#92400E;font-weight:600;line-height:1.4;margin:0;">
            Ensure your <strong>phone number</strong> is registered with M-Pesa. A <strong>verification link</strong> will be sent to your email after registration.
          </p>
        </div>

        <button type="submit" class="btn-main" id="submit-btn">
          Create My Account
          <svg style="width:18px;height:18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
        </button>

        <p style="text-align:center;font-size:0.82rem;color:#64748B;font-weight:600;margin-top:1.25rem;">
          Already a trader?
          <a href="{{ route('login') }}" style="color:var(--blue);font-weight:900;text-decoration:none;margin-left:0.4rem;">Sign In →</a>
        </p>

      </form>
    </div>
  </div>
</div>

<script>
// ── Password Strength Checker ──────────────────────────────────────────────
const bars   = [s1, s2, s3, s4];
const colors = ['#EF4444','#F97316','#0F47AF','#068930'];
const labels = ['Weak','Fair','Good','Strong'];

function checkStrength(val) {
  let score = 0;
  if (val.length >= 8)              score++;
  if (/[A-Z]/.test(val))            score++;
  if (/[a-z]/.test(val))            score++;
  if (/[0-9]/.test(val))            score++;
  if (/[^A-Za-z0-9]/.test(val))     score++;

  // Update bars
  bars.forEach((b, i) => b.style.background = i < Math.ceil(score / 5 * 4) ? colors[Math.min(Math.ceil(score/5*4)-1, 3)] : '#E2E8F0');
  const sl = document.getElementById('strength-label');
  if (!val) { sl.textContent = 'Enter a password'; sl.style.color = '#94A3B8'; return; }
  const level = Math.min(Math.ceil(score / 5 * 4) - 1, 3);
  sl.textContent = labels[level] + ' Password';
  sl.style.color = colors[level];

  // Requirement flags
  setReq('r-len',   val.length >= 8);
  setReq('r-up',    /[A-Z]/.test(val));
  setReq('r-low',   /[a-z]/.test(val));
  setReq('r-num',   /[0-9]/.test(val));
  setReq('r-sym',   /[^A-Za-z0-9]/.test(val));
  checkMatch();
}

function setReq(id, ok) {
  const el = document.getElementById(id);
  el.classList.toggle('met', ok);
}

function checkMatch() {
  const p1 = document.getElementById('rp').value;
  const p2 = document.getElementById('rpc').value;
  setReq('r-match', p2.length > 0 && p1 === p2);
}

// ── Username live validation ───────────────────────────────────────────────
document.getElementById('un')?.addEventListener('input', function() {
  const val = this.value;
  const ok  = /^[a-zA-Z0-9_]{3,20}$/.test(val);
  this.classList.toggle('is-valid',   ok && val.length > 0);
  this.classList.toggle('is-invalid', !ok && val.length > 0);
});

// ── Submit feedback ────────────────────────────────────────────────────────
document.getElementById('reg-form')?.addEventListener('submit', function() {
  const btn = document.getElementById('submit-btn');
  btn.disabled = true;
  btn.innerHTML = '⏳ Creating Account...';
});
</script>
</body>
</html>