@extends('layouts.app')

@section('content')

<div class="max-w-3xl mx-auto bg-white shadow-lg rounded-2xl p-8">

    <h2 class="text-xl font-bold mb-6">Report Violation</h2>

    <form method="POST" action="{{ route('officer.violations.store') }}">
        @csrf

        <div class="mb-4">
            <label class="block font-medium mb-2">Select Trader</label>
            <select name="trader_id" class="w-full border rounded-lg p-2">
                @foreach($traders as $trader)
                    <option value="{{ $trader->id }}">
                        {{ $trader->name }} - Stall {{ $trader->stall->stall_number ?? '' }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label class="block font-medium mb-2">Violation Type</label>
            <select name="violation_type" class="w-full border rounded-lg p-2">
                <option value="Waste Management">Waste Management</option>
                <option value="Noise Violation">Noise Violation</option>
                <option value="Late Payment">Late Payment</option>
            </select>
        </div>

        <div class="mb-6">
            <label class="block font-medium mb-2">Officer Notes</label>
            <textarea name="officer_notes"
                      class="w-full border rounded-lg p-3"
                      rows="4"></textarea>
        </div>

        <button class="bg-red-600 text-white px-6 py-2 rounded-lg">
            Generate AI Letter
        </button>

    </form>

</div>

@endsection