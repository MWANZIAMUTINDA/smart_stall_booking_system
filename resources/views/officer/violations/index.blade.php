@extends('layouts.app')

@section('content')
<div class="container-fluid py-5" style="background: #f0f4f8; min-height: 100vh; font-family: 'Inter', 'Segoe UI', sans-serif;">
    <div class="container">
        
        <!-- HEADER SECTION: MAXIMUM VISIBILITY -->
        <div class="row align-items-center mb-5">
            <div class="col-md-7">
                <h1 class="display-4 fw-black text-primary mb-1" style="letter-spacing: -2px; text-transform: uppercase; line-height: 1;">
                    Violation Records
                </h1>
                <div class="mt-2">
                    <span class="text-secondary fw-black text-uppercase small letter-spacing-1">Portal</span>
                    <span class="mx-2 text-muted">/</span>
                    <span class="text-primary fw-black text-uppercase small letter-spacing-1">Officer Overview</span>
                </div>
            </div>
            <div class="col-md-5 text-md-end mt-4 mt-md-0">
                <a href="{{ route('officer.violations.create') }}" 
                   class="btn btn-primary shadow-blue px-5 py-3 fw-black text-uppercase border-0" 
                   style="border-radius: 12px; font-size: 1.1rem; transition: transform 0.2s;">
                    <i class="fas fa-plus-circle me-2"></i> Record New Violation
                </a>
            </div>
        </div>

        <!-- MAIN TABLE CARD -->
        <div class="card border-0 shadow-lg" style="border-radius: 20px; overflow: hidden;">
            
            <!-- ACTIVE LOGS BAR -->
            <div class="card-header border-0 py-4 px-4 d-flex align-items-center justify-content-between" 
                 style="background: #002d5a;">
                <h3 class="h4 mb-0 fw-black text-white text-uppercase" style="letter-spacing: 2px;">
                    Active Logs
                </h3>
                <span class="badge bg-white text-primary fw-black px-3 py-2 rounded-pill" style="font-size: 0.8rem;">
                    TOTAL: {{ $violations->count() }}
                </span>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr class="text-primary fw-black text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">
                            <th class="ps-4 py-4 border-0">ID</th>
                            <th class="border-0">Trader Profile</th>
                            <th class="border-0">Violation Type</th>
                            <th class="border-0 text-center">Status</th>
                            <th class="border-0">Date Created</th>
                            <th class="pe-4 border-0 text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($violations as $violation)
                            <tr class="bg-white border-bottom">
                                <td class="ps-4">
                                    <span class="fw-black text-primary">#{{ $violation->id }}</span>
                                </td>
                                <td>
                                    <div class="fw-black text-dark fs-5">{{ optional($violation->trader)->name }}</div>
                                    <small class="text-muted fw-bold">TRD-REF-{{ $violation->trader_id }}</small>
                                </td>
                                <td>
                                    <span class="fw-bold text-dark">{{ $violation->violation_type }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-primary px-4 py-2 rounded-pill fw-black text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.5px;">
                                        {{ $violation->status }}
                                    </span>
                                </td>
                                <td class="text-secondary fw-bold">
                                    {{ $violation->created_at->format('M d, Y') }}
                                </td>
                                <td class="pe-4 text-end">
                                    <a href="{{ route('officer.violations.preview', $violation->id) }}" 
                                       class="btn btn-primary fw-black px-4 py-2 rounded-pill border-0 shadow-blue-sm">
                                        VIEW
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <h4 class="text-muted fw-black">NO RECORDS FOUND</h4>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    .fw-black { font-weight: 900 !important; }
    .text-primary { color: #0056b3 !important; }
    .bg-primary { background-color: #0056b3 !important; }
    .letter-spacing-1 { letter-spacing: 1px; }
    
    .shadow-blue { box-shadow: 0 8px 20px rgba(0, 86, 179, 0.4) !important; }
    .shadow-blue-sm { box-shadow: 0 4px 10px rgba(0, 86, 179, 0.3) !important; }

    .btn:hover { transform: translateY(-2px); filter: brightness(1.1); }
    
    .table-hover tbody tr:hover {
        background-color: #f8fbff !important;
        transition: 0.2s;
    }
</style>
@endsection
