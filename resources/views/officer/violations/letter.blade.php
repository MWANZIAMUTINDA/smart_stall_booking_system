@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="letter-paper mx-auto" style="max-width: 800px; position: relative;">
        
        <!-- Optional watermark or seal -->
        {{-- <img src="{{ asset('images/seal.png') }}" class="official-seal" alt="Official Seal"> --}}

        <!-- Header -->
        <div class="letter-header pb-3">
            <h2 class="text-uppercase fw-bold">Official Notice of Violation</h2>
            <p class="mb-0">Enforcement Division | Market Administration</p>
            <p>Date: {{ now()->format('d M, Y') }}</p>
        </div>

        <!-- Recipient Details -->
        <div class="mb-4">
            <p><strong>TO:</strong> {{ $violation->trader->name }}</p>
            <p><strong>STALL:</strong> {{ $violation->trader->stall->stall_number ?? '[Not Provided]' }}</p>
            <p><strong>Market:</strong> Muthurwa Market, Nairobi</p>
        </div>

        <!-- Subject -->
        <div class="subject text-center mb-4" style="font-weight: bold; text-decoration: underline;">
            Subject: Official Notice of Violation – {{ strtoupper($violation->violation_type) }}
        </div>

        <!-- Salutation -->
        <p>Dear {{ $violation->trader->name }},</p>

        <!-- AI-generated letter content -->
        @php
            // Decode JSON if AI returned JSON format
            $letterData = json_decode($violation->ai_raw_message);
        @endphp

        @if($letterData)
            <p>{{ $letterData->opening_statement ?? '' }}</p>

            <div class="my-4 p-3 bg-light border-start border-4 border-danger">
                <strong>Violation Details:</strong><br>
                {{ $letterData->violation_details ?? '' }}
            </div>

            <p>{{ $letterData->legal_consequences ?? '' }}</p>

            <p>{{ $letterData->closing ?? '' }}</p>
        @else
            <p>{{ $violation->ai_raw_message }}</p>
        @endif

        <!-- Signature -->
        <div class="mt-5">
            <p>Sincerely,</p>
            <br><br>
            <p class="fw-bold border-top d-inline-block pt-2">
                {{ auth()->user()->name ?? 'Authorized Enforcement Officer' }}
            </p>
        </div>
    </div>
</div>

<style>
    .letter-paper {
        background: white;
        padding: 50px 80px;
        min-height: 297mm; /* A4 Height */
        box-shadow: 0 0 15px rgba(0,0,0,0.1);
        font-family: 'Georgia', serif;
        color: #333;
        line-height: 1.6;
    }
    .letter-header { border-bottom: 2px solid #002d5a; margin-bottom: 30px; }
    .official-seal { opacity: 0.1; position: absolute; top: 40%; left: 25%; width: 50%; }
    .subject { text-align: center; font-weight: bold; text-decoration: underline; text-transform: uppercase; margin: 30px 0; }
    .border-light { border-color: #dee2e6 !important; }
</style>
@endsection