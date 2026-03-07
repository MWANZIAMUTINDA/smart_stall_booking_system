@extends('layouts.app')

@section('content')

{{-- Custom Animation & Letter Styles --}}
<style>
    @keyframes ping-green { 0% { transform: scale(1); opacity: 0.7; } 100% { transform: scale(1.4); opacity: 0; } }
    @keyframes ping-blue { 0% { transform: scale(1); opacity: 0.7; } 100% { transform: scale(1.4); opacity: 0; } }
    .animate-ping-green { animation: ping-green 2s cubic-bezier(0, 0, 0.2, 1) infinite; }
    .animate-ping-blue { animation: ping-blue 2s cubic-bezier(0, 0, 0.2, 1) infinite; }
    .fade-in-up { animation: fadeInUp 0.5s ease-out forwards; }
    @keyframes fadeInUp { from { opacity: 0; transform: translateY(15px); } to { opacity: 1; transform: translateY(0); } }

    .letter-preview {
        font-family: 'Times New Roman', Times, serif;
        line-height: 1.6;
        color: #1a202c;
    }
    .official-header {
        border-bottom: 2px solid #000;
        margin-bottom: 20px;
        text-align: center;
    }
</style>

<div class="max-w-4xl mx-auto bg-white shadow-xl rounded-2xl p-8 fade-in-up">

    <h2 class="text-2xl font-bold mb-6 text-gray-800 flex items-center no-print">
        <i class="fas fa-file-signature text-blue-600 mr-3"></i> Violation Notice Preview
    </h2>

    {{-- Success & Error Messages --}}
    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-700 rounded-lg border-l-4 border-green-500">
            {{ session('success') }}
        </div>
    @endif

    {{-- Trader & Status Quick Info --}}
    <div class="mb-6 p-4 bg-gray-50 rounded-lg grid grid-cols-1 md:grid-cols-2 gap-4 border border-gray-100 no-print">
        <div class="space-y-1">
            <p><strong>Trader:</strong> {{ optional($violation->trader)->name ?? 'N/A' }}</p>
            <p><strong>Stall:</strong> {{ $letterData['stall_number'] ?? 'N/A' }}</p>
        </div>
        <div class="space-y-1 text-right">
            <p><strong>Case ID:</strong> <span class="font-mono text-blue-700 font-bold">{{ $letterData['case_id'] ?? $violation->case_id }}</span></p>
            <p><strong>Status:</strong> <span class="font-semibold px-2 py-0.5 rounded bg-blue-100 text-blue-800 text-sm uppercase">{{ $violation->status }}</span></p>
        </div>
    </div>

    {{-- THE ACTUAL LETTER CONTENT --}}
    <div class="mb-6 p-10 border rounded-sm bg-white shadow-inner relative letter-preview">
        
        @if($letterData && is_array($letterData))

            {{-- 1. Official Letterhead --}}
            <div class="official-header pb-4">
                <h3 class="text-xl font-bold uppercase tracking-widest">{{ $letterData['letter_head'] ?? 'NAIROBI CITY COUNTY GOVERNMENT' }}</h3>
                <p class="text-sm italic">Market Enforcement Department | Enforcement Division</p>
            </div>

            {{-- 2. Meta Info --}}
            <div class="flex justify-between mb-8">
                <div>
                    <p><strong>Ref:</strong> {{ $letterData['reference_number'] ?? 'NCC/MKT/'.date('Y') }}</p>
                    <p><strong>Date:</strong> {{ $letterData['date_of_observation'] ?? date('d M, Y') }}</p>
                </div>
                <div class="text-right">
                    <p><strong>TO:</strong> {{ $letterData['recipient_name'] ?? 'The Trader' }}</p>
                    <p><strong>STALL:</strong> {{ $letterData['stall_number'] ?? 'N/A' }}</p>
                </div>
            </div>

            {{-- 3. Subject --}}
            <h3 class="text-center font-bold text-lg underline uppercase mb-6">
                {{ $letterData['subject'] ?? 'OFFICIAL NOTICE OF VIOLATION' }}
            </h3>

            {{-- 4. Opening --}}
            <p class="mb-4">{{ $letterData['opening_statement'] ?? '' }}</p>

            {{-- 5. Violation Details & Statutory Basis --}}
            <div class="mb-6 bg-gray-50 p-4 border-l-4 border-red-500">
                <h4 class="font-bold text-sm uppercase text-red-700 mb-2">Nature of Infringement:</h4>
                <p class="mb-3"><strong>Observation:</strong> {!! nl2br(e($letterData['violation_details'] ?? '')) !!}</p>
                
                @if(!empty($letterData['law_reference']))
                    <p class="text-sm"><strong>Statutory Basis:</strong> <em>{{ $letterData['law_reference'] }}</em></p>
                @endif
                
                @if(!empty($letterData['community_impact']))
                    <p class="text-sm mt-2 text-gray-600"><strong>Public Impact:</strong> {{ $letterData['community_impact'] }}</p>
                @endif
            </div>

            {{-- 6. Penalties & Deadline --}}
            <div class="mb-6">
                <h4 class="font-bold text-sm uppercase mb-2">Legal Consequences:</h4>
                <p class="text-gray-800 mb-2">{!! nl2br(e($letterData['legal_consequences'] ?? '')) !!}</p>
                <p class="font-bold text-red-600">Compliance Deadline: {{ $letterData['compliance_deadline'] ?? 'IMMEDIATE' }}</p>
            </div>

            {{-- 7. Mandatory Instructions --}}
            <div class="mb-6 border p-3 rounded-lg bg-blue-50">
                <h4 class="font-bold text-sm uppercase text-blue-800 mb-2">Required Remedial Action:</h4>
                <p class="text-sm">{!! nl2br(e($letterData['instructions'] ?? '')) !!}</p>
            </div>

            {{-- 8. Appeal Rights --}}
            @if(!empty($letterData['appeal_rights']))
                <div class="text-xs text-gray-500 italic mb-6">
                    <strong>Right of Reply:</strong> {{ $letterData['appeal_rights'] }}
                </div>
            @endif

            {{-- 9. Signature Block --}}
            <div class="mt-10">
                <p class="mb-8">Sincerely,</p>
                <div class="mt-4 font-bold uppercase">
                    {!! nl2br(e($letterData['signature_block'] ?? ($letterData['officer_name'] ?? 'Authorized Officer'))) !!}
                </div>
                <p class="text-xs mt-2 text-gray-400 font-mono italic">Official Digital Signature: {{ $letterData['case_id'] ?? 'VERIFIED' }}</p>
            </div>

        @else
            {{-- Fallback: show raw content if JSON failed --}}
            <div class="whitespace-pre-line text-gray-800">
                {{ $violation->ai_raw_message ?? 'No content generated.' }}
            </div>
        @endif
    </div>

    {{-- Action Buttons --}}
    <div class="flex flex-col sm:flex-row justify-end items-center sm:space-x-4 space-y-3 sm:space-y-0 mt-8 no-print">

        <form method="POST" action="{{ route('officer.violations.sendEmail', $violation->id) }}" class="w-full sm:w-auto">
            @csrf
            <button type="submit" class="group relative w-full sm:w-auto bg-emerald-600 hover:bg-emerald-700 text-white px-8 py-3 rounded-xl font-bold shadow-lg transition-all duration-300 transform hover:scale-105 active:scale-95 overflow-hidden">
                <span class="absolute inset-0 rounded-xl bg-emerald-400 opacity-0 group-hover:animate-ping-green"></span>
                <span class="relative flex items-center justify-center">
                    <i class="fas fa-paper-plane mr-2"></i> Send Official Email
                </span>
            </button>
        </form>

        <form method="POST" action="{{ route('officer.violations.approve', $violation->id) }}" class="w-full sm:w-auto">
            @csrf
            <button type="submit" class="group relative w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-xl font-bold shadow-lg transition-all duration-300 transform hover:scale-105 active:scale-95 overflow-hidden">
                <span class="absolute inset-0 rounded-xl bg-blue-400 opacity-0 group-hover:animate-ping-blue"></span>
                <span class="relative flex items-center justify-center">
                    <i class="fas fa-check-circle mr-2"></i> Finalise Letter
                </span>
            </button>
        </form>
    </div>

</div>

@endsection
