@extends('layouts.app')

@section('page-title', 'Violation Notice – ' . ($violation->case_id ?? 'Preview'))

@section('content')

{{-- ════════════════════════════════════════════════════════════ --}}
{{--  STYLES                                                      --}}
{{-- ════════════════════════════════════════════════════════════ --}}
<style>
  @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Playfair+Display:wght@700;900&display=swap');

  /* ── Animations ── */
  @keyframes fadeSlideUp { from { opacity:0; transform:translateY(16px); } to { opacity:1; transform:translateY(0); } }
  @keyframes spinPulse    { 0%,100% { transform:rotate(0deg) scale(1); } 50% { transform:rotate(180deg) scale(1.1); } }
  @keyframes shimmer      { 0% { background-position:-400px 0; } 100% { background-position:400px 0; } }
  @keyframes glow         { 0%,100%{box-shadow:0 0 0 0 rgba(30,81,40,.35);} 50%{box-shadow:0 0 0 8px rgba(30,81,40,0);} }

  .fade-up  { animation: fadeSlideUp .45s ease-out both; }
  .fa-d1    { animation-delay:.05s; }
  .fa-d2    { animation-delay:.12s; }
  .fa-d3    { animation-delay:.20s; }

  .spin-pulse { animation: spinPulse 1.4s ease-in-out infinite; }

  .skeleton {
    background: linear-gradient(90deg,#f0f0f0 25%,#e0e0e0 50%,#f0f0f0 75%);
    background-size: 800px 100%;
    animation: shimmer 1.5s infinite linear;
    border-radius: 6px;
  }

  /* ── Layout ── */
  .preview-grid { display:grid; grid-template-columns:340px 1fr; gap:24px; align-items:start; }
  @media(max-width:1024px){ .preview-grid { grid-template-columns:1fr; } }

  /* ── Control Panel ── */
  .ctrl-panel {
    background:#fff;
    border:1px solid #e2e8f0;
    border-radius:20px;
    overflow:hidden;
    box-shadow:0 4px 24px rgba(0,0,0,.07);
    position:sticky;
    top:90px;
  }
  .ctrl-header {
    padding:18px 22px;
    background:linear-gradient(135deg,#0f2e18,#1E5128,#2d6a2e);
    display:flex;
    align-items:center;
    gap:10px;
  }
  .ctrl-header h3 { color:#fff; font-size:.8rem; font-weight:900; letter-spacing:.08em; text-transform:uppercase; margin:0; }

  .ctrl-section { padding:18px 22px; border-bottom:1px solid #f1f5f9; }
  .ctrl-section:last-child { border-bottom:none; }
  .ctrl-label { font-size:.65rem; font-weight:800; text-transform:uppercase; letter-spacing:.1em; color:#94a3b8; margin-bottom:8px; display:block; }

  /* ── Tone Pills ── */
  .tone-grid { display:grid; grid-template-columns:1fr 1fr; gap:8px; }
  .tone-pill {
    padding:9px 10px;
    border-radius:12px;
    border:2px solid #e2e8f0;
    background:#f8fafc;
    cursor:pointer;
    text-align:center;
    transition:all .2s ease;
    font-size:.7rem;
    font-weight:800;
    text-transform:uppercase;
    letter-spacing:.04em;
    color:#64748b;
    user-select:none;
  }
  .tone-pill:hover  { border-color:#1E5128; color:#1E5128; background:#f0f7f0; }
  .tone-pill.active { border-color:#1E5128; background:#1E5128; color:#fff; }

  /* ── Regenerate Button ── */
  .regen-btn {
    width:100%;
    padding:14px;
    border-radius:14px;
    background:linear-gradient(135deg,#1E5128,#2d6a2e);
    color:#fff;
    font-size:.78rem;
    font-weight:900;
    text-transform:uppercase;
    letter-spacing:.08em;
    border:none;
    cursor:pointer;
    display:flex;
    align-items:center;
    justify-content:center;
    gap:8px;
    transition:all .25s ease;
    box-shadow:0 6px 20px rgba(30,81,40,.35);
    animation: glow 3s ease-in-out infinite;
  }
  .regen-btn:hover:not(:disabled) { transform:translateY(-2px); box-shadow:0 10px 28px rgba(30,81,40,.45); }
  .regen-btn:disabled { opacity:.6; cursor:not-allowed; transform:none; animation:none; }

  /* ── Status Badge ── */
  .status-badge { display:inline-flex; align-items:center; gap:6px; padding:5px 12px; border-radius:999px; font-size:.65rem; font-weight:800; text-transform:uppercase; letter-spacing:.08em; }

  /* ── Letter Paper ── */
  .letter-paper {
    background:#fff;
    border:1px solid #e2e8f0;
    border-radius:20px;
    box-shadow:0 8px 40px rgba(0,0,0,.1);
    overflow:hidden;
    position:relative;
  }
  .letter-toolbar {
    padding:14px 24px;
    background:linear-gradient(90deg,#f8fafc,#fff);
    border-bottom:1px solid #e2e8f0;
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:12px;
    flex-wrap:wrap;
  }
  .letter-body { padding:56px 72px; }
  @media(max-width:768px){ .letter-body { padding:32px 28px; } }

  /* ── Letter Content Typography ── */
  .ltr-head {
    text-align:center;
    border-bottom:3px double #1E5128;
    padding-bottom:20px;
    margin-bottom:28px;
    position:relative;
  }
  .ltr-head::before {
    content:'';
    display:block;
    width:60px;
    height:4px;
    background:#D4A373;
    margin:0 auto 14px;
    border-radius:2px;
  }
  .ltr-org    { font-family:'Playfair Display',Georgia,serif; font-size:1.2rem; font-weight:900; color:#0f2e18; letter-spacing:.04em; text-transform:uppercase; line-height:1.2; }
  .ltr-dept   { font-size:.75rem; font-weight:600; color:#1E5128; margin-top:4px; letter-spacing:.06em; text-transform:uppercase; }
  .ltr-addr   { font-size:.72rem; color:#64748b; margin-top:2px; }

  .ltr-meta   { display:flex; justify-content:space-between; margin-bottom:24px; font-size:.78rem; line-height:1.7; }
  .ltr-meta b { color:#1a202c; }

  .ltr-subject {
    text-align:center;
    font-size:.9rem;
    font-weight:800;
    text-decoration:underline;
    text-transform:uppercase;
    letter-spacing:.06em;
    color:#0f2e18;
    margin:28px 0;
    padding:10px 20px;
    background:linear-gradient(135deg,#f0f7f0,#e8f4ea);
    border-radius:10px;
    border-left:4px solid #1E5128;
  }

  .ltr-salute { font-size:.85rem; margin-bottom:14px; color:#1a202c; }
  .ltr-opening { font-size:.85rem; line-height:1.8; color:#2d3748; margin-bottom:20px; }

  .ltr-section { margin-bottom:22px; }
  .ltr-section-title {
    font-size:.7rem;
    font-weight:900;
    text-transform:uppercase;
    letter-spacing:.1em;
    color:#fff;
    background:#1E5128;
    padding:6px 14px;
    border-radius:6px;
    display:inline-block;
    margin-bottom:10px;
  }

  .ltr-violation-box {
    border:1px solid #e2e8f0;
    border-left:4px solid #dc2626;
    background:#fff5f5;
    border-radius:10px;
    padding:18px 20px;
    margin-bottom:20px;
    font-size:.83rem;
    line-height:1.7;
  }
  .ltr-violation-box .vb-row { display:flex; gap:10px; margin-bottom:6px; }
  .ltr-violation-box .vb-lbl { font-weight:700; color:#374151; min-width:130px; }
  .ltr-violation-box .vb-val { color:#1a202c; }

  .ltr-law-box {
    border:1px solid #dbeafe;
    border-left:4px solid #2563eb;
    background:#eff6ff;
    border-radius:10px;
    padding:14px 18px;
    margin-bottom:20px;
    font-size:.8rem;
    line-height:1.6;
    color:#1e3a5f;
  }

  .ltr-impact-box {
    border:1px solid #fde68a;
    border-left:4px solid #d97706;
    background:#fffbeb;
    border-radius:10px;
    padding:12px 18px;
    margin-bottom:20px;
    font-size:.8rem;
    color:#78350f;
  }

  .ltr-deadline {
    background:linear-gradient(135deg,#fef2f2,#fee2e2);
    border:1px solid #fca5a5;
    border-radius:12px;
    padding:14px 20px;
    margin-bottom:20px;
    display:flex;
    align-items:center;
    gap:12px;
  }
  .ltr-deadline-icon { font-size:1.5rem; }
  .ltr-deadline-txt strong { font-size:.85rem; font-weight:800; color:#991b1b; display:block; }
  .ltr-deadline-txt span { font-size:.78rem; color:#b91c1c; }

  .ltr-consequences {
    font-size:.82rem;
    line-height:1.75;
    color:#374151;
    padding:14px 18px;
    background:#f8fafc;
    border-radius:10px;
    margin-bottom:20px;
  }

  .ltr-instructions {
    border:1px solid #c7d2fe;
    border-left:4px solid #4f46e5;
    background:#eef2ff;
    border-radius:10px;
    padding:14px 18px;
    margin-bottom:20px;
    font-size:.82rem;
    line-height:1.7;
    color:#312e81;
  }

  .ltr-appeal {
    font-size:.77rem;
    color:#64748b;
    font-style:italic;
    padding:10px 14px;
    background:#f8fafc;
    border-radius:8px;
    margin-bottom:24px;
  }

  .ltr-payment-box {
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:12px;
    margin-bottom:20px;
  }
  .ltr-payment-item {
    border:1px solid #e2e8f0;
    border-radius:10px;
    padding:12px 16px;
    background:#f8fafc;
    text-align:center;
  }
  .ltr-payment-item .pi-label { font-size:.65rem; font-weight:700; text-transform:uppercase; letter-spacing:.08em; color:#94a3b8; }
  .ltr-payment-item .pi-val   { font-size:.95rem; font-weight:800; color:#1E5128; margin-top:4px; }

  .ltr-signature { margin-top:48px; }
  .ltr-sig-line { display:flex; gap:40px; }
  .ltr-sig-block { flex:1; }
  .ltr-sig-pad   { border-top:2px solid #0f2e18; padding-top:8px; min-height:60px; }
  .ltr-sig-name  { font-size:.82rem; font-weight:800; text-transform:uppercase; color:#0f2e18; }
  .ltr-sig-title { font-size:.72rem; color:#64748b; }
  .ltr-sig-img   { max-width:140px; max-height:60px; display:block; margin-bottom:8px; }

  .ltr-stamp {
    text-align:right;
    flex:1;
  }
  .ltr-stamp-box {
    display:inline-block;
    border:2px dashed #1E5128;
    border-radius:14px;
    padding:16px 22px;
    text-align:center;
    color:#1E5128;
    font-size:.72rem;
    font-weight:700;
    text-transform:uppercase;
    letter-spacing:.05em;
    opacity:.65;
  }

  .ltr-cc {
    font-size:.72rem;
    color:#64748b;
    margin-top:28px;
    padding-top:14px;
    border-top:1px dashed #e2e8f0;
    line-height:1.7;
  }

  .ltr-contact {
    margin-top:14px;
    font-size:.72rem;
    color:#94a3b8;
    text-align:center;
    line-height:1.6;
    padding-top:14px;
    border-top:1px solid #f1f5f9;
  }

  /* Watermark */
  .ltr-watermark {
    position:absolute;
    top:50%;
    left:50%;
    transform:translate(-50%,-50%) rotate(-30deg);
    font-size:5rem;
    font-weight:900;
    text-transform:uppercase;
    letter-spacing:.2em;
    color:#1E5128;
    opacity:.025;
    pointer-events:none;
    white-space:nowrap;
    user-select:none;
    z-index:0;
  }
  .ltr-content { position:relative; z-index:1; }

  /* ── Action Buttons ── */
  .action-bar { display:flex; gap:12px; flex-wrap:wrap; padding:18px 24px; background:#f8fafc; border-top:1px solid #e2e8f0; border-radius:0 0 20px 20px; }
  .btn-action {
    display:inline-flex;
    align-items:center;
    gap:8px;
    padding:11px 22px;
    border-radius:12px;
    font-size:.75rem;
    font-weight:800;
    text-transform:uppercase;
    letter-spacing:.06em;
    cursor:pointer;
    border:none;
    transition:all .2s ease;
    text-decoration:none;
    white-space:nowrap;
  }
  .btn-action:hover { transform:translateY(-1px); }
  .btn-action:active{ transform:scale(.97); }
  .btn-approve { background:linear-gradient(135deg,#1E5128,#2d6a2e); color:#fff; box-shadow:0 4px 14px rgba(30,81,40,.3); }
  .btn-email   { background:linear-gradient(135deg,#059669,#047857); color:#fff; box-shadow:0 4px 14px rgba(5,150,105,.3); }
  .btn-pdf     { background:linear-gradient(135deg,#dc2626,#b91c1c); color:#fff; box-shadow:0 4px 14px rgba(220,38,38,.3); }
  .btn-print   { background:linear-gradient(135deg,#7c3aed,#6d28d9); color:#fff; box-shadow:0 4px 14px rgba(124,58,237,.3); }
  .btn-outline { background:#fff; color:#374151; border:2px solid #e2e8f0; }
  .btn-outline:hover { border-color:#1E5128; color:#1E5128; }

  /* ── Signature Pad ── */
  .sig-pad-wrap {
    background:#fff;
    border:2px dashed #d1d5db;
    border-radius:14px;
    overflow:hidden;
    cursor:crosshair;
    display:block;
    width:100%;
    transition:border-color .2s;
  }
  .sig-pad-wrap:hover { border-color:#1E5128; }

  /* ── Toast ── */
  .toast-container { position:fixed; top:20px; right:20px; z-index:9999; display:flex; flex-direction:column; gap:10px; }
  .toast {
    min-width:280px;
    padding:14px 20px;
    border-radius:14px;
    font-size:.8rem;
    font-weight:700;
    display:flex;
    align-items:center;
    gap:10px;
    box-shadow:0 10px 30px rgba(0,0,0,.15);
    animation:fadeSlideUp .3s ease-out both;
  }
  .toast-success { background:#f0fdf4; border:1px solid #86efac; color:#166534; }
  .toast-error   { background:#fef2f2; border:1px solid #fca5a5; color:#991b1b; }
  .toast-info    { background:#eff6ff; border:1px solid #bfdbfe; color:#1e40af; }

  /* ── Overlay loading ── */
  #letter-loading-overlay {
    position:absolute;
    inset:0;
    background:rgba(255,255,255,.85);
    backdrop-filter:blur(4px);
    z-index:10;
    display:none;
    align-items:center;
    justify-content:center;
    flex-direction:column;
    gap:16px;
    border-radius:20px;
  }
  #letter-loading-overlay.active { display:flex; }
  .loading-icon { width:54px; height:54px; border:4px solid #e2e8f0; border-top-color:#1E5128; border-radius:50%; animation:spinPulse 1s linear infinite; }

  @media print {
    .ctrl-panel, .letter-toolbar, .action-bar, .no-print { display:none !important; }
    .letter-paper { box-shadow:none; border:none; border-radius:0; }
    .letter-body  { padding:0; }
    body { background:#fff; }
  }
</style>

{{-- Toast container --}}
<div class="toast-container" id="toastContainer"></div>

<div class="space-y-5 fade-up">

  {{-- ── Breadcrumb / Title ── --}}
  <div class="flex items-center justify-between flex-wrap gap-3 no-print">
    <div>
      <h1 class="text-xl font-black text-slate-800 tracking-tight" style="font-family:'Playfair Display',serif;">
        Violation Notice Preview
      </h1>
      <div class="flex items-center gap-2 mt-1 text-[10px] font-black uppercase tracking-widest text-slate-400">
        <span>Officer Portal</span>
        <span class="text-slate-300">›</span>
        <span>Violations</span>
        <span class="text-slate-300">›</span>
        <span class="font-mono text-blue-700">{{ $violation->case_id }}</span>
      </div>
    </div>
    @php
      $statusStyles = [
        'pending_ai'  => 'background:#fee2e2;color:#991b1b;border:1px solid #fecaca;',
        'draft_ready' => 'background:#fef3c7;color:#92400e;border:1px solid #fde68a;',
        'approved'    => 'background:#dcfce7;color:#166534;border:1px solid #bbf7d0;',
        'sent'        => 'background:#dbeafe;color:#1e40af;border:1px solid #bfdbfe;',
      ];
      $statusStyle = $statusStyles[$violation->status] ?? $statusStyles['pending_ai'];
      $statusLabel = ['pending_ai'=>'Pending','draft_ready'=>'Draft Ready','approved'=>'Approved','sent'=>'Sent'][$violation->status] ?? $violation->status;
    @endphp
    <div class="status-badge" style="{{ $statusStyle }}">
      <span class="w-2 h-2 rounded-full inline-block" style="background:currentColor; opacity:.6;"></span>
      {{ $statusLabel }}
    </div>
  </div>

  {{-- ── Two-Column Grid ── --}}
  <div class="preview-grid">

    {{-- ══════════ LEFT: CONTROL PANEL ══════════ --}}
    <div class="ctrl-panel no-print fa-d1 fade-up">

      {{-- Header --}}
      <div class="ctrl-header">
        <svg width="18" height="18" fill="none" stroke="#D4A373" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
        <h3>AI Letter Control</h3>
      </div>

      {{-- Case Summary --}}
      <div class="ctrl-section">
        <span class="ctrl-label">Case Details</span>
        <div class="space-y-2 text-[.75rem]">
          <div class="flex justify-between">
            <span class="text-slate-500 font-semibold">Trader</span>
            <span class="font-bold text-slate-800">{{ optional($violation->trader)->name ?? 'N/A' }}</span>
          </div>
          <div class="flex justify-between">
            <span class="text-slate-500 font-semibold">Violation</span>
            <span class="font-bold text-slate-800 text-right max-w-[150px]">{{ $violation->violation_type }}</span>
          </div>
          <div class="flex justify-between">
            <span class="text-slate-500 font-semibold">Date</span>
            <span class="font-bold text-slate-800">{{ $violation->created_at->format('d M Y') }}</span>
          </div>
          <div class="flex justify-between">
            <span class="text-slate-500 font-semibold">Case ID</span>
            <span class="font-mono font-bold text-blue-700 text-xs">{{ $violation->case_id }}</span>
          </div>
        </div>
      </div>

      {{-- Tone Selector --}}
      <div class="ctrl-section">
        <span class="ctrl-label">Letter Tone</span>
        <div class="tone-grid" id="tonePills">
          @foreach([
            ['formal',        '📋', 'Formal'],
            ['strict',        '⚖️',  'Strict'],
            ['final_warning', '🚨', 'Final Warning'],
            ['cordial',       '🤝', 'Cordial'],
          ] as [$val, $icon, $label])
          <div class="tone-pill {{ $val === 'formal' ? 'active' : '' }}" data-tone="{{ $val }}">
            {{ $icon }}<br>{{ $label }}
          </div>
          @endforeach
        </div>
      </div>

      {{-- Custom Instructions --}}
      <div class="ctrl-section">
        <span class="ctrl-label">Custom Instructions <span class="normal-case font-normal text-slate-300">(optional)</span></span>
        <textarea id="customInstructions" rows="3"
          class="w-full text-sm border-2 rounded-xl px-3 py-2.5 resize-none focus:outline-none transition-all"
          style="border-color:#e2e8f0; background:#f8fafc; font-size:.78rem;"
          placeholder="e.g. Mention prior warnings, add specific penalty amounts…"></textarea>
      </div>

      {{-- Regenerate Button --}}
      <div class="ctrl-section">
        <button class="regen-btn" id="regenBtn" onclick="regenerateLetter()">
          <svg id="regenIcon" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
          </svg>
          <span id="regenBtnText">Regenerate with Gemini AI</span>
        </button>
        <p class="text-center text-[.68rem] text-slate-400 mt-3">
          Powered by <strong class="text-[#4285F4]">Gemini 2.5 Flash</strong> · Letter updates instantly
        </p>
      </div>

      {{-- Evidence Photo --}}
      @if($violation->photo_path)
      <div class="ctrl-section">
        <span class="ctrl-label">Evidence Photo</span>
        <img src="{{ asset('storage/' . $violation->photo_path) }}"
             alt="Violation Evidence"
             class="w-full rounded-xl shadow-sm border-2 border-slate-100 object-cover"
             style="max-height:180px;">
      </div>
      @endif

      {{-- Violation Logs --}}
      @if($violation->logs && $violation->logs->count())
      <div class="ctrl-section">
        <span class="ctrl-label">Activity Log</span>
        <div class="space-y-2 max-h-40 overflow-y-auto">
          @foreach($violation->logs->sortByDesc('created_at')->take(6) as $log)
          <div class="flex items-start gap-2">
            <span class="w-1.5 h-1.5 rounded-full bg-green-500 mt-2 flex-shrink-0"></span>
            <div>
              <p class="text-[.72rem] font-semibold text-slate-700">{{ $log->action }}</p>
              <p class="text-[.65rem] text-slate-400">{{ optional($log->user)->name ?? 'System' }} · {{ $log->created_at->diffForHumans() }}</p>
            </div>
          </div>
          @endforeach
        </div>
      </div>
      @endif

    </div>{{-- /ctrl-panel --}}

    {{-- ══════════ RIGHT: LETTER PREVIEW ══════════ --}}
    <div>

      <div class="letter-paper fa-d2 fade-up" style="position:relative;">

        {{-- Loading Overlay --}}
        <div id="letter-loading-overlay">
          <div class="loading-icon"></div>
          <p class="text-sm font-bold text-slate-600">Generating with Gemini AI…</p>
          <p class="text-xs text-slate-400">Crafting your official notice…</p>
        </div>

        {{-- Toolbar --}}
        <div class="letter-toolbar no-print">
          <div class="flex items-center gap-2">
            <svg width="16" height="16" fill="none" stroke="#1E5128" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            <span class="text-xs font-black uppercase tracking-widest text-slate-600">Official Violation Notice</span>
          </div>
          <div class="flex items-center gap-2 text-[.68rem] font-bold text-slate-400">
            <span class="w-2 h-2 rounded-full bg-green-400 inline-block"></span>
            AI Generated · Muthurwa Market
          </div>
        </div>

        {{-- ── THE LETTER BODY ── --}}
        <div class="letter-body" id="letterContent">
          <div class="ltr-watermark">Muthurwa Market</div>
          <div class="ltr-content">

            @php
              $ld = $letterData ?? [];
              $safeGet = fn($key, $default='') => $ld[$key] ?? $default;
            @endphp

            {{-- 1. LETTERHEAD --}}
            <div class="ltr-head" id="ltr-head">
              <div class="ltr-org" id="ltr-org">{{ $safeGet('letter_head','NAIROBI CITY COUNTY GOVERNMENT') }}</div>
              <div class="ltr-dept">Market Enforcement Department · Muthurwa Market, Nairobi</div>
              <div class="ltr-addr">P.O. Box 30075-00100, Nairobi | Tel: 0710618973 | info@muthurwamarket.indevs.in</div>
            </div>

            {{-- 2. META (Ref, Date, To) --}}
            <div class="ltr-meta" id="ltr-meta">
              <div>
                <div><b>Ref No:</b> {{ $safeGet('reference_number', 'NCC/MKT/'.date('Y')) }}</div>
                <div><b>Case ID:</b> <span class="font-mono text-blue-700">{{ $safeGet('case_id', $violation->case_id) }}</span></div>
                <div><b>Date:</b>
                  @php
                    try { echo \Carbon\Carbon::parse($safeGet('date_of_observation'))->format('d F Y'); }
                    catch(\Exception $e){ echo date('d F Y'); }
                  @endphp
                </div>
              </div>
              <div style="text-align:right;">
                <div><b>To:</b> {{ strtoupper($safeGet('recipient_name', optional($violation->trader)->name ?? 'The Trader')) }}</div>
                <div><b>Stall:</b> {{ $safeGet('stall_number', $currentStall ?? 'N/A') }}</div>
                <div><b>Market:</b> Muthurwa Market, Nairobi</div>
              </div>
            </div>

            {{-- 3. SUBJECT --}}
            <div class="ltr-subject" id="ltr-subject">
              {{ $safeGet('subject', 'OFFICIAL NOTICE OF VIOLATION – ' . strtoupper($violation->violation_type ?? '')) }}
            </div>

            {{-- 4. SALUTATION --}}
            <p class="ltr-salute" id="ltr-salute">
              Dear <strong>{{ ucwords(strtolower($safeGet('recipient_name', optional($violation->trader)->name ?? 'Trader'))) }}</strong>,
            </p>

            {{-- 5. OPENING --}}
            <p class="ltr-opening" id="ltr-opening">{{ $safeGet('opening_statement') }}</p>

            {{-- 6. VIOLATION DETAILS BOX --}}
            <div class="ltr-section">
              <div class="ltr-section-title">🚨 Nature of Infringement</div>
              <div class="ltr-violation-box" id="ltr-violation-box">
                <div class="vb-row"><span class="vb-lbl">Trader:</span><span class="vb-val" id="vb-trader">{{ ucwords(strtolower($safeGet('recipient_name', optional($violation->trader)->name ?? 'N/A'))) }}</span></div>
                <div class="vb-row"><span class="vb-lbl">Stall Number:</span><span class="vb-val" id="vb-stall">{{ $safeGet('stall_number', $currentStall ?? 'N/A') }}</span></div>
                <div class="vb-row"><span class="vb-lbl">Violation Type:</span><span class="vb-val" id="vb-type">{{ $safeGet('violation_type', $violation->violation_type ?? 'N/A') }}</span></div>
                <div class="vb-row"><span class="vb-lbl">Date Observed:</span><span class="vb-val">
                  @php
                    try { echo \Carbon\Carbon::parse($safeGet('date_of_observation'))->format('d M Y'); }
                    catch(\Exception $e){ echo date('d M Y'); }
                  @endphp
                </span></div>
                <div style="margin-top:10px; padding-top:10px; border-top:1px dashed #fca5a5;">
                  <div class="vb-lbl" style="margin-bottom:6px;">Description of Infringement:</div>
                  <div id="ltr-violation-details" style="font-size:.83rem; color:#1a202c; line-height:1.7;">{!! nl2br(e($safeGet('violation_details', $violation->officer_notes ?? ''))) !!}</div>
                </div>
              </div>
            </div>

            {{-- 7. PAYMENT --}}
            @if($safeGet('amount_due') || $safeGet('payment_period'))
            <div class="ltr-payment-box" id="ltr-payment-box">
              <div class="ltr-payment-item">
                <div class="pi-label">💰 Amount Due</div>
                <div class="pi-val" id="pi-amount">{{ $safeGet('amount_due', 'Per Market Tariff') }}</div>
              </div>
              <div class="ltr-payment-item">
                <div class="pi-label">📅 Payment Period</div>
                <div class="pi-val" id="pi-period">{{ $safeGet('payment_period', 'Daily') }}</div>
              </div>
            </div>
            @endif

            {{-- 8. STATUTORY BASIS --}}
            @if($safeGet('law_reference'))
            <div class="ltr-section">
              <div class="ltr-section-title">📜 Statutory Basis</div>
              <div class="ltr-law-box" id="ltr-law-box">
                <strong>Legal Reference:</strong> {{ $safeGet('law_reference') }}
              </div>
            </div>
            @endif

            {{-- 9. COMMUNITY IMPACT --}}
            @if($safeGet('community_impact'))
            <div class="ltr-impact-box" id="ltr-impact-box">
              <strong>⚠ Community Impact:</strong> {{ $safeGet('community_impact') }}
            </div>
            @endif

            {{-- 10. DEADLINE --}}
            <div class="ltr-section">
              <div class="ltr-section-title">⏰ Compliance Deadline</div>
              <div class="ltr-deadline" id="ltr-deadline">
                <div class="ltr-deadline-icon">🚦</div>
                <div class="ltr-deadline-txt">
                  <strong>Immediate Action Required</strong>
                  <span>{{ $safeGet('compliance_deadline', 'You are required to rectify this violation within 24 hours.') }}</span>
                </div>
              </div>
            </div>

            {{-- 11. LEGAL CONSEQUENCES --}}
            <div class="ltr-section">
              <div class="ltr-section-title">⚖️ Legal Consequences</div>
              <div class="ltr-consequences" id="ltr-consequences">
                {!! nl2br(e($safeGet('legal_consequences','Failure to comply will result in legal action before the Nairobi City Court, including revocation of stall allocation, confiscation of goods, and suspension of trading privileges.'))) !!}
              </div>
            </div>

            {{-- 12. REQUIRED ACTIONS --}}
            <div class="ltr-section">
              <div class="ltr-section-title">✅ Required Corrective Actions</div>
              <div class="ltr-instructions" id="ltr-instructions">
                {!! nl2br(e($safeGet('instructions','Immediately cease the violation and report to the Market Enforcement Office for re-inspection.'))) !!}
              </div>
            </div>

            {{-- 13. APPEAL RIGHTS --}}
            @if($safeGet('appeal_rights'))
            <div class="ltr-appeal" id="ltr-appeal">
              <strong>Right of Reply:</strong> {{ $safeGet('appeal_rights') }}
            </div>
            @endif

            {{-- 14. SIGNATURE BLOCK --}}
            <div class="ltr-signature" id="ltr-signature">
              <p style="font-size:.83rem; margin-bottom:24px; color:#374151;">Yours faithfully,</p>
              <div class="ltr-sig-line">
                <div class="ltr-sig-block">
                  <div class="ltr-sig-pad">
                    @if($violation->signature_path)
                      @php
                        $sigPath = storage_path('app/public/' . $violation->signature_path);
                        if(file_exists($sigPath)):
                          $sigExt = pathinfo($sigPath, PATHINFO_EXTENSION);
                          $sigB64 = 'data:image/'.$sigExt.';base64,'.base64_encode(file_get_contents($sigPath));
                        endif;
                      @endphp
                      @if(isset($sigB64))
                        <img src="{{ $sigB64 }}" class="ltr-sig-img" alt="Officer Signature">
                      @endif
                    @endif
                    <div class="ltr-sig-name" id="ltr-sig-name">{{ $safeGet('officer_name', optional($violation->officer)->name ?? auth()->user()->name) }}</div>
                    <div class="ltr-sig-title" id="ltr-sig-title">{{ $safeGet('officer_title', 'Market Enforcement Officer') }}</div>
                    <div style="font-size:.7rem; color:#94a3b8; margin-top:4px;">Market Enforcement Department · Muthurwa Market</div>
                    <div style="font-size:.65rem; color:#cbd5e1; font-family:monospace; margin-top:2px;">Verified Case: {{ $violation->case_id }}</div>
                  </div>
                </div>
                <div class="ltr-stamp">
                  <div style="position: relative; display: inline-block;">
                    <img src="{{ asset('nairobi_county_stamp.png') }}" alt="Nairobi City County Stamp" style="width: 100px; height: 100px; transform: rotate(-5deg); opacity: 0.85; filter: drop-shadow(0 2px 4px rgba(30,81,40,0.15));">
                  </div>
                </div>
              </div>
            </div>

            {{-- 15. CC + CONTACT --}}
            <div class="ltr-cc" id="ltr-cc">
              <strong>CC:</strong><br>
              @php
                $cc = $safeGet('cc_section','Market Manager, Enforcement Department, File');
                foreach(explode(',', $cc) as $c): echo '• ' . trim($c) . '<br>'; endforeach;
              @endphp
            </div>
            <div class="ltr-contact" id="ltr-contact">
              {!! nl2br(e($safeGet('contact_details', "Market Enforcement Office · Muthurwa Market · Nairobi\nTel: 0710618973 · Email: info@muthurwamarket.indevs.in"))) !!}
            </div>

          </div>{{-- /ltr-content --}}
        </div>{{-- /letter-body --}}

        {{-- ── SIGNATURE PAD (only when not approved/sent) ── --}}
        @if(!in_array($violation->status, ['approved','sent']))
        <div class="p-6 border-t border-slate-100 no-print" id="sigPadSection">
          <p class="text-xs font-bold uppercase tracking-widest text-slate-500 mb-3">Officer Digital Signature <span class="text-red-500">*</span></p>
          <canvas id="signature-pad" class="sig-pad-wrap" width="600" height="120" style="height:120px;"></canvas>
          <div class="flex items-center gap-3 mt-2">
            <button type="button" id="clear-signature" class="text-xs font-bold text-red-500 hover:text-red-700 underline transition-colors">
              ✕ Clear
            </button>
            <span class="text-[.68rem] text-slate-400 italic">Draw your signature above before finalising</span>
          </div>
        </div>
        @endif

        {{-- ── ACTION BAR ── --}}
        <div class="action-bar no-print">

          {{-- Finalise --}}
          @if(!in_array($violation->status, ['approved','sent']))
          <form id="finalize-form" method="POST" action="{{ route('officer.violations.approve', $violation->id) }}" style="display:contents;">
            @csrf
            <input type="hidden" name="signature_data" id="signature_data">
            <button type="button" id="finalize-btn" class="btn-action btn-approve">
              <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
              Finalise & Sign
            </button>
          </form>
          @endif

          {{-- Send Email --}}
          <form method="POST" action="{{ route('officer.violations.sendEmail', $violation->id) }}" style="display:contents;">
            @csrf
            <button type="submit" class="btn-action btn-email">
              <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
              Send via Email
            </button>
          </form>

          {{-- Download PDF --}}
          @if(in_array($violation->status, ['approved','sent']))
          <a href="{{ route('officer.violations.pdf', $violation->id) }}" class="btn-action btn-pdf" target="_blank">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
            Download PDF
          </a>
          @endif

          {{-- Print --}}
          <button onclick="window.print()" class="btn-action btn-print">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
            Print Letter
          </button>

          {{-- Back --}}
          <a href="{{ route('officer.violations.index') }}" class="btn-action btn-outline" style="margin-left:auto;">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
            Back
          </a>

        </div>{{-- /action-bar --}}

      </div>{{-- /letter-paper --}}

    </div>{{-- /right col --}}

  </div>{{-- /preview-grid --}}

</div>{{-- /space-y-5 --}}


{{-- ════════════════════════════════════════════════════════════ --}}
{{--  JAVASCRIPT                                                   --}}
{{-- ════════════════════════════════════════════════════════════ --}}
<script>
/* ── CSRF Token ── */
const CSRF = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';

/* ── Tone Pills ── */
let selectedTone = 'formal';
document.querySelectorAll('.tone-pill').forEach(pill => {
  pill.addEventListener('click', function() {
    document.querySelectorAll('.tone-pill').forEach(p => p.classList.remove('active'));
    this.classList.add('active');
    selectedTone = this.dataset.tone;
  });
});

/* ── Toast ── */
function showToast(msg, type='success') {
  const t = document.getElementById('toastContainer');
  const icons = { success:'✅', error:'❌', info:'ℹ️' };
  const div = document.createElement('div');
  div.className = `toast toast-${type}`;
  div.innerHTML = `<span>${icons[type]||'💬'}</span><span>${msg}</span>`;
  t.prepend(div);
  setTimeout(()=>{ div.style.opacity='0'; div.style.transform='translateY(-10px)'; div.style.transition='all .3s'; setTimeout(()=>div.remove(),300); }, 4000);
}

/* ── Escape HTML helper ── */
const esc = s => String(s??'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
const nl2br = s => esc(s).replace(/\n/g,'<br>');
const fmtDate = s => { try { return new Date(s).toLocaleDateString('en-GB',{day:'2-digit',month:'long',year:'numeric'}); } catch(e){ return s; }};

/* ── Regenerate Letter ── */
async function regenerateLetter() {
  const btn    = document.getElementById('regenBtn');
  const icon   = document.getElementById('regenIcon');
  const text   = document.getElementById('regenBtnText');
  const overlay= document.getElementById('letter-loading-overlay');

  btn.disabled = true;
  icon.classList.add('spin-pulse');
  text.textContent = 'Generating…';
  overlay.classList.add('active');

  const instructions = document.getElementById('customInstructions').value.trim();

  try {
    const res = await fetch('{{ route("officer.violations.regenerate", $violation->id) }}', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': CSRF,
        'Accept': 'application/json',
      },
      body: JSON.stringify({ tone: selectedTone, instructions: instructions })
    });

    const data = await res.json();

    if (data.success && data.letter_data) {
      updateLetterDOM(data.letter_data);
      showToast('Letter regenerated successfully!', 'success');
    } else {
      showToast(data.message || 'Could not regenerate letter.', 'error');
    }
  } catch(err) {
    showToast('Network error: ' + err.message, 'error');
  } finally {
    btn.disabled = false;
    icon.classList.remove('spin-pulse');
    text.textContent = 'Regenerate with Gemini AI';
    overlay.classList.remove('active');
  }
}

/* ── Update Letter DOM without page reload ── */
function updateLetterDOM(d) {
  const set = (id, val) => { const el = document.getElementById(id); if(el && val!=null) el.innerHTML = esc(val); };
  const setHtml = (id, html) => { const el = document.getElementById(id); if(el) el.innerHTML = html; };

  set('ltr-org',    d.letter_head);
  set('ltr-subject', d.subject);
  set('ltr-salute', `Dear <strong>${esc(d.recipient_name ?? '')}</strong>,`);
  set('ltr-opening', d.opening_statement);
  set('vb-trader',  d.recipient_name);
  set('vb-stall',   d.stall_number);
  set('vb-type',    d.violation_type);
  setHtml('ltr-violation-details', nl2br(d.violation_details));
  set('pi-amount',  d.amount_due);
  set('pi-period',  d.payment_period);
  set('ltr-law-box', `<strong>Legal Reference:</strong> ${esc(d.law_reference)}`);
  set('ltr-impact-box', `<strong>⚠ Community Impact:</strong> ${esc(d.community_impact)}`);
  setHtml('ltr-consequences', nl2br(d.legal_consequences));
  setHtml('ltr-instructions', nl2br(d.instructions));
  set('ltr-appeal', d.appeal_rights ? `<strong>Right of Reply:</strong> ${esc(d.appeal_rights)}` : '');
  set('ltr-sig-name', d.officer_name);
  set('ltr-sig-title', d.officer_title);
  set('ltr-stamp', d.official_stamp_section);

  if(d.deadline || d.compliance_deadline) {
    const deadlineEl = document.querySelector('.ltr-deadline-txt strong');
    const deadlineSpan = document.querySelector('.ltr-deadline-txt span');
    if(deadlineEl) deadlineEl.textContent = 'Immediate Action Required';
    if(deadlineSpan) deadlineSpan.textContent = d.compliance_deadline || '';
  }

  // Update meta (ref, date, recipient)
  const metaEl = document.getElementById('ltr-meta');
  if(metaEl && d.reference_number) {
    metaEl.querySelector('div:first-child').innerHTML =
      `<div><b>Ref No:</b> ${esc(d.reference_number)}</div>
       <div><b>Case ID:</b> <span class="font-mono text-blue-700">${esc(d.case_id||'')}</span></div>
       <div><b>Date:</b> ${fmtDate(d.date_of_observation)}</div>`;
    const right = metaEl.querySelector('div:last-child');
    if(right) right.innerHTML =
      `<div><b>To:</b> ${esc((d.recipient_name||'').toUpperCase())}</div>
       <div><b>Stall:</b> ${esc(d.stall_number||'N/A')}</div>
       <div><b>Market:</b> Muthurwa Market, Nairobi</div>`;
  }

  // Update CC
  const ccEl = document.getElementById('ltr-cc');
  if(ccEl && d.cc_section) {
    const parts = d.cc_section.split(',').map(c=>'• '+c.trim()).join('<br>');
    ccEl.innerHTML = `<strong>CC:</strong><br>${parts}`;
  }

  // Update contact
  const contactEl = document.getElementById('ltr-contact');
  if(contactEl && d.contact_details) contactEl.innerHTML = nl2br(d.contact_details);

  // Flash effect
  const paper = document.querySelector('.letter-body');
  paper.style.transition='opacity .3s';
  paper.style.opacity='0.3';
  setTimeout(()=>{ paper.style.opacity='1'; }, 300);
}

/* ── Signature Pad ── */
(function() {
  const canvas = document.getElementById('signature-pad');
  if (!canvas) return;

  const ctx = canvas.getContext('2d');
  ctx.strokeStyle = '#0f2e18';
  ctx.lineWidth   = 2.5;
  ctx.lineCap     = 'round';
  ctx.lineJoin    = 'round';
  let isDrawing   = false;
  let hasDrawn    = false;

  function coords(e) {
    const r = canvas.getBoundingClientRect();
    const scaleX = canvas.width  / r.width;
    const scaleY = canvas.height / r.height;
    if (e.touches && e.touches.length) {
      return { x:(e.touches[0].clientX-r.left)*scaleX, y:(e.touches[0].clientY-r.top)*scaleY };
    }
    return { x:(e.clientX-r.left)*scaleX, y:(e.clientY-r.top)*scaleY };
  }

  canvas.addEventListener('mousedown',  e => { isDrawing=true; const c=coords(e); ctx.beginPath(); ctx.moveTo(c.x,c.y); e.preventDefault(); });
  canvas.addEventListener('mousemove',  e => { if(!isDrawing)return; const c=coords(e); ctx.lineTo(c.x,c.y); ctx.stroke(); hasDrawn=true; e.preventDefault(); });
  canvas.addEventListener('mouseup',    ()=> isDrawing=false);
  canvas.addEventListener('mouseleave', ()=> isDrawing=false);

  canvas.addEventListener('touchstart', e => { isDrawing=true; const c=coords(e); ctx.beginPath(); ctx.moveTo(c.x,c.y); e.preventDefault(); }, {passive:false});
  canvas.addEventListener('touchmove',  e => { if(!isDrawing)return; const c=coords(e); ctx.lineTo(c.x,c.y); ctx.stroke(); hasDrawn=true; e.preventDefault(); }, {passive:false});
  canvas.addEventListener('touchend',   ()=> isDrawing=false);

  const clearBtn = document.getElementById('clear-signature');
  if(clearBtn) clearBtn.addEventListener('click', ()=>{ ctx.clearRect(0,0,canvas.width,canvas.height); hasDrawn=false; });

  const finalizeBtn = document.getElementById('finalize-btn');
  if(finalizeBtn) {
    finalizeBtn.addEventListener('click', function() {
      if(!hasDrawn) { showToast('Please draw your signature first.','error'); return; }
      document.getElementById('signature_data').value = canvas.toDataURL('image/png');
      showToast('Submitting…','info');
      document.getElementById('finalize-form').submit();
    });
  }
})();
</script>

@endsection
