<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Official Violation Notice – {{ $violation->case_id }}</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <style>
    /* ── Reset & Base ── */
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    :root {
      --green-dark:   #0f2e18;
      --green-main:   #1E5128;
      --green-light:  #f0f7f0;
      --gold:         #D4A373;
      --red:          #dc2626;
      --blue:         #2563eb;
      --indigo:       #4f46e5;
      --amber:        #d97706;
      --slate-dark:   #1a202c;
      --slate-mid:    #374151;
      --slate-light:  #64748b;
    }

    html { font-size: 16px; }

    body {
      font-family: 'Inter', sans-serif;
      background: #e8edf3;
      color: var(--slate-dark);
      line-height: 1.6;
      padding: 40px 20px 80px;
    }

    /* ── Toolbar ── */
    .toolbar {
      max-width: 860px;
      margin: 0 auto 24px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      flex-wrap: wrap;
      gap: 12px;
    }
    .toolbar-brand { display: flex; align-items: center; gap: 10px; }
    .toolbar-brand .dot { width: 10px; height: 10px; border-radius: 50%; background: var(--green-main); }
    .toolbar-brand span { font-size: .75rem; font-weight: 800; text-transform: uppercase; letter-spacing: .1em; color: #64748b; }
    .toolbar-actions { display: flex; gap: 10px; }
    .tbtn {
      display: inline-flex;
      align-items: center;
      gap: 7px;
      padding: 9px 18px;
      border-radius: 10px;
      font-size: .72rem;
      font-weight: 800;
      text-transform: uppercase;
      letter-spacing: .06em;
      cursor: pointer;
      border: none;
      text-decoration: none;
      transition: all .2s ease;
    }
    .tbtn:hover { transform: translateY(-1px); }
    .tbtn-green { background: var(--green-main); color: #fff; box-shadow: 0 4px 14px rgba(30,81,40,.3); }
    .tbtn-red   { background: var(--red);        color: #fff; box-shadow: 0 4px 14px rgba(220,38,38,.3); }
    .tbtn-ghost { background: #fff; color: #374151; border: 2px solid #e2e8f0; }
    .tbtn-ghost:hover { border-color: var(--green-main); color: var(--green-main); }

    /* ── Paper ── */
    .paper {
      max-width: 860px;
      margin: 0 auto;
      background: #fff;
      border-radius: 20px;
      box-shadow: 0 20px 60px rgba(0,0,0,.12), 0 4px 16px rgba(0,0,0,.06);
      overflow: hidden;
      position: relative;
    }

    /* ── Top colour bar ── */
    .paper-top-bar {
      height: 5px;
      background: linear-gradient(90deg, var(--green-dark) 0%, var(--green-main) 40%, var(--gold) 55%, var(--green-main) 70%, var(--green-dark) 100%);
    }

    /* ── Watermark ── */
    .watermark {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%) rotate(-30deg);
      font-size: 6rem;
      font-weight: 900;
      font-family: 'Playfair Display', serif;
      text-transform: uppercase;
      letter-spacing: .3em;
      color: var(--green-main);
      opacity: .025;
      pointer-events: none;
      white-space: nowrap;
      user-select: none;
      z-index: 0;
    }

    /* ── Body padding ── */
    .paper-inner {
      padding: 56px 72px 64px;
      position: relative;
      z-index: 1;
    }
    @media(max-width: 640px){ .paper-inner { padding: 36px 24px 48px; } }

    /* ── LETTERHEAD ── */
    .lh {
      text-align: center;
      padding-bottom: 24px;
      margin-bottom: 28px;
      position: relative;
    }
    .lh::before {
      content: '';
      display: block;
      width: 48px;
      height: 4px;
      background: var(--gold);
      margin: 0 auto 14px;
      border-radius: 2px;
    }
    .lh::after {
      content: '';
      display: block;
      position: absolute;
      bottom: 0;
      left: 0;
      right: 0;
      height: 3px;
      background: linear-gradient(90deg, transparent, var(--green-main) 20%, var(--green-main) 80%, transparent);
    }
    .lh-republic {
      font-size: .68rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: .2em;
      color: #94a3b8;
      margin-bottom: 6px;
    }
    .lh-county {
      font-family: 'Playfair Display', Georgia, serif;
      font-size: 1.5rem;
      font-weight: 900;
      color: var(--green-dark);
      text-transform: uppercase;
      letter-spacing: .05em;
      line-height: 1.15;
    }
    .lh-dept {
      font-size: .78rem;
      font-weight: 700;
      color: var(--green-main);
      letter-spacing: .08em;
      text-transform: uppercase;
      margin-top: 5px;
    }
    .lh-market {
      font-size: .7rem;
      color: #94a3b8;
      margin-top: 3px;
    }
    .lh-divider {
      height: 1px;
      background: linear-gradient(90deg, transparent, #cbd5e1 20%, #cbd5e1 80%, transparent);
      margin: 10px 0 8px;
    }
    .lh-contact {
      font-size: .68rem;
      color: #94a3b8;
      letter-spacing: .04em;
    }

    /* ── META INFO ── */
    .meta-row {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      margin-bottom: 24px;
      font-size: .82rem;
      gap: 16px;
    }
    .meta-row .meta-left, .meta-row .meta-right { line-height: 1.8; }
    .meta-row .meta-right { text-align: right; }
    .meta-row b { color: #0f172a; }
    .meta-case-id { font-family: monospace; color: #1a4d7d; font-weight: 800; }

    /* ── SUBJECT ── */
    .subject {
      text-align: center;
      font-size: .92rem;
      font-weight: 800;
      text-transform: uppercase;
      text-decoration: underline;
      letter-spacing: .08em;
      color: var(--green-dark);
      margin: 28px 0;
      padding: 14px 24px;
      background: linear-gradient(135deg, #f0f7f0, #e8f4ea);
      border-radius: 12px;
      border-left: 5px solid var(--green-main);
    }

    /* ── SALUTATION & OPENING ── */
    .salute  { font-size: .88rem; margin-bottom: 12px; }
    .opening { font-size: .86rem; line-height: 1.85; margin-bottom: 22px; color: var(--slate-mid); }

    /* ── SECTION BADGE ── */
    .sec-badge {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      font-size: .68rem;
      font-weight: 900;
      text-transform: uppercase;
      letter-spacing: .1em;
      color: #fff;
      background: var(--green-main);
      padding: 5px 14px;
      border-radius: 6px;
      margin-bottom: 10px;
    }

    /* ── VIOLATION BOX ── */
    .vio-box {
      border: 1px solid #fecaca;
      border-left: 5px solid var(--red);
      background: #fff5f5;
      border-radius: 12px;
      padding: 18px 22px;
      margin-bottom: 20px;
      font-size: .84rem;
    }
    .vio-row { display: flex; gap: 12px; margin-bottom: 7px; align-items: baseline; }
    .vio-lbl { font-weight: 700; color: var(--slate-mid); min-width: 140px; flex-shrink: 0; }
    .vio-val { color: var(--slate-dark); }
    .vio-desc {
      margin-top: 12px;
      padding-top: 12px;
      border-top: 1px dashed #fca5a5;
      line-height: 1.75;
    }
    .vio-desc-lbl { font-weight: 700; margin-bottom: 5px; color: var(--slate-mid); }

    /* ── PAYMENT PILLS ── */
    .payment-row {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 12px;
      margin-bottom: 20px;
    }
    .payment-pill {
      border: 1px solid #e2e8f0;
      border-radius: 12px;
      padding: 14px 18px;
      text-align: center;
      background: #f8fafc;
    }
    .pp-label { font-size: .65rem; font-weight: 800; text-transform: uppercase; letter-spacing: .1em; color: #94a3b8; }
    .pp-val   { font-size: 1rem; font-weight: 900; color: var(--green-main); margin-top: 3px; }

    /* ── LAW BOX ── */
    .law-box {
      border: 1px solid #bfdbfe;
      border-left: 5px solid var(--blue);
      background: #eff6ff;
      border-radius: 12px;
      padding: 14px 20px;
      margin-bottom: 20px;
      font-size: .83rem;
      color: #1e3a5f;
      line-height: 1.7;
    }

    /* ── IMPACT BOX ── */
    .impact-box {
      border: 1px solid #fde68a;
      border-left: 5px solid var(--amber);
      background: #fffbeb;
      border-radius: 12px;
      padding: 12px 18px;
      margin-bottom: 20px;
      font-size: .83rem;
      color: #78350f;
    }

    /* ── DEADLINE ── */
    .deadline-box {
      background: linear-gradient(135deg, #fef2f2, #fee2e2);
      border: 1px solid #fca5a5;
      border-radius: 14px;
      padding: 16px 20px;
      margin-bottom: 20px;
      display: flex;
      align-items: center;
      gap: 14px;
    }
    .deadline-icon { font-size: 1.8rem; flex-shrink: 0; }
    .deadline-strong { font-size: .88rem; font-weight: 800; color: #991b1b; display: block; margin-bottom: 2px; }
    .deadline-text   { font-size: .82rem; color: #b91c1c; }

    /* ── CONSEQUENCES ── */
    .consequences-box {
      background: #f8fafc;
      border: 1px solid #e2e8f0;
      border-radius: 12px;
      padding: 16px 20px;
      margin-bottom: 20px;
      font-size: .84rem;
      line-height: 1.8;
      color: var(--slate-mid);
    }

    /* ── INSTRUCTIONS ── */
    .instructions-box {
      border: 1px solid #c7d2fe;
      border-left: 5px solid var(--indigo);
      background: #eef2ff;
      border-radius: 12px;
      padding: 16px 20px;
      margin-bottom: 20px;
      font-size: .84rem;
      line-height: 1.8;
      color: #312e81;
    }

    /* ── APPEAL ── */
    .appeal-box {
      background: #f8fafc;
      border: 1px dashed #cbd5e1;
      border-radius: 10px;
      padding: 12px 16px;
      margin-bottom: 24px;
      font-size: .78rem;
      color: #64748b;
      font-style: italic;
    }

    /* ── SIGNATURE ── */
    .sig-area { margin-top: 52px; }
    .sig-closer { font-size: .86rem; color: var(--slate-mid); margin-bottom: 28px; }
    .sig-flex { display: flex; align-items: flex-end; gap: 32px; }
    .sig-left { flex: 1; }
    .sig-right { flex: 0 0 auto; text-align: center; }

    .sig-line {
      border-top: 2.5px solid var(--green-dark);
      padding-top: 10px;
      min-height: 80px;
    }
    .sig-img { max-width: 150px; max-height: 65px; display: block; margin-bottom: 8px; }
    .sig-name  { font-size: .88rem; font-weight: 900; text-transform: uppercase; color: var(--green-dark); letter-spacing: .04em; }
    .sig-title { font-size: .75rem; color: #64748b; margin-top: 2px; }
    .sig-dept  { font-size: .68rem; color: #94a3b8; margin-top: 2px; }
    .sig-case  { font-size: .62rem; color: #cbd5e1; font-family: monospace; margin-top: 4px; }

    .stamp {
      border: 2px dashed var(--green-main);
      border-radius: 14px;
      padding: 18px 22px;
      min-width: 120px;
      min-height: 100px;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      opacity: .55;
      color: var(--green-main);
      font-size: .68rem;
      font-weight: 800;
      text-transform: uppercase;
      letter-spacing: .06em;
      text-align: center;
      gap: 5px;
    }
    .stamp-icon { font-size: 1.8rem; }

    /* ── CC + CONTACT ── */
    .cc-area {
      margin-top: 28px;
      padding-top: 16px;
      border-top: 1px dashed #e2e8f0;
      font-size: .76rem;
      color: #64748b;
      line-height: 1.8;
    }

    .contact-area {
      margin-top: 16px;
      padding-top: 14px;
      border-top: 1px solid #f1f5f9;
      font-size: .72rem;
      color: #94a3b8;
      text-align: center;
      line-height: 1.7;
    }

    /* ── Verification strip ── */
    .verify-strip {
      margin-top: 20px;
      padding: 10px 16px;
      background: var(--green-light);
      border: 1px solid #c6e0ca;
      border-radius: 10px;
      display: flex;
      justify-content: space-between;
      font-size: .7rem;
      font-weight: 700;
      color: var(--green-main);
      flex-wrap: wrap;
      gap: 6px;
    }

    /* ── Section spacing ── */
    .sec-group { margin-bottom: 24px; }

    /* ── Print styles ── */
    @media print {
      body       { background: #fff; padding: 0; }
      .toolbar   { display: none; }
      .paper     { box-shadow: none; border-radius: 0; max-width: none; }
      .paper-inner { padding: 20mm 22mm; }
    }
  </style>
</head>
<body>

  {{-- ── TOOLBAR (no-print) ── --}}
  <div class="toolbar">
    <div class="toolbar-brand">
      <div class="dot"></div>
      <span>Official Notice · {{ $violation->case_id }}</span>
    </div>
    <div class="toolbar-actions">
      <a href="{{ route('officer.violations.preview', $violation->id) }}" class="tbtn tbtn-ghost">
        ← Back to Preview
      </a>
      @if(in_array($violation->status, ['approved','sent']))
      <a href="{{ route('officer.violations.pdf', $violation->id) }}" class="tbtn tbtn-red" target="_blank">
        ⬇ Download PDF
      </a>
      @endif
      <button onclick="window.print()" class="tbtn tbtn-green">
        🖨 Print Letter
      </button>
    </div>
  </div>

  {{-- ── PAPER ── --}}
  <div class="paper">
    <div class="paper-top-bar"></div>
    <div class="watermark">Muthurwa</div>

    <div class="paper-inner">

      @php
        $ld      = $letterData ?? [];
        $safeGet = fn($k, $d='') => $ld[$k] ?? $d;
        $fmtDate = function($s) {
          try { return \Carbon\Carbon::parse($s)->format('d F Y'); }
          catch(\Exception $e){ return date('d F Y'); }
        };
      @endphp

      {{-- 1. LETTERHEAD --}}
      <div class="lh">
        <div class="lh-republic">Republic of Kenya</div>
        <div class="lh-county">{{ $safeGet('letter_head','Nairobi City County Government') }}</div>
        <div class="lh-dept">Market Enforcement Department</div>
        <div class="lh-divider"></div>
        <div class="lh-market">Muthurwa Market, Nairobi &nbsp;·&nbsp; P.O. Box 30075-00100</div>
        <div class="lh-contact">
          Tel: 0710618973 &nbsp;·&nbsp; Email: info@muthurwamarket.indevs.in
          &nbsp;·&nbsp; <em>"Promoting Orderly, Safe &amp; Compliant Markets"</em>
        </div>
      </div>

      {{-- 2. META --}}
      <div class="meta-row">
        <div class="meta-left">
          <div><b>Ref No:</b> {{ $safeGet('reference_number','NCC/MKT/'.date('Y')) }}</div>
          <div><b>Case ID:</b> <span class="meta-case-id">{{ $safeGet('case_id', $violation->case_id) }}</span></div>
          <div><b>Date:</b> {{ $fmtDate($safeGet('date_of_observation')) }}</div>
        </div>
        <div class="meta-right">
          <div><b>To:</b> {{ strtoupper($safeGet('recipient_name', optional($violation->trader)->name ?? 'THE TRADER')) }}</div>
          <div><b>Stall No:</b> {{ $safeGet('stall_number','N/A') }}</div>
          <div><b>Market:</b> Muthurwa Market, Nairobi</div>
        </div>
      </div>

      {{-- 3. SUBJECT --}}
      <div class="subject">
        {{ $safeGet('subject','OFFICIAL NOTICE OF VIOLATION – '.strtoupper($violation->violation_type ?? '')) }}
      </div>

      {{-- 4. SALUTATION --}}
      <p class="salute">
        Dear <strong>{{ ucwords(strtolower($safeGet('recipient_name', optional($violation->trader)->name ?? 'Trader'))) }}</strong>,
      </p>

      {{-- 5. OPENING --}}
      <p class="opening">{{ $safeGet('opening_statement') }}</p>

      {{-- 6. VIOLATION DETAILS --}}
      <div class="sec-group">
        <div class="sec-badge">🚨 Nature of Infringement</div>
        <div class="vio-box">
          <div class="vio-row"><span class="vio-lbl">Trader Name:</span><span class="vio-val">{{ ucwords(strtolower($safeGet('recipient_name', optional($violation->trader)->name ?? 'N/A'))) }}</span></div>
          <div class="vio-row"><span class="vio-lbl">Stall Number:</span><span class="vio-val">{{ $safeGet('stall_number','N/A') }}</span></div>
          <div class="vio-row"><span class="vio-lbl">Violation Type:</span><span class="vio-val">{{ $safeGet('violation_type', $violation->violation_type ?? 'N/A') }}</span></div>
          <div class="vio-row"><span class="vio-lbl">Date Observed:</span><span class="vio-val">{{ $fmtDate($safeGet('date_of_observation')) }}</span></div>
          <div class="vio-desc">
            <div class="vio-desc-lbl">Description of Infringement:</div>
            {!! nl2br(e($safeGet('violation_details', $violation->officer_notes ?? ''))) !!}
          </div>
        </div>
      </div>

      {{-- 7. PAYMENT --}}
      @if($safeGet('amount_due') || $safeGet('payment_period'))
      <div class="payment-row">
        <div class="payment-pill">
          <div class="pp-label">💰 Amount Due</div>
          <div class="pp-val">{{ $safeGet('amount_due','Per Market Tariff') }}</div>
        </div>
        <div class="payment-pill">
          <div class="pp-label">📅 Payment Period</div>
          <div class="pp-val">{{ $safeGet('payment_period','Daily') }}</div>
        </div>
      </div>
      @endif

      {{-- 8. STATUTORY BASIS --}}
      @if($safeGet('law_reference'))
      <div class="sec-group">
        <div class="sec-badge">📜 Statutory Basis</div>
        <div class="law-box">
          <strong>Legal Reference:</strong> {{ $safeGet('law_reference') }}
        </div>
      </div>
      @endif

      {{-- 9. COMMUNITY IMPACT --}}
      @if($safeGet('community_impact'))
      <div class="impact-box">
        <strong>⚠ Community &amp; Market Impact:</strong> {{ $safeGet('community_impact') }}
      </div>
      @endif

      {{-- 10. COMPLIANCE DEADLINE --}}
      <div class="sec-group">
        <div class="sec-badge">⏰ Compliance Deadline</div>
        <div class="deadline-box">
          <div class="deadline-icon">🚦</div>
          <div>
            <span class="deadline-strong">Immediate Action Required</span>
            <span class="deadline-text">{{ $safeGet('compliance_deadline','You are required to rectify this violation within 24 hours from the date and time of this notice.') }}</span>
          </div>
        </div>
      </div>

      {{-- 11. LEGAL CONSEQUENCES --}}
      <div class="sec-group">
        <div class="sec-badge">⚖️ Legal Consequences</div>
        <div class="consequences-box">
          {!! nl2br(e($safeGet('legal_consequences','Failure to comply with this official notice will result in immediate legal action before the Nairobi City Court, including revocation of your stall allocation, confiscation of goods, and permanent suspension of your trading privileges at Muthurwa Market.'))) !!}
        </div>
      </div>

      {{-- 12. REQUIRED ACTIONS --}}
      <div class="sec-group">
        <div class="sec-badge">✅ Required Corrective Actions</div>
        <div class="instructions-box">
          {!! nl2br(e($safeGet('instructions','Immediately cease the violation and report to the Market Enforcement Office for re-inspection and compliance verification within the stipulated time.'))) !!}
        </div>
      </div>

      {{-- 13. APPEAL RIGHTS --}}
      @if($safeGet('appeal_rights'))
      <div class="appeal-box">
        <strong>Right of Reply / Appeal:</strong> {{ $safeGet('appeal_rights') }}
      </div>
      @endif

      {{-- 14. SIGNATURE BLOCK --}}
      <div class="sig-area">
        <p class="sig-closer">Yours faithfully,</p>
        <div class="sig-flex">

          <div class="sig-left">
            <div class="sig-line">
              @if($violation->signature_path)
                @php
                  $spFull = storage_path('app/public/' . $violation->signature_path);
                  if(file_exists($spFull)){
                    $spExt = pathinfo($spFull, PATHINFO_EXTENSION);
                    $spB64 = 'data:image/'.$spExt.';base64,'.base64_encode(file_get_contents($spFull));
                  }
                @endphp
                @if(isset($spB64))
                  <img src="{{ $spB64 }}" class="sig-img" alt="Signature">
                @endif
              @endif
              <div class="sig-name">{{ $safeGet('officer_name', optional($violation->officer)->name ?? auth()->user()->name) }}</div>
              <div class="sig-title">{{ $safeGet('officer_title','Market Enforcement Officer') }}</div>
              <div class="sig-dept">Market Enforcement Department · Muthurwa Market, Nairobi</div>
              <div class="sig-case">Verified Case ID: {{ $violation->case_id }}</div>
            </div>
          </div>

          <div class="sig-right">
            <div style="position: relative; display: inline-block;">
              <img src="{{ asset('nairobi_county_stamp.png') }}" alt="Nairobi City County Stamp" style="width: 100px; height: 100px; transform: rotate(-3deg); opacity: 0.9; filter: drop-shadow(0 2px 4px rgba(30,81,40,0.15));">
            </div>
          </div>

        </div>
      </div>

      {{-- 15. CC SECTION --}}
      <div class="cc-area">
        <strong>CC:</strong><br>
        @php
          $ccStr = $safeGet('cc_section','Market Manager, Enforcement Department, File');
          foreach(explode(',', $ccStr) as $c) echo '• ' . trim($c) . '<br>';
        @endphp
      </div>

      {{-- 16. CONTACT DETAILS --}}
      <div class="contact-area">
        {!! nl2br(e($safeGet('contact_details',"Market Enforcement Office · Muthurwa Market · Nairobi City County\nTel: 0710618973 · Email: info@muthurwamarket.indevs.in"))) !!}
      </div>

      {{-- 17. VERIFICATION STRIP --}}
      <div class="verify-strip">
        <span>📋 Case: <strong>{{ $violation->case_id }}</strong></span>
        <span>📅 Generated: <strong>{{ now()->format('d M Y, H:i') }}</strong></span>
        <span>
          Status: <strong>{{ strtoupper(str_replace('_',' ',$violation->status)) }}</strong>
        </span>
      </div>

    </div>{{-- /paper-inner --}}
  </div>{{-- /paper --}}

</body>
</html>
