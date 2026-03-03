@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Violation Draft Preview</h1>
    
    <div class="card">
        <div class="card-body">
            <h5>Violation Type: {{ $violation->violation_type }}</h5>
            <p><strong>Officer Notes:</strong> {{ $violation->officer_notes }}</p>
            <hr>
            <h6>AI Generated Letter:</h6>
            <div class="p-3 bg-light border">
                {!! nl2br(e($violation->ai_raw_message)) !!}
            </div>
        </div>
        <div class="card-footer">
            <a href="{{ route('officer.violations.index') }}" class="btn btn-secondary">Back</a>
            <button class="btn btn-success">Approve & Send</button>
        </div>
    </div>
</div>
@endsection
