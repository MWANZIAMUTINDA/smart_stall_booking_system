@extends('layouts.app')

@section('page-title', 'Violation Records')

@section('content')
<div class="space-y-6">

    {{-- ── Header Section ─────────────────────────────────────────── --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-800 tracking-tight" style="font-family:'Montserrat',sans-serif;">
                Active Violation Logs
            </h1>
            <div class="flex items-center gap-2 mt-1">
                <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Portal</span>
                <span class="text-slate-300">/</span>
                <span class="text-[10px] font-black uppercase tracking-widest" style="color:#1E5128;">Officer Overview</span>
            </div>
        </div>
        <div>
            <a href="{{ route('officer.violations.create') }}" 
               class="inline-flex items-center gap-2 px-6 py-3 rounded-xl font-black text-xs uppercase tracking-widest text-white transition-all hover:-translate-y-0.5 active:scale-95"
               style="background:linear-gradient(135deg,#1E5128,#2d6a2e);box-shadow:0 10px 25px rgba(30,81,40,.25);">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                Record New Violation
            </a>
        </div>
    </div>

    {{-- ── Horizontal Filter Bar ──────────────────────────────────── --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
        <form method="GET" action="{{ route('officer.violations.index') }}" class="flex flex-col lg:flex-row items-end gap-4">
            
            {{-- Search Bar (Left side) --}}
            <div class="flex-1 w-full lg:w-auto">
                <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1 block ml-1">Search</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <input type="text" id="tableSearch" placeholder="Search by Trader Name or ID..." 
                           class="w-full pl-10 pr-4 py-2.5 rounded-xl border-2 text-sm font-medium focus:outline-none transition-all"
                           style="border-color:#e2e8f0;background:#f8fafc;focus:border-color:#1E5128;">
                </div>
            </div>

            {{-- Status Filter --}}
            <div class="w-full lg:w-48">
                <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1 block ml-1">Status</label>
                <select name="status" class="w-full border-2 rounded-xl px-4 py-2.5 text-sm font-bold focus:outline-none transition-all text-slate-700" style="border-color:#e2e8f0;background:#f8fafc;">
                    <option value="">All Statuses</option>
                    <option value="pending_ai" {{ request('status') == 'pending_ai' ? 'selected' : '' }}>Pending Action</option>
                    <option value="draft_ready" {{ request('status') == 'draft_ready' ? 'selected' : '' }}>Draft Ready</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="sent" {{ request('status') == 'sent' ? 'selected' : '' }}>Sent</option>
                </select>
            </div>

            {{-- Date From --}}
            <div class="w-full lg:w-40">
                <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1 block ml-1">Date From</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full border-2 rounded-xl px-4 py-2.5 text-sm font-bold focus:outline-none transition-all text-slate-700" style="border-color:#e2e8f0;background:#f8fafc;">
            </div>

            {{-- Date To --}}
            <div class="w-full lg:w-40">
                <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1 block ml-1">Date To</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}" class="w-full border-2 rounded-xl px-4 py-2.5 text-sm font-bold focus:outline-none transition-all text-slate-700" style="border-color:#e2e8f0;background:#f8fafc;">
            </div>

            {{-- Apply Filters Button --}}
            <div class="w-full lg:w-auto">
                <button type="submit" class="w-full lg:w-auto flex items-center justify-center gap-2 px-6 py-2.5 rounded-xl text-white text-xs font-black uppercase tracking-widest transition-all hover:-translate-y-0.5 active:scale-95" style="background:#1E5128;">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                    Apply
                </button>
            </div>
        </form>
    </div>

    {{-- ── Main Table Card ────────────────────────────────────────── --}}
    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
        
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between" style="background:linear-gradient(90deg,#f0f7f0,#fff);">
            <div class="flex items-center gap-2">
                <div class="w-1 h-6 rounded-full" style="background:#1E5128;"></div>
                <h3 class="text-sm font-black uppercase tracking-tight" style="color:#1E5128;">
                    Official Case Records
                </h3>
            </div>
            <span class="px-3 py-1 bg-white rounded-lg border border-slate-200 text-[10px] font-black text-slate-500">
                TOTAL: {{ $violations->count() }}
            </span>
        </div>

        <div class="max-h-[600px] overflow-y-auto">
            <table class="w-full text-left border-collapse" id="violationsTable">
                <thead class="sticky top-0 z-20 bg-white shadow-sm border-b border-slate-100">
                    <tr class="text-[10px] uppercase tracking-widest font-black text-slate-400">
                        <th class="px-6 py-4">Case ID</th>
                        <th class="px-6 py-4">Trader Profile</th>
                        <th class="px-6 py-4">Violation Details</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4">Date Logged</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($violations as $violation)
                        @php 
                            $searchString = strtolower($violation->id . ' ' . optional($violation->trader)->name);
                        @endphp
                        {{-- Zebra striping and hover effect --}}
                        <tr class="v-row even:bg-slate-50/50 hover:bg-slate-50 transition-colors group" data-search="{{ $searchString }}">
                            
                            {{-- Monospace ID --}}
                            <td class="px-6 py-4">
                                <span class="font-mono text-xs font-black text-slate-700 px-2 py-1 rounded-md" style="background:#f1f5f9;border:1px solid #e2e8f0;">
                                    #{{ str_pad($violation->id, 4, '0', STR_PAD_LEFT) }}
                                </span>
                            </td>
                            
                            {{-- Trader Profile --}}
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-800 text-sm">{{ optional($violation->trader)->name }}</div>
                                <div class="text-[10px] font-bold text-slate-400 mt-0.5">TRD-REF-{{ $violation->trader_id }}</div>
                            </td>
                            
                            {{-- Violation Type --}}
                            <td class="px-6 py-4">
                                <span class="font-black text-slate-800 text-sm" style="color:#1E5128;">
                                    {{ $violation->violation_type }}
                                </span>
                            </td>
                            
                            {{-- Status Pills --}}
                            <td class="px-6 py-4 text-center">
                                @if($violation->status === 'approved')
                                    <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[10px] font-black uppercase tracking-wider"
                                         style="background:#dcfce7;color:#166534;border:1px solid #bbf7d0;">
                                        Approved
                                    </div>
                                @elseif($violation->status === 'sent')
                                    <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[10px] font-black uppercase tracking-wider"
                                         style="background:#dbeafe;color:#1e40af;border:1px solid #bfdbfe;">
                                        Sent / Dispatched
                                    </div>
                                @elseif($violation->status === 'draft_ready')
                                    <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[10px] font-black uppercase tracking-wider"
                                         style="background:#fef3c7;color:#92400e;border:1px solid #fde68a;">
                                        Draft Ready
                                    </div>
                                @else
                                    <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[10px] font-black uppercase tracking-wider"
                                         style="background:#fee2e2;color:#991b1b;border:1px solid #fecaca;">
                                        Pending Action
                                    </div>
                                @endif
                            </td>
                            
                            {{-- Date --}}
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-600 text-xs">{{ $violation->created_at->format('M d, Y') }}</div>
                                <div class="text-[10px] font-medium text-slate-400">{{ $violation->created_at->format('h:i A') }}</div>
                            </td>
                            
                            {{-- Actions --}}
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end gap-2">
                                    
                                    {{-- Eye Icon (View) --}}
                                    <a href="{{ route('officer.violations.preview', $violation->id) }}" 
                                       class="w-8 h-8 rounded-lg bg-white border border-slate-200 flex items-center justify-center text-slate-400 hover:text-blue-600 hover:border-blue-200 hover:bg-blue-50 transition-colors shadow-sm" title="View/Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </a>

                                    {{-- Download Icon (PDF) --}}
                                    @if($violation->status === 'approved' || $violation->status === 'sent')
                                        <a href="{{ route('officer.violations.pdf', $violation->id) }}" 
                                           class="w-8 h-8 rounded-lg bg-white border border-slate-200 flex items-center justify-center text-slate-400 hover:text-rose-600 hover:border-rose-200 hover:bg-rose-50 transition-colors shadow-sm" title="Download PDF">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                        </a>
                                    @endif

                                    {{-- Resend Email Icon --}}
                                    @if($violation->status === 'sent')
                                        <form method="POST" action="{{ route('officer.violations.sendEmail', $violation->id) }}" class="inline-block">
                                            @csrf
                                            <button type="submit" 
                                                    class="w-8 h-8 rounded-lg bg-white border border-slate-200 flex items-center justify-center text-slate-400 hover:text-emerald-600 hover:border-emerald-200 hover:bg-emerald-50 transition-colors shadow-sm" title="Resend Email">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                            </button>
                                        </form>
                                    @endif

                                    {{-- Placeholder Three Dots (For future dropdown) --}}
                                    <button type="button" class="w-8 h-8 rounded-lg border border-transparent flex items-center justify-center text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition-colors" title="More Options">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/></svg>
                                    </button>

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-20 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <div class="w-14 h-14 rounded-2xl bg-slate-50 border-2 border-slate-100 flex items-center justify-center text-2xl">📁</div>
                                    <h4 class="text-sm font-black text-slate-400 uppercase tracking-widest">No Records Found</h4>
                                    <p class="text-[10px] font-bold text-slate-400">Try adjusting your filters or search terms.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ── Google Font: Montserrat ────────────────────────────────── --}}
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;900&display=swap" rel="stylesheet">

<style>
    /* Custom focus styles for inputs */
    input:focus, select:focus {
        box-shadow: 0 0 0 3px rgba(30,81,40,.12) !important;
        border-color: #1E5128 !important;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('tableSearch');
    const rows = document.querySelectorAll('.v-row');

    if (searchInput) {
        searchInput.addEventListener('input', function(e) {
            const term = e.target.value.toLowerCase().trim();
            rows.forEach(row => {
                const searchData = row.getAttribute('data-search');
                if (term === '' || searchData.includes(term)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    }
});
</script>
@endsection
