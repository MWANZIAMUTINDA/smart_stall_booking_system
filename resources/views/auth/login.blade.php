<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="description" content="Sign in to Muthurwa Stall Booking — Nairobi County Portal.">
<title>Sign In — Muthurwa Stall Booking</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;900&display=swap" rel="stylesheet">
@vite(['resources/css/app.css', 'resources/js/app.js'])
<style>
*,*::before,*::after{box-sizing:border-box;}
html,body{margin:0;padding:0;min-height:100vh;font-family:'Inter',sans-serif;overflow:hidden;}

/* ── Nairobi Official Palette ── */
:root {
  --nairobi-green: #068930;
  --nairobi-yellow: #FCDD07;
  --nairobi-blue: #0F47AF;
  --rich-black: #1A1A1B;
}

/* ── Dynamic Nairobi Background ── */
@keyframes nairobiFlow {
  0% { background-position: 0% 50%; }
  50% { background-position: 100% 50%; }
  100% { background-position: 0% 50%; }
}
.bg-nairobi {
  background: linear-gradient(135deg, #068930, #057529, #046122, #0F47AF);
  background-size: 400% 400%;
  animation: nairobiFlow 15s ease infinite;
}

/* ── Skyline Silhouette ── */
.skyline-watermark {
  position: absolute;
  bottom: 0;
  left: 0;
  width: 100%;
  opacity: 0.1;
  pointer-events: none;
  z-index: 1;
}

/* ── Layout ── */
.split { display: flex; min-height: 100vh; }
.pl { flex: 0 0 52%; position: relative; overflow: hidden; display: flex; flex-direction: column; justify-content: center; padding: 4rem; }
.pr { flex: 1; background: #fff; display: flex; flex-direction: column; justify-content: center; padding: 4rem; position: relative; z-index: 10; border-left: 8px solid var(--nairobi-yellow); }

@media(max-width:1024px){
  .pl { flex: 0 0 40%; padding: 2rem; }
}
@media(max-width:768px){
  .split { flex-direction: column; }
  .pl { flex: 0 0 auto; padding: 3rem 2rem; }
  .pr { padding: 2rem; border-left: none; border-top: 8px solid var(--nairobi-yellow); }
}

/* ── Typography & UI ── */
.pill {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  background: #E7F3EF;
  border: 1px solid rgba(6,137,48,0.2);
  padding: 0.4rem 1rem;
  border-radius: 999px;
  font-size: 0.75rem;
  font-weight: 900;
  color: var(--nairobi-green);
  text-transform: uppercase;
  letter-spacing: 0.1em;
  margin-bottom: 2rem;
}

.fg { position: relative; margin-bottom: 1.5rem; }
.fg input {
  width: 100%;
  height: 60px;
  padding: 1.5rem 1rem 0.5rem;
  border: 2px solid #F1F5F9;
  border-radius: 1.25rem;
  font-size: 1rem;
  font-weight: 600;
  color: var(--rich-black);
  background: #F8FAFC;
  outline: none;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}
.fg input:focus {
  border-color: var(--nairobi-blue);
  background: #fff;
  box-shadow: 0 0 0 4px rgba(15,71,175,0.1);
}
.fg label {
  position: absolute;
  left: 1.25rem;
  top: 50%;
  transform: translateY(-50%);
  font-size: 0.95rem;
  font-weight: 500;
  color: #94A3B8;
  pointer-events: none;
  transition: all 0.2s ease;
}
.fg input:focus+label, .fg input:not(:placeholder-shown)+label {
  top: 0.8rem;
  transform: none;
  font-size: 0.65rem;
  font-weight: 900;
  color: var(--nairobi-blue);
  text-transform: uppercase;
  letter-spacing: 0.05em;
}

/* ── CTA Button ── */
.btn-nairobi {
  width: 100%;
  height: 60px;
  border: none;
  border-radius: 1.5rem;
  background: var(--nairobi-green);
  color: #fff;
  font-size: 1.1rem;
  font-weight: 900;
  cursor: pointer;
  transition: all 0.3s;
  box-shadow: 0 10px 25px rgba(6,137,48,0.3);
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.75rem;
}
.btn-nairobi:hover {
  transform: translateY(-3px);
  box-shadow: 0 15px 35px rgba(6,137,48,0.45);
  background: #057529;
}
.btn-nairobi:active { transform: scale(0.98); }

/* ── Emblem ── */
.emblem-box {
  background: rgba(255,255,255,0.15);
  backdrop-filter: blur(20px);
  border: 1px solid rgba(255,255,255,0.2);
  padding: 1.5rem;
  border-radius: 2.5rem;
  width: fit-content;
  margin-bottom: 2.5rem;
  box-shadow: inset 0 0 20px rgba(255,255,255,0.1);
}

.feature-tag {
  background: rgba(255,255,255,0.08);
  border: 1px solid rgba(255,255,255,0.12);
  padding: 0.75rem 1.25rem;
  border-radius: 1.25rem;
  display: flex;
  align-items: center;
  gap: 1rem;
  margin-bottom: 1rem;
  transition: all 0.3s;
}
.feature-tag:hover {
  background: rgba(255,255,255,0.15);
  transform: translateX(8px);
}
</style>
</head>
<body>
<div class="split">

  <!-- ══ LEFT PANEL (Nairobi Green) ══ -->
  <div class="pl bg-nairobi">
    <!-- Skyline silhouette -->
    <svg class="skyline-watermark" viewBox="0 0 800 200" fill="#FFFFFF">
      <path d="M0 200V180H20V150H40V180H60V140H80V180H100V100H130V180H150V120H180V180H200V60H240V180H260V140H290V180H310V90H350V180H370V130H400V180H420V40H460V180H480V120H510V180H530V80H570V180H590V140H620V180H640V110H680V180H700V150H730V180H750V130H780V180H800V200H0Z" />
    </svg>

    <div style="position:relative;z-index:10;">
      <div class="emblem-box">
        <span class="text-5xl block transform -rotate-12">🦁</span>
      </div>

      <h1 style="font-size:3rem;font-weight:900;color:#fff;line-height:0.9;letter-spacing:-0.03em;">
        <span style="color:var(--nairobi-yellow);text-transform:uppercase;font-size:0.75rem;letter-spacing:0.4em;display:block;margin-bottom:0.75rem;">Nairobi City County</span>
        Muthurwa<br>
        <span style="color:rgba(255,255,255,0.6);font-size:1.75rem;">Digital Portal</span>
      </h1>
      
      <p style="color:rgba(255,255,255,0.4);font-weight:600;text-transform:uppercase;letter-spacing:0.2em;font-size:0.65rem;margin-top:1.5rem;margin-bottom:3rem;">Green City in the Sun</p>

      <div class="feature-tag">
        <div style="width:40px;height:40px;background:var(--nairobi-yellow);border-radius:1rem;display:flex;align-items:center;justify-content:center;font-size:1.2rem;">🏪</div>
        <div>
          <div style="color:#fff;font-weight:800;font-size:0.9rem;">Live Market Map</div>
          <div style="color:rgba(255,255,255,0.5);font-size:0.7rem;">Real-time stall availability</div>
        </div>
      </div>

      <div class="feature-tag">
        <div style="width:40px;height:40px;background:var(--nairobi-blue);border-radius:1rem;display:flex;align-items:center;justify-content:center;font-size:1.2rem;">📱</div>
        <div>
          <div style="color:#fff;font-weight:800;font-size:0.9rem;">M-Pesa Verified</div>
          <div style="color:rgba(255,255,255,0.5);font-size:0.7rem;">Secure instant mobile payments</div>
        </div>
      </div>

      <div class="feature-tag">
        <div style="width:40px;height:40px;background:rgba(255,255,255,0.2);border-radius:1rem;display:flex;align-items:center;justify-content:center;font-size:1.2rem;">📜</div>
        <div>
          <div style="color:#fff;font-weight:800;font-size:0.9rem;">Digital Receipts</div>
          <div style="color:rgba(255,255,255,0.5);font-size:0.7rem;">Official QR-coded reservations</div>
        </div>
      </div>

      <p style="color:rgba(255,255,255,0.2);font-size:0.6rem;font-weight:700;text-transform:uppercase;letter-spacing:0.1em;margin-top:4rem;">
        &copy; {{ date('Y') }} Nairobi City County Government. All rights reserved.
      </p>
    </div>
  </div>

  <!-- ══ RIGHT PANEL (Login Form) ══ -->
  <div class="pr">
    <div style="max-width:440px;width:100%;margin:auto;">
      
      <div class="pill">
        <span class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></span>
        Official Trader Access
      </div>

      <h2 style="font-size:2.5rem;font-weight:900;color:var(--rich-black);margin:0 0 0.5rem;letter-spacing:-0.02em;">Welcome back 👋</h2>
      <p style="color:#64748B;font-weight:500;margin-bottom:3rem;">Sign in to manage your Muthurwa reservations.</p>

      <x-auth-session-status class="mb-6 p-4 bg-emerald-50 text-emerald-700 rounded-2xl border border-emerald-100 font-bold text-sm" :status="session('status')" />

      <form method="POST" action="{{ route('login') }}" novalidate>
        @csrf

        <!-- Email -->
        <div class="fg">
          <input type="email" id="em" name="email" value="{{ old('email') }}" placeholder=" " required autocomplete="username">
          <label for="em">Email Address</label>
        </div>
        @error('email')<p style="color:#EF4444;font-size:0.75rem;font-weight:700;margin-top:-1rem;margin-bottom:1rem;margin-left:0.5rem;">⚠ {{ $message }}</p>@enderror

        <!-- Password -->
        <div class="fg" style="position:relative;">
          <input type="password" id="pw" name="password" placeholder=" " required autocomplete="current-password">
          <label for="pw">Password</label>
          <button type="button" id="tpb" style="position:absolute;right:1rem;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:#94A3B8;">
            <svg id="eo" style="width:20px;height:20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
          </button>
        </div>
        @error('password')<p style="color:#EF4444;font-size:0.75rem;font-weight:700;margin-top:-1rem;margin-bottom:1rem;margin-left:0.5rem;">⚠ {{ $message }}</p>@enderror

        <div style="display:flex;align-items:center;justify-content:between;margin-bottom:2rem;">
          <label style="display:flex;align-items:center;gap:0.75rem;font-size:0.85rem;color:#64748B;font-weight:600;cursor:pointer;flex:1;">
            <input type="checkbox" name="remember" style="width:18px;height:18px;accent-color:var(--nairobi-green);border-radius:6px;"> Remember me
          </label>
          @if(Route::has('password.request'))
            <a href="{{ route('password.request') }}" style="color:var(--nairobi-blue);font-weight:700;font-size:0.85rem;text-decoration:none;hover:underline;">Forgot?</a>
          @endif
        </div>

        <button type="submit" class="btn-nairobi">
          Sign In to Portal
          <svg style="width:20px;height:20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
        </button>

        <div style="display:flex;align-items:center;gap:1rem;margin:2.5rem 0;">
          <div style="flex:1;height:1px;background:#F1F5F9;"></div>
          <span style="font-size:0.75rem;font-weight:800;color:#CBD5E1;text-transform:uppercase;letter-spacing:0.1em;">or</span>
          <div style="flex:1;height:1px;background:#F1F5F9;"></div>
        </div>

        <p style="text-align:center;font-size:0.9rem;color:#64748B;font-weight:600;">
          New trader? 
          <a href="{{ route('register') }}" style="color:var(--nairobi-blue);font-weight:900;text-decoration:none;margin-left:0.5rem;border-bottom:2px solid transparent;hover:border-var(--nairobi-blue);transition:all 0.2s;">Create Account</a>
        </p>
      </form>

    </div>
  </div>
</div>

<script>
  /* Password toggle */
  document.getElementById('tpb')?.addEventListener('click', function() {
    const pw = document.getElementById('pw');
    pw.type = pw.type === 'password' ? 'text' : 'password';
  });

  /* Submit feedback */
  document.querySelector('form')?.addEventListener('submit', function(e) {
    const btn = this.querySelector('button[type="submit"]');
    btn.innerHTML = 'Verifying Access...';
    btn.style.opacity = '0.7';
    btn.style.pointerEvents = 'none';
  });
</script>
</body>
</html>