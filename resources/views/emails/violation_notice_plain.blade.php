NAIROBI CITY COUNTY GOVERNMENT
MUTHURWA MARKET — MARKET ENFORCEMENT DEPARTMENT
========================================================
⚠  OFFICIAL NOTICE OF VIOLATION — IMMEDIATE ACTION REQUIRED
========================================================

@php
    $d = is_array($violation->ai_raw_message)
        ? $violation->ai_raw_message
        : (json_decode($violation->ai_raw_message, true) ?? []);
    $f = fn(string $k, string $fb = '—') => trim($d[$k] ?? '') ?: $fb;
@endphp

REFERENCE NO : {{ $f('reference_number') }}
CASE ID      : {{ $f('case_id', $violation->case_id ?? '—') }}
DATE         : {{ $f('date_of_observation', now()->format('Y-m-d')) }}

--------------------------------------------------------
TO   : {{ $f('recipient_name', $violation->trader->name ?? '—') }}
STALL: {{ $f('stall_number', '—') }}
--------------------------------------------------------

SUBJECT: {{ $f('subject', 'Notice of Violation – ' . ($violation->violation_type ?? '')) }}

{{ $f('opening_statement') }}

--------------------------------------------------------
VIOLATION TYPE
--------------------------------------------------------
{{ $f('violation_type', $violation->violation_type ?? '—') }}

VIOLATION DETAILS:
{{ $f('violation_details') }}

--------------------------------------------------------
LEGAL REFERENCE
--------------------------------------------------------
{{ $f('law_reference') }}

--------------------------------------------------------
COMMUNITY IMPACT
--------------------------------------------------------
{{ $f('community_impact') }}

--------------------------------------------------------
⏰ COMPLIANCE DEADLINE
--------------------------------------------------------
{{ $f('compliance_deadline') }}

--------------------------------------------------------
REQUIRED ACTIONS
--------------------------------------------------------
{{ $f('instructions') }}

--------------------------------------------------------
⚠ LEGAL CONSEQUENCES OF NON-COMPLIANCE
--------------------------------------------------------
{{ $f('legal_consequences') }}

@if($f('amount_due') !== '—')
--------------------------------------------------------
PAYMENT DETAILS
--------------------------------------------------------
Amount Due    : {{ $f('amount_due') }}
Payment Period: {{ $f('payment_period') }}
@endif

--------------------------------------------------------
RIGHT OF APPEAL
--------------------------------------------------------
{{ $f('appeal_rights') }}

========================================================

{{ $f('signature_block') }}

{{ $f('official_stamp_section', '[OFFICIAL MARKET ENFORCEMENT STAMP]') }}

--------------------------------------------------------
CONTACT
--------------------------------------------------------
{{ $f('contact_details') }}

CC: {{ $f('cc_section') }}

========================================================
This is an official Nairobi City County Government communication.
© {{ date('Y') }} Nairobi City County Government. All rights reserved.
========================================================
