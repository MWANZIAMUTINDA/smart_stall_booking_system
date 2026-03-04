@extends('layouts.app')

@section('content')

<div class="max-w-4xl mx-auto bg-white shadow-lg rounded-2xl p-8">

    <h2 class="text-2xl font-bold mb-6 text-gray-800">
        Violation Letter
    </h2>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-700 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    {{-- Trader & Violation Info --}}
    <div class="mb-6 p-4 bg-gray-50 rounded-lg space-y-2">
        <p>
            <strong>Trader:</strong>
            {{ optional($violation->trader)->name ?? 'Trader Not Found' }}
        </p>

        <p>
            <strong>Stall:</strong>
            {{ optional(optional($violation->trader)->stall)->stall_number ?? 'N/A' }}
        </p>

        <p>
            <strong>Officer:</strong>
            {{ optional($violation->officer)->name ?? 'Officer Not Found' }}
        </p>

        <p>
            <strong>Violation Type:</strong>
            {{ $violation->violation_type ?? 'N/A' }}
        </p>

        <p>
            <strong>Status:</strong>
            {{ $violation->status ?? 'Unknown' }}
        </p>
    </div>

    {{-- AI Letter Preview (Structured JSON Display) --}}
    <div class="mb-6 p-6 border rounded-lg bg-gray-50">
        @if($letterData)

            <h3 class="text-lg font-semibold mb-2">
                {{ $letterData['subject'] ?? '' }}
            </h3>

            <p class="mb-4">
                <strong>{{ $letterData['salutation'] ?? '' }}</strong>
            </p>

            <p class="mb-4">
                {{ $letterData['opening_statement'] ?? '' }}
            </p>

            <p class="mb-4">
                <strong>Date of Observation:</strong>
                {{ $letterData['violation_details']['date_of_observation'] ?? '' }} <br>

                <strong>Officer:</strong>
                {{ $letterData['violation_details']['officer_name'] ?? '' }} <br>

                <strong>Description:</strong>
                {{ $letterData['violation_details']['description_of_violation'] ?? '' }} <br>

                <strong>Notes:</strong>
                {{ $letterData['violation_details']['officer_notes'] ?? '' }}
            </p>

            <p class="mb-4">
                <strong>Legal Consequences:</strong>
                {{ $letterData['legal_consequences']['further_actions'] ?? '' }}
            </p>

            <p class="mt-6 whitespace-pre-line">
                {{ $letterData['closing'] ?? '' }}
            </p>

        @else
            <p>No AI letter generated.</p>
        @endif
    </div>

    {{-- Approve Button --}}
    <form method="POST"
          action="{{ route('officer.violations.approve', $violation->id) }}">
        @csrf

        <div class="flex justify-end">
            <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg shadow-md 
                           transform transition-all duration-200 ease-in-out 
                           hover:scale-105 active:scale-95 focus:outline-none focus:ring-2 focus:ring-blue-400">
                Approve & Save Final Letter
            </button>
        </div>
    </form>

</div>

@endsection