@extends('layouts.app')

@section('content')
<div class="container-fluid py-5" style="background: #f2f6fb; min-height: 100vh; font-family: 'Inter', sans-serif;">
    
    <div class="container">

        {{-- HEADER --}}
        <div class="row justify-content-center mb-5">
            <div class="col-lg-8">
                <div class="d-flex align-items-center justify-content-between border-bottom border-3 border-primary pb-3">
                    <div>
                        <h1 class="display-5 fw-black text-primary mb-0" style="letter-spacing: -1.2px;">
                            Report Violation
                        </h1>
                        <p class="text-uppercase small text-secondary fw-bold mb-0 mt-1">
                            Official Enforcement Entry
                        </p>
                    </div>
                    <div class="text-primary">
                        <i class="fas fa-file-signature fa-3x"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-8">

                {{-- SUCCESS MESSAGE --}}
                @if(session('success'))
                    <div class="alert alert-success border-0 shadow-sm rounded-4 p-4 mb-4 d-flex align-items-center">
                        <i class="fas fa-check-circle fa-2x me-3"></i>
                        <div class="fw-black text-uppercase">{{ session('success') }}</div>
                    </div>
                @endif

                {{-- VALIDATION ERRORS --}}
                @if($errors->any())
                    <div class="alert alert-danger border-0 shadow-sm rounded-4 p-4 mb-4">
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong class="fw-black text-uppercase">Correction Required</strong>
                        </div>

                        <ul class="mb-0 fw-bold">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- FORM CARD --}}
                <div class="card border-0 shadow-lg" style="border-radius: 25px; overflow: hidden;">
                    
                    <div class="card-header border-0 py-4 px-4" style="background: #002d5a;">
                        <h4 class="mb-0 text-white fw-black text-uppercase" style="letter-spacing: 1px;">
                            Violation Details
                        </h4>
                    </div>

                    <div class="card-body p-5 bg-white">

                        <form method="POST" action="{{ route('officer.violations.store') }}">
                            @csrf

                            {{-- SELECT ACTIVE TRADER --}}
                            <div class="mb-4">
                                <label for="trader_select"
                                       class="form-label fw-black text-primary text-uppercase small mb-2">
                                    <i class="fas fa-user-tag me-1"></i> Select Active Trader
                                </label>

                                <select name="trader_id"
                                        id="trader_select"
                                        required
                                        class="form-select form-select-lg border-2 shadow-sm rounded-4 py-3 fw-bold text-dark focus-blue">
                                    <option value="">-- Select Occupant --</option>

                                    @foreach($activeTraders as $trader)
                                        <option value="{{ $trader->user_id }}">
                                            {{ strtoupper($trader->trader_name) }}
                                            (Stall: #{{ $trader->stall_number }})
                                        </option>
                                    @endforeach

                                </select>
                            </div>

                            {{-- VIOLATION TYPE --}}
                            <div class="mb-4">
                                <label class="form-label fw-black text-primary text-uppercase small mb-2">
                                    <i class="fas fa-exclamation-circle me-1"></i> Violation Type
                                </label>

                                <select name="violation_type"
                                        class="form-select form-select-lg border-2 shadow-sm rounded-4 py-3 fw-bold text-dark focus-blue">
                                    <option value="Waste Management">Waste Management</option>
                                    <option value="Noise Violation">Noise Violation</option>
                                    <option value="Late Payment">Late Payment</option>
                                </select>
                            </div>

                            {{-- OFFICER NOTES --}}
                            <div class="mb-5">
                                <label class="form-label fw-black text-primary text-uppercase small mb-2">
                                    <i class="fas fa-pen-fancy me-1"></i> Officer Notes
                                </label>

                                <textarea name="officer_notes"
                                          rows="5"
                                          required
                                          class="form-control border-2 shadow-sm rounded-4 p-4 fw-medium focus-blue"
                                          placeholder="Enter detailed observations here..."></textarea>
                            </div>

                            {{-- SUBMIT BUTTON --}}
                            <div class="d-grid">
                                <button type="submit"
                                        class="btn btn-primary btn-xl py-3 fw-black text-uppercase shadow-blue border-0"
                                        style="border-radius: 15px; font-size: 1.15rem; letter-spacing: 1.3px;">
                                    <i class="fas fa-robot me-2"></i> Generate AI Letter
                                </button>

                                <small class="text-center text-muted mt-3 fw-bold">
                                    <i class="fas fa-info-circle me-1"></i>
                                    This will automatically draft a legal notice for the trader.
                                </small>
                            </div>

                        </form>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>

{{-- STYLES --}}
<style>
    .fw-black { font-weight: 900 !important; }
    .text-primary { color: #0056b3 !important; }
    .btn-primary { background-color: #0056b3 !important; border-color: #0056b3 !important; }

    .focus-blue:focus {
        border-color: #0056b3 !important;
        box-shadow: 0 0 0 0.25rem rgba(0, 86, 179, 0.18) !important;
    }

    .shadow-blue {
        box-shadow: 0 12px 30px rgba(0, 86, 179, 0.35) !important;
    }

    .form-select,
    .form-control {
        border-color: #dde3eb;
        transition: all 0.2s ease;
    }

    .btn-xl:hover {
        transform: translateY(-3px);
        filter: brightness(1.08);
        box-shadow: 0 16px 35px rgba(0, 86, 179, 0.45) !important;
    }

    .btn-xl:active {
        transform: translateY(1px);
    }
</style>

@endsection