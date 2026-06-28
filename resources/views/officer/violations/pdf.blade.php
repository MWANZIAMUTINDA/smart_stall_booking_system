<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Violation Notice – {{ $violation->case_id }}</title>
  <style>
    /* ── Base ── */
    * { box-sizing: border-box; margin: 0; padding: 0; }

    body {
      font-family: 'Times New Roman', Times, serif;
      font-size: 11pt;
      line-height: 1.65;
      color: #111;
      background: #fff;
      margin: 0;
      padding: 0;
    }

    /* ── Page Layout ── */
    .page {
      max-width: 210mm;
      margin: 0 auto;
      padding: 18mm 20mm 18mm 20mm;
      min-height: 297mm;
      background: #fff;
      position: relative;
    }

    /* ── Watermark ── */
    .watermark {
      position: fixed;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%) rotate(-35deg);
      font-size: 72pt;
      font-weight: 900;
      font-family: Arial, sans-serif;
      color: #1E5128;
      opacity: 0.04;
      letter-spacing: 8px;
      white-space: nowrap;
      pointer-events: none;
      z-index: 0;
    }

    /* ── Letterhead ── */
    .letterhead {
      text-align: center;
      border-bottom: 3px double #1E5128;
      padding-bottom: 14px;
      margin-bottom: 18px;
      position: relative;
    }

    .letterhead-accent {
      width: 50px;
      height: 3px;
      background: #D4A373;
      margin: 0 auto 10px auto;
      border-radius: 2px;
    }

    .lh-country {
      font-size: 7.5pt;
      font-weight: bold;
      letter-spacing: 3px;
      text-transform: uppercase;
      color: #555;
      margin-bottom: 3px;
    }

    .lh-county {
      font-size: 16pt;
      font-weight: bold;
      font-family: 'Times New Roman', serif;
      color: #0f2e18;
      text-transform: uppercase;
      letter-spacing: 2px;
      line-height: 1.2;
    }

    .lh-dept {
      font-size: 9pt;
      color: #1E5128;
      font-weight: bold;
      letter-spacing: 1.5px;
      text-transform: uppercase;
      margin-top: 3px;
    }

    .lh-market {
      font-size: 8pt;
      color: #666;
      margin-top: 2px;
    }

    .lh-divider {
      height: 1px;
      background: linear-gradient(to right, transparent, #1E5128 30%, #D4A373 50%, #1E5128 70%, transparent);
      margin: 8px 0 6px;
    }

    .lh-contact {
      font-size: 7.5pt;
      color: #777;
      letter-spacing: 0.5px;
    }

    /* ── Meta Table ── */
    .meta-table {
      width: 100%;
      margin-bottom: 16px;
      font-size: 9.5pt;
    }
    .meta-table td { vertical-align: top; padding: 2px 0; }
    .meta-table .left-col { width: 55%; }
    .meta-table .right-col { width: 45%; text-align: right; }
    .meta-label { font-weight: bold; color: #333; }
    .meta-value { color: #111; }
    .case-id-val { font-family: Courier, monospace; color: #1a4d7d; font-weight: bold; }

    /* ── Subject Line ── */
    .subject-line {
      text-align: center;
      font-size: 10.5pt;
      font-weight: bold;
      text-transform: uppercase;
      text-decoration: underline;
      letter-spacing: 1.5px;
      color: #0f2e18;
      margin: 18px 0;
      padding: 10px 20px;
      background: #f0f7f0;
      border: 1px solid #c6e0ca;
      border-left: 4px solid #1E5128;
    }

    /* ── Body Text ── */
    .salute { font-size: 10pt; margin-bottom: 10px; }
    .opening { font-size: 10pt; line-height: 1.7; margin-bottom: 14px; }

    /* ── Section Headings ── */
    .section-head {
      font-size: 8.5pt;
      font-weight: bold;
      text-transform: uppercase;
      letter-spacing: 1px;
      color: #fff;
      background: #1E5128;
      padding: 4px 10px;
      margin-bottom: 8px;
      display: inline-block;
    }

    /* ── Violation Details Box ── */
    .violation-box {
      border: 1px solid #e0e0e0;
      border-left: 4px solid #c0392b;
      background: #fff8f8;
      padding: 12px 14px;
      margin-bottom: 14px;
      font-size: 10pt;
    }

    .violation-row { margin-bottom: 5px; }
    .violation-row .vr-label { font-weight: bold; color: #333; display: inline-block; min-width: 130px; }
    .violation-row .vr-val   { color: #111; }

    .violation-description {
      margin-top: 10px;
      padding-top: 8px;
      border-top: 1px dashed #f5b7b1;
      font-size: 10pt;
      line-height: 1.7;
    }

    /* ── Payment Grid ── */
    .payment-grid {
      width: 100%;
      margin-bottom: 14px;
      border-collapse: collapse;
    }
    .payment-grid td {
      width: 50%;
      border: 1px solid #ddd;
      padding: 8px 12px;
      text-align: center;
      vertical-align: middle;
    }
    .pg-label {
      font-size: 7.5pt;
      font-weight: bold;
      text-transform: uppercase;
      letter-spacing: 1px;
      color: #777;
      display: block;
    }
    .pg-value {
      font-size: 12pt;
      font-weight: bold;
      color: #1E5128;
    }

    /* ── Law Reference Box ── */
    .law-box {
      border: 1px solid #c8d8f5;
      border-left: 4px solid #2563eb;
      background: #eff6ff;
      padding: 10px 14px;
      margin-bottom: 14px;
      font-size: 10pt;
      color: #1e3a5f;
    }

    /* ── Impact Box ── */
    .impact-box {
      border: 1px solid #fde68a;
      border-left: 4px solid #d97706;
      background: #fffbeb;
      padding: 9px 14px;
      margin-bottom: 14px;
      font-size: 10pt;
      color: #78350f;
    }

    /* ── Deadline Box ── */
    .deadline-box {
      background: #fff5f5;
      border: 1.5px solid #f5b7b1;
      padding: 10px 14px;
      margin-bottom: 14px;
      font-size: 10pt;
    }
    .deadline-title {
      font-weight: bold;
      font-size: 10pt;
      color: #c0392b;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }
    .deadline-text { font-size: 10pt; color: #c0392b; }

    /* ── Consequences Box ── */
    .consequences-box {
      background: #f9fafb;
      border: 1px solid #e5e7eb;
      padding: 10px 14px;
      margin-bottom: 14px;
      font-size: 10pt;
      line-height: 1.7;
      color: #374151;
    }

    /* ── Instructions Box ── */
    .instructions-box {
      border: 1px solid #c7d2fe;
      border-left: 4px solid #4f46e5;
      background: #eef2ff;
      padding: 10px 14px;
      margin-bottom: 14px;
      font-size: 10pt;
      line-height: 1.7;
      color: #312e81;
    }

    /* ── Appeal Rights ── */
    .appeal-note {
      font-size: 9pt;
      color: #666;
      font-style: italic;
      margin-bottom: 14px;
      padding: 8px 12px;
      background: #f8fafc;
      border: 1px dashed #d1d5db;
    }

    /* ── Signature Block ── */
    .signature-area {
      margin-top: 36px;
      width: 100%;
    }
    .sig-table { width: 100%; }
    .sig-table td { vertical-align: bottom; }
    .sig-left  { width: 55%; }
    .sig-right { width: 45%; text-align: center; }

    .sig-pad {
      border-top: 2px solid #0f2e18;
      padding-top: 6px;
      min-height: 55px;
    }
    .sig-image {
      max-width: 160px;
      max-height: 55px;
      display: block;
      margin-bottom: 4px;
    }
    .sig-name  { font-weight: bold; text-transform: uppercase; font-size: 10pt; color: #0f2e18; }
    .sig-title { font-size: 9pt; color: #555; }
    .sig-dept  { font-size: 8.5pt; color: #777; }

    .stamp-box {
      display: inline-block;
      border: 2px dashed #1E5128;
      border-radius: 10px;
      padding: 14px 18px;
      text-align: center;
      color: #1E5128;
      font-size: 8pt;
      font-weight: bold;
      text-transform: uppercase;
      letter-spacing: 1px;
      opacity: 0.6;
      min-width: 110px;
      min-height: 80px;
      vertical-align: middle;
    }

    /* ── CC Section ── */
    .cc-section {
      margin-top: 24px;
      padding-top: 12px;
      border-top: 1px dashed #ddd;
      font-size: 9pt;
      color: #555;
      line-height: 1.7;
    }

    /* ── Verification Strip ── */
    .verification-strip {
      margin-top: 18px;
      padding: 8px 14px;
      background: #f0f7f0;
      border: 1px solid #c6e0ca;
      font-size: 8pt;
      color: #2d6a2e;
      display: flex;
      justify-content: space-between;
    }

    /* ── Footer ── */
    .page-footer {
      position: fixed;
      bottom: 8mm;
      left: 20mm;
      right: 20mm;
      font-size: 7.5pt;
      color: #aaa;
      text-align: center;
      border-top: 1px solid #e5e7eb;
      padding-top: 5px;
    }

    /* ── Evidence Annex ── */
    .page-break { page-break-before: always; }
    .annex-header {
      text-align: center;
      font-size: 13pt;
      font-weight: bold;
      text-transform: uppercase;
      text-decoration: underline;
      margin-bottom: 16px;
      letter-spacing: 1px;
    }
    .evidence-container { text-align: center; }
    .evidence-img {
      max-width: 90%;
      max-height: 380px;
      border: 2px solid #000;
      display: block;
      margin: 0 auto;
    }
    .evidence-caption {
      font-size: 9pt;
      font-style: italic;
      margin-top: 8px;
      color: #555;
    }
  </style>
</head>
<body>

  <div class="watermark">MUTHURWA</div>

  <div class="page">

    {{-- ── LETTERHEAD ── --}}
    <div class="letterhead">
      <div class="letterhead-accent"></div>
      <div class="lh-country">Republic of Kenya</div>
      <div class="lh-county">{{ $letterData['letter_head'] ?? 'Nairobi City County Government' }}</div>
      <div class="lh-dept">Market Enforcement Department · Muthurwa Market</div>
      <div class="lh-divider"></div>
      <div class="lh-market">P.O. Box 30075-00100, Nairobi · Tel: 0710618973 · Email: info@muthurwamarket.indevs.in</div>
      <div class="lh-contact" style="margin-top:4px;">
        "Promoting Orderly, Safe &amp; Compliant Market Operations"
      </div>
    </div>

    {{-- ── META ── --}}
    <table class="meta-table">
      <tr>
        <td class="left-col">
          <span class="meta-label">Ref No:</span>
          <span class="meta-value"> {{ $letterData['reference_number'] ?? 'NCC/MKT/'.date('Y') }}</span><br>
          <span class="meta-label">Case ID:</span>
          <span class="case-id-val"> {{ $letterData['case_id'] ?? $violation->case_id }}</span><br>
          <span class="meta-label">Date:</span>
          <span class="meta-value">
            @php
              try { echo \Carbon\Carbon::parse($letterData['date_of_observation'] ?? now())->format('d F Y'); }
              catch(\Exception $e){ echo date('d F Y'); }
            @endphp
          </span>
        </td>
        <td class="right-col">
          <span class="meta-label">TO:</span>
          <span class="meta-value"> {{ strtoupper($letterData['recipient_name'] ?? optional($violation->trader)->name ?? 'THE TRADER') }}</span><br>
          <span class="meta-label">Stall No:</span>
          <span class="meta-value"> {{ $letterData['stall_number'] ?? 'N/A' }}</span><br>
          <span class="meta-label">Market:</span>
          <span class="meta-value"> Muthurwa Market, Nairobi</span>
        </td>
      </tr>
    </table>

    {{-- ── SUBJECT ── --}}
    <div class="subject-line">
      {{ $letterData['subject'] ?? 'OFFICIAL NOTICE OF VIOLATION – ' . strtoupper($violation->violation_type ?? '') }}
    </div>

    {{-- ── SALUTATION & OPENING ── --}}
    <p class="salute">
      Dear <strong>{{ ucwords(strtolower($letterData['recipient_name'] ?? optional($violation->trader)->name ?? 'Trader')) }}</strong>,
    </p>
    <p class="opening">{{ $letterData['opening_statement'] ?? '' }}</p>

    {{-- ── VIOLATION DETAILS ── --}}
    <div class="section-head">🚨 Nature of Infringement</div>
    <div class="violation-box">
      <div class="violation-row">
        <span class="vr-label">Trader Name:</span>
        <span class="vr-val">{{ ucwords(strtolower($letterData['recipient_name'] ?? optional($violation->trader)->name ?? 'N/A')) }}</span>
      </div>
      <div class="violation-row">
        <span class="vr-label">Stall Number:</span>
        <span class="vr-val">{{ $letterData['stall_number'] ?? 'N/A' }}</span>
      </div>
      <div class="violation-row">
        <span class="vr-label">Violation Type:</span>
        <span class="vr-val">{{ $letterData['violation_type'] ?? $violation->violation_type ?? 'N/A' }}</span>
      </div>
      <div class="violation-row">
        <span class="vr-label">Date Observed:</span>
        <span class="vr-val">
          @php
            try { echo \Carbon\Carbon::parse($letterData['date_of_observation'] ?? $violation->created_at)->format('d M Y, h:i A'); }
            catch(\Exception $e){ echo date('d M Y'); }
          @endphp
        </span>
      </div>
      <div class="violation-description">
        <strong>Description of Infringement:</strong><br>
        {!! nl2br(e($letterData['violation_details'] ?? $violation->officer_notes ?? '')) !!}
      </div>
    </div>

    {{-- ── PAYMENT (if applicable) ── --}}
    @if(!empty($letterData['amount_due']) || !empty($letterData['payment_period']))
    <table class="payment-grid">
      <tr>
        <td>
          <span class="pg-label">💰 Amount Due</span>
          <span class="pg-value">{{ $letterData['amount_due'] ?? 'Per Market Tariff' }}</span>
        </td>
        <td>
          <span class="pg-label">📅 Payment Period</span>
          <span class="pg-value">{{ $letterData['payment_period'] ?? 'Daily' }}</span>
        </td>
      </tr>
    </table>
    @endif

    {{-- ── STATUTORY BASIS ── --}}
    @if(!empty($letterData['law_reference']))
    <div class="section-head">📜 Statutory &amp; Legal Basis</div>
    <div class="law-box">
      <strong>Legal Reference:</strong> {{ $letterData['law_reference'] }}
    </div>
    @endif

    {{-- ── COMMUNITY IMPACT ── --}}
    @if(!empty($letterData['community_impact']))
    <div class="impact-box">
      <strong>⚠ Community &amp; Market Impact:</strong> {{ $letterData['community_impact'] }}
    </div>
    @endif

    {{-- ── COMPLIANCE DEADLINE ── --}}
    <div class="section-head">⏰ Compliance Deadline</div>
    <div class="deadline-box">
      <div class="deadline-title">⚠ Immediate Action Required</div>
      <div class="deadline-text">{{ $letterData['compliance_deadline'] ?? 'You are required to rectify this violation within 24 hours from the time of this notice.' }}</div>
    </div>

    {{-- ── LEGAL CONSEQUENCES ── --}}
    <div class="section-head">⚖️ Legal Consequences of Non-Compliance</div>
    <div class="consequences-box">
      {!! nl2br(e($letterData['legal_consequences'] ?? 'Failure to comply with this notice will result in immediate legal action before the Nairobi City Court, including revocation of stall allocation, confiscation of goods, and suspension of all trading privileges.')) !!}
    </div>

    {{-- ── REQUIRED ACTIONS ── --}}
    <div class="section-head">✅ Required Corrective Actions</div>
    <div class="instructions-box">
      {!! nl2br(e($letterData['instructions'] ?? 'Immediately cease the violation and report to the Market Enforcement Office for re-inspection and compliance verification.')) !!}
    </div>

    {{-- ── APPEAL RIGHTS ── --}}
    @if(!empty($letterData['appeal_rights']))
    <div class="appeal-note">
      <strong>Right of Reply / Appeal:</strong> {{ $letterData['appeal_rights'] }}
    </div>
    @endif

    {{-- ── SIGNATURE BLOCK ── --}}
    <div class="signature-area">
      <p style="font-size:10pt; margin-bottom:22px;">Yours faithfully,</p>
      <table class="sig-table">
        <tr>
          <td class="sig-left">
            <div class="sig-pad">
              @php
                $sigPath = null;
                if($violation->signature_path){
                  $fullPath = storage_path('app/public/' . $violation->signature_path);
                  if(file_exists($fullPath)){
                    $ext    = pathinfo($fullPath, PATHINFO_EXTENSION);
                    $sigB64 = 'data:image/'.$ext.';base64,'.base64_encode(file_get_contents($fullPath));
                  }
                }
              @endphp
              @if(isset($sigB64))
                <img src="{{ $sigB64 }}" class="sig-image" alt="Officer Signature">
              @endif
              <div class="sig-name">{{ $letterData['officer_name'] ?? optional($violation->officer)->name ?? 'Authorized Officer' }}</div>
              <div class="sig-title">{{ $letterData['officer_title'] ?? 'Market Enforcement Officer' }}</div>
              <div class="sig-dept">Market Enforcement Department · Muthurwa Market</div>
            </div>
          </td>
          <td class="sig-right">
            @php
              $stampPath = public_path('nairobi_county_stamp.png');
              $stampB64 = null;
              if(file_exists($stampPath)){
                $ext = pathinfo($stampPath, PATHINFO_EXTENSION);
                $stampB64 = 'data:image/'.$ext.';base64,'.base64_encode(file_get_contents($stampPath));
              }
            @endphp
            @if($stampB64)
              <div style="text-align: right;">
                <img src="{{ $stampB64 }}" style="width: 100px; height: 100px; opacity: 0.9; margin-top: -10px;" alt="Official Stamp">
              </div>
            @else
              <div class="stamp-box">
                <div style="font-size:22pt; margin-bottom:4px;">🏛️</div>
                <div>{{ $letterData['official_stamp_section'] ?? 'OFFICIAL ENFORCEMENT STAMP' }}</div>
                <div style="font-size:7pt; margin-top:3px; color:#2d6a2e; font-style:italic;">Nairobi City County</div>
              </div>
            @endif
          </td>
        </tr>
      </table>
    </div>

    {{-- ── CC SECTION ── --}}
    <div class="cc-section">
      <strong>CC:</strong><br>
      @php
        $cc = $letterData['cc_section'] ?? 'Market Manager, Enforcement Department, File';
        foreach(explode(',', $cc) as $c) echo '• ' . trim($c) . '<br>';
      @endphp
    </div>

    {{-- ── VERIFICATION STRIP ── --}}
    <div class="verification-strip">
      <span>Case ID: <strong>{{ $violation->case_id }}</strong></span>
      <span>Generated: {{ now()->format('d M Y, H:i') }}</span>
      <span>Status: <strong>{{ strtoupper($violation->status ?? 'draft') }}</strong></span>
    </div>

  </div>{{-- /page --}}

  {{-- ── PAGE FOOTER ── --}}
  <div class="page-footer">
    Official Notice · Muthurwa Market Enforcement Department · Nairobi City County Government · Generated {{ now()->format('Y-m-d H:i:s') }}
  </div>

  {{-- ── EVIDENCE ANNEX (new page) ── --}}
  @if($violation->photo_path)
    @php
      $photoFullPath = storage_path('app/public/' . $violation->photo_path);
      $photoB64      = '';
      if(file_exists($photoFullPath)){
        $ext      = pathinfo($photoFullPath, PATHINFO_EXTENSION);
        $photoB64 = 'data:image/'.$ext.';base64,'.base64_encode(file_get_contents($photoFullPath));
      }
    @endphp
    @if($photoB64)
    <div class="page-break"></div>
    <div class="page">
      <div class="annex-header">Annexure A – Photographic Evidence</div>
      <div style="text-align:center; margin-bottom:12px; font-size:10pt;">
        <strong>Case ID:</strong> {{ $violation->case_id }} &nbsp;|&nbsp;
        <strong>Trader:</strong> {{ optional($violation->trader)->name ?? 'N/A' }} &nbsp;|&nbsp;
        <strong>Violation:</strong> {{ $violation->violation_type ?? 'N/A' }}
      </div>
      <div class="evidence-container">
        <img src="{{ $photoB64 }}" class="evidence-img" alt="Violation Evidence Photo">
        <p class="evidence-caption">
          Evidence photo captured during inspection by {{ optional($violation->officer)->name ?? 'Enforcement Officer' }} on
          {{ optional($violation->created_at)->format('d M Y') }}.
        </p>
      </div>
    </div>
    @endif
  @endif

</body>
</html>
