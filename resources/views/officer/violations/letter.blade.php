@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="letter-paper mx-auto" style="max-width: 800px; position: relative;">

        {{-- HEADER --}}
        <div class="letter-header pb-3">
            <h2 class="text-uppercase fw-bold">Official Notice of Violation</h2>
            <p class="mb-0">Enforcement Division | Market Administration</p>
            <p class="mb-0">Muthurwa Market, Nairobi</p>
            <p>Date: {{ now()->format('d M, Y') }}</p>
        </div>

        {{-- RECIPIENT DETAILS --}}
        <div class="mb-4">
            <p><strong>TO:</strong> {{ optional($violation->trader)->name ? ucwords(optional($violation->trader)->name) : ($letterData['recipient_name'] ?? 'Recipient') }}</p>

            <p>
                <strong>STALL:</strong>
                {{ optional(optional($violation->trader)->stall)->stall_number ?? ($letterData['stall_number'] ?? 'Not Assigned') }}
            </p>

            <p><strong>Market:</strong> Muthurwa Market, Nairobi</p>
        </div>

        {{-- SUBJECT --}}
        <div class="subject">
            Subject: {{ $letterData['subject'] ?? ('Official Notice of Violation – ' . strtoupper($violation->violation_type ?? '')) }}
        </div>

        {{-- SALUTATION --}}
        <p>Dear {{ optional($violation->trader)->name ? ucwords(optional($violation->trader)->name) : ($letterData['recipient_name'] ?? 'Trader') }},</p>

        {{-- PREPARE letterData --}}
        @php
            // Prefer controller-provided $letterData if available; otherwise attempt to decode ai_raw_message
            if (!isset($letterData) || !is_array($letterData)) {
                $letterData = null;
                if (!empty($violation->ai_raw_message)) {
                    // Try decode JSON safely (handles string or already JSON)
                    $decoded = json_decode($violation->ai_raw_message, true);
                    if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                        $letterData = $decoded;
                    } else {
                        // Attempt to extract first JSON object substring
                        if (preg_match('/\{(?:[^{}]|(?R))*\}/s', $violation->ai_raw_message, $matches)) {
                            $candidate = $matches[0];
                            $decoded2 = json_decode($candidate, true);
                            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded2)) {
                                $letterData = $decoded2;
                            }
                        }
                    }
                }
            }
        @endphp

        {{-- AI GENERATED CONTENT --}}
        @if(!empty($letterData) && is_array($letterData))

            {{-- Opening Statement --}}
            @if(!empty($letterData['opening_statement']))
                <p class="mb-3">{{ $letterData['opening_statement'] }}</p>
            @endif

            {{-- Violation Details Box --}}
            <div class="violation-box">
                <h5 class="fw-bold mb-3">Violation Details</h5>

                <p>
                    <strong>Trader:</strong>
                    {{ $letterData['recipient_name'] ?? optional($violation->trader)->name ?? 'N/A' }}
                </p>

                <p>
                    <strong>Date of Observation:</strong>
                    @php
                        $obs = $letterData['date_of_observation'] ?? null;
                        try {
                            $displayDate = $obs ? \Carbon\Carbon::parse($obs)->format('d M, Y') : (optional($violation->created_at)->format('d M, Y') ?? now()->format('d M, Y'));
                        } catch (\Exception $e) {
                            $displayDate = $obs ?? now()->format('d M, Y');
                        }
                    @endphp
                    {{ $displayDate }}
                </p>

                <p>
                    <strong>Violation Type:</strong>
                    {{ $letterData['violation_type'] ?? $violation->violation_type ?? 'N/A' }}
                </p>

                <p>
                    <strong>Stall Number:</strong>
                    {{ $letterData['stall_number'] ?? optional(optional($violation->trader)->stall)->stall_number ?? 'N/A' }}
                </p>

                @if(!empty($letterData['violation_details']))
                    <p class="mt-3">
                        <strong>Description:</strong><br>
                        <span class="text-gray-800">{!! nl2br(e($letterData['violation_details'])) !!}</span>
                    </p>
                @endif

                <p class="mt-3">
                    <strong>Payment Period:</strong>
                    {{ $letterData['payment_period'] ?? 'Daily' }}
                </p>

                <p>
                    <strong>Amount Due:</strong>
                    {{ $letterData['amount_due'] ?? 'Refer to official market tariffs.' }}
                </p>
            </div>

            {{-- Legal Consequences --}}
            @if(!empty($letterData['legal_consequences']))
                <p class="mt-4">
                    {!! nl2br(e($letterData['legal_consequences'])) !!}
                </p>
            @endif

            {{-- Instructions --}}
            @if(!empty($letterData['instructions']))
                <p class="mt-3">
                    <strong>Instructions:</strong><br>
                    {!! nl2br(e($letterData['instructions'])) !!}
                </p>
            @endif

            {{-- Closing --}}
            @if(!empty($letterData['closing']))
                <p class="mt-4">
                    {!! nl2br(e($letterData['closing'])) !!}
                </p>
            @endif

        @else

            {{-- Fallback: show final_letter, then raw AI message, then officer notes --}}
            @if(!empty($violation->final_letter))
                <div class="mb-4">
                    <div class="whitespace-pre-line">{!! nl2br(e($violation->final_letter)) !!}</div>
                </div>
            @elseif(!empty($violation->ai_raw_message))
                <div class="mb-4">
                    <div class="whitespace-pre-line">{!! nl2br(e($violation->ai_raw_message)) !!}</div>
                </div>
            @else
                <p>{{ $violation->officer_notes ?? 'No letter content available.' }}</p>
            @endif

        @endif

        {{-- SIGNATURE --}}
        <div class="signature-section mt-5">
            <p>Sincerely,</p>
            <br><br>
            <p class="fw-bold officer-name">
                {{ optional($violation->officer)->name ?? auth()->user()->name ?? 'Authorized Enforcement Officer' }}
            </p>
            <p class="text-muted small">
                {{ optional($violation->officer)->title ?? 'Market Enforcement Officer' }}
            </p>
            {{-- If AI provided a signature block, show it below --}}
            @if(!empty($letterData['signature_block']))
                <div class="mt-3 whitespace-pre-line text-sm text-gray-700">
                    {!! nl2br(e($letterData['signature_block'])) !!}
                </div>
            @endif
        </div>

    </div>
</div>

{{-- STYLES --}}
<style>
.letter-paper {
    background: white;
    padding: 60px 90px;
    min-height: 297mm;
    box-shadow: 0 0 20px rgba(0,0,0,0.08);
    font-family: "Times New Roman", serif;
    font-size: 16px;
    line-height: 1.7;
    color: #333;
}

.letter-header {
    border-bottom: 3px solid #002d5a;
    margin-bottom: 30px;
}

.subject {
    text-align: center;
    font-weight: bold;
    text-decoration: underline;
    text-transform: uppercase;
    margin: 30px 0;
}

.violation-box {
    background: #f8f9fa;
    border-left: 5px solid #dc3545;
    padding: 20px;
    margin-top: 25px;
}

.signature-section {
    margin-top: 70px;
}

.officer-name {
    border-top: 1px solid #000;
    display: inline-block;
    padding-top: 5px;
}

.official-seal {
    opacity: 0.08;
    position: absolute;
    top: 35%;
    left: 20%;
    width: 60%;
}
</style>

@endsection
