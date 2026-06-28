<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>Official Violation Notice — Nairobi City County</title>
<!--[if mso]><noscript><xml><o:OfficeDocumentSettings><o:PixelsPerInch>96</o:PixelsPerInch></o:OfficeDocumentSettings></xml></noscript><![endif]-->
</head>
<body style="margin:0;padding:0;background-color:#F0F4F8;font-family:Arial,Helvetica,sans-serif;-webkit-font-smoothing:antialiased;">

<?php
    // Decode the AI-generated JSON for structured field access
    $d = is_array($violation->ai_raw_message)
        ? $violation->ai_raw_message
        : (json_decode($violation->ai_raw_message, true) ?? []);

    // Helper to safely get a field, with a fallback
    $f = fn(string $key, string $fallback = '—') => trim($d[$key] ?? '') ?: $fallback;
?>

<table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color:#F0F4F8;">
<tr><td align="center" style="padding:30px 16px;">

<table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="max-width:620px;border-radius:16px;overflow:hidden;box-shadow:0 8px 40px rgba(0,0,0,0.14);">

    {{-- ══ RED URGENCY STRIPE ══ --}}
    <tr>
        <td style="background-color:#DC2626;padding:10px 32px;text-align:center;">
            <span style="color:#ffffff;font-size:11px;font-weight:700;letter-spacing:3px;text-transform:uppercase;">
                ⚠ OFFICIAL NOTICE OF VIOLATION — IMMEDIATE ACTION REQUIRED
            </span>
        </td>
    </tr>

    {{-- ══ HEADER ══ --}}
    <tr>
        <td style="background:linear-gradient(135deg,#068930 0%,#046122 55%,#034d1a 100%);padding:36px 40px 28px;text-align:center;">
            <p style="margin:0 0 6px;color:#FCDD07;font-size:10px;font-weight:700;letter-spacing:4px;text-transform:uppercase;">
                Nairobi City County Government
            </p>
            <h1 style="margin:0;color:#ffffff;font-size:22px;font-weight:700;letter-spacing:-0.5px;line-height:1.3;">
                🦁 Muthurwa Market<br>
                <span style="font-size:14px;font-weight:400;opacity:0.75;">Market Enforcement Department</span>
            </h1>
        </td>
    </tr>

    {{-- ══ CASE REFERENCE BANNER ══ --}}
    <tr>
        <td style="background-color:#1A1A1B;padding:12px 40px;">
            <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                <tr>
                    <td style="color:#FCDD07;font-size:11px;font-weight:700;letter-spacing:2px;text-transform:uppercase;">
                        Case ID: {{ $f('case_id', $violation->case_id ?? '—') }}
                    </td>
                    <td align="right" style="color:rgba(255,255,255,0.5);font-size:11px;font-weight:600;">
                        Ref: {{ $f('reference_number') }} &nbsp;|&nbsp; {{ $f('date_of_observation', now()->format('Y-m-d')) }}
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    {{-- ══ BODY ══ --}}
    <tr>
        <td style="background-color:#ffffff;padding:36px 40px;">

            {{-- Subject --}}
            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin-bottom:24px;">
                <tr>
                    <td style="background-color:#FFF5F5;border-left:5px solid #DC2626;border-radius:0 8px 8px 0;padding:14px 20px;">
                        <p style="margin:0;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:2px;color:#DC2626;">Subject</p>
                        <p style="margin:4px 0 0;font-size:15px;font-weight:700;color:#1A1A1B;">{{ $f('subject', 'Notice of Violation – ' . $violation->violation_type) }}</p>
                    </td>
                </tr>
            </table>

            {{-- Trader Info Card --}}
            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin-bottom:24px;border:1.5px solid #E2E8F0;border-radius:10px;overflow:hidden;">
                <tr>
                    <td style="background-color:#F8FAFC;padding:12px 20px;border-bottom:1px solid #E2E8F0;">
                        <p style="margin:0;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:2px;color:#068930;">📋 Notice Recipient</p>
                    </td>
                </tr>
                <tr>
                    <td style="padding:16px 20px;">
                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                            <tr>
                                <td width="50%" style="padding-bottom:10px;">
                                    <p style="margin:0;font-size:10px;font-weight:700;text-transform:uppercase;color:#94A3B8;letter-spacing:1px;">Trader Name</p>
                                    <p style="margin:3px 0 0;font-size:14px;font-weight:700;color:#1A1A1B;">{{ $f('recipient_name', $violation->trader->name ?? '—') }}</p>
                                </td>
                                <td width="50%" style="padding-bottom:10px;">
                                    <p style="margin:0;font-size:10px;font-weight:700;text-transform:uppercase;color:#94A3B8;letter-spacing:1px;">Stall Number</p>
                                    <p style="margin:3px 0 0;font-size:14px;font-weight:700;color:#1A1A1B;">{{ $f('stall_number') }}</p>
                                </td>
                            </tr>
                            <tr>
                                <td width="50%">
                                    <p style="margin:0;font-size:10px;font-weight:700;text-transform:uppercase;color:#94A3B8;letter-spacing:1px;">Date of Violation</p>
                                    <p style="margin:3px 0 0;font-size:14px;font-weight:700;color:#1A1A1B;">{{ $f('date_of_observation') }}</p>
                                </td>
                                <td width="50%">
                                    <p style="margin:0;font-size:10px;font-weight:700;text-transform:uppercase;color:#94A3B8;letter-spacing:1px;">Violation Type</p>
                                    <p style="margin:3px 0 0;font-size:14px;font-weight:700;color:#DC2626;">{{ $f('violation_type', $violation->violation_type ?? '—') }}</p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>

            {{-- Opening Statement --}}
            <p style="font-size:14px;color:#374151;line-height:1.75;margin:0 0 24px;">
                {{ $f('opening_statement') }}
            </p>

            {{-- Section: Violation Details --}}
            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin-bottom:20px;">
                <tr>
                    <td style="background-color:#FFF5F5;border-radius:10px;padding:18px 22px;">
                        <p style="margin:0 0 8px;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:2px;color:#DC2626;">🚨 Violation Details</p>
                        <p style="margin:0;font-size:13px;color:#374151;line-height:1.7;">{{ $f('violation_details') }}</p>
                    </td>
                </tr>
            </table>

            {{-- Section: Legal Reference --}}
            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin-bottom:20px;">
                <tr>
                    <td style="background-color:#F8FAFC;border-left:4px solid #0F47AF;border-radius:0 10px 10px 0;padding:16px 20px;">
                        <p style="margin:0 0 6px;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:2px;color:#0F47AF;">⚖️ Legal Reference</p>
                        <p style="margin:0;font-size:13px;color:#374151;line-height:1.7;">{{ $f('law_reference') }}</p>
                    </td>
                </tr>
            </table>

            {{-- Section: Community Impact --}}
            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin-bottom:20px;">
                <tr>
                    <td style="background-color:#FFFBEB;border-left:4px solid #FCDD07;border-radius:0 10px 10px 0;padding:16px 20px;">
                        <p style="margin:0 0 6px;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:2px;color:#92400E;">🌍 Community Impact</p>
                        <p style="margin:0;font-size:13px;color:#374151;line-height:1.7;">{{ $f('community_impact') }}</p>
                    </td>
                </tr>
            </table>

            {{-- Compliance Deadline (Highlighted) --}}
            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin-bottom:20px;">
                <tr>
                    <td style="background:linear-gradient(135deg,#DC2626,#B91C1C);border-radius:10px;padding:20px 24px;text-align:center;">
                        <p style="margin:0 0 4px;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:2px;color:rgba(255,255,255,0.7);">⏰ Compliance Deadline</p>
                        <p style="margin:0;font-size:15px;font-weight:700;color:#ffffff;line-height:1.5;">{{ $f('compliance_deadline') }}</p>
                    </td>
                </tr>
            </table>

            {{-- Section: Required Actions --}}
            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin-bottom:20px;">
                <tr>
                    <td style="background-color:#F0FAF3;border-left:4px solid #068930;border-radius:0 10px 10px 0;padding:16px 20px;">
                        <p style="margin:0 0 8px;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:2px;color:#068930;">✅ Required Actions</p>
                        <p style="margin:0;font-size:13px;color:#374151;line-height:1.7;">{{ $f('instructions') }}</p>
                    </td>
                </tr>
            </table>

            {{-- Section: Legal Consequences --}}
            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin-bottom:20px;">
                <tr>
                    <td style="border:1.5px solid #FECACA;border-radius:10px;padding:16px 20px;">
                        <p style="margin:0 0 6px;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:2px;color:#DC2626;">⚠ Legal Consequences of Non-Compliance</p>
                        <p style="margin:0;font-size:13px;color:#374151;line-height:1.7;">{{ $f('legal_consequences') }}</p>
                    </td>
                </tr>
            </table>

            @if($f('amount_due') !== '—')
            {{-- Payment Details --}}
            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin-bottom:20px;border:1.5px solid #D1FAE5;border-radius:10px;overflow:hidden;">
                <tr>
                    <td width="50%" style="background-color:#F0FAF3;padding:16px 20px;border-right:1px solid #D1FAE5;">
                        <p style="margin:0;font-size:10px;font-weight:700;text-transform:uppercase;color:#068930;letter-spacing:1px;">Amount Due</p>
                        <p style="margin:4px 0 0;font-size:18px;font-weight:700;color:#1A1A1B;">{{ $f('amount_due') }}</p>
                    </td>
                    <td width="50%" style="background-color:#F0FAF3;padding:16px 20px;">
                        <p style="margin:0;font-size:10px;font-weight:700;text-transform:uppercase;color:#068930;letter-spacing:1px;">Payment Period</p>
                        <p style="margin:4px 0 0;font-size:18px;font-weight:700;color:#1A1A1B;">{{ $f('payment_period') }}</p>
                    </td>
                </tr>
            </table>
            @endif

            {{-- Appeal Rights --}}
            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin-bottom:28px;">
                <tr>
                    <td style="background-color:#F8FAFC;border-radius:10px;padding:16px 20px;">
                        <p style="margin:0 0 6px;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:2px;color:#0F47AF;">📝 Right of Appeal</p>
                        <p style="margin:0;font-size:13px;color:#374151;line-height:1.7;">{{ $f('appeal_rights') }}</p>
                    </td>
                </tr>
            </table>

            {{-- Divider --}}
            <hr style="border:none;border-top:1.5px solid #E2E8F0;margin:0 0 24px;">

            {{-- Signature Block --}}
            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin-bottom:20px;">
                <tr>
                    <td width="55%">
                        <p style="margin:0 0 4px;font-size:12px;color:#374151;font-style:italic;">Yours faithfully,</p>
                        @if($violation->signature_path)
                            <img src="{{ url('storage/' . $violation->signature_path) }}" alt="Officer Signature" style="max-height:60px;margin:8px 0;">
                        @else
                            <div style="height:40px;border-bottom:2px solid #1A1A1B;width:160px;margin:8px 0;"></div>
                        @endif
                        <p style="margin:4px 0 0;font-size:14px;font-weight:700;color:#1A1A1B;">{{ $f('officer_name', $violation->officer->name ?? '—') }}</p>
                        <p style="margin:2px 0 0;font-size:12px;color:#64748B;">{{ $f('officer_title', 'Market Enforcement Officer') }}</p>
                        <p style="margin:2px 0 0;font-size:12px;color:#64748B;">Muthurwa Market · Nairobi City County</p>
                    </td>
                    <td width="45%" align="right" valign="middle">
                        <img src="{{ url('nairobi_county_stamp.png') }}" alt="Nairobi City County Stamp" style="width:100px;height:100px;display:block;">
                    </td>
                </tr>
            </table>

            {{-- Contact Details --}}
            <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                <tr>
                    <td style="border-top:1px solid #E2E8F0;padding-top:16px;">
                        <p style="margin:0 0 4px;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:2px;color:#94A3B8;">Contact</p>
                        <p style="margin:0;font-size:12px;color:#64748B;line-height:1.7;white-space:pre-line;">{{ $f('contact_details') }}</p>
                    </td>
                </tr>
            </table>

        </td>
    </tr>

    {{-- ══ CC SECTION ══ --}}
    <tr>
        <td style="background-color:#F8FAFC;border-top:1px solid #E2E8F0;padding:14px 40px;">
            <p style="margin:0;font-size:11px;color:#94A3B8;font-weight:600;">
                <strong style="color:#64748B;">CC:</strong> {{ str_replace("\n", " | ", $f('cc_section')) }}
            </p>
        </td>
    </tr>

    {{-- ══ FOOTER ══ --}}
    <tr>
        <td style="background-color:#1A1A1B;padding:24px 40px;text-align:center;">
            <p style="margin:0 0 4px;font-size:11px;font-weight:700;letter-spacing:3px;text-transform:uppercase;color:#FCDD07;">Muthurwa Market</p>
            <p style="margin:0 0 12px;font-size:10px;color:rgba(255,255,255,0.4);">Nairobi City County Government · Market Enforcement Department</p>
            <p style="margin:0;font-size:10px;color:rgba(255,255,255,0.25);line-height:1.7;">
                This is an official government communication. Do not ignore this notice.<br>
                © {{ date('Y') }} Nairobi City County Government. All rights reserved.
            </p>
        </td>
    </tr>

</table>
</td></tr>
</table>

</body>
</html>
