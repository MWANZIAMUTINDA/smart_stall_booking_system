@extends('layouts.app')

@section('page-title', 'Officer Portal')

@section('content')
<div class="space-y-6">

    {{-- ── 1. Glassmorphism Hero & Greeting ──────────────────────── --}}
    <div class="relative overflow-hidden rounded-3xl border border-white/20 shadow-xl backdrop-blur-xl"
         style="background: rgba(30, 81, 40, 0.85);">
        
        {{-- Decorative Nairobi Skyline/Watermark element --}}
        <div class="absolute bottom-0 right-0 opacity-[0.03] pointer-events-none" style="font-size:160px;line-height:1;font-weight:900;color:white;letter-spacing:-6px;">🏙️</div>
        
        {{-- Glass glow --}}
        <div class="absolute -top-24 -left-24 w-64 h-64 rounded-full pointer-events-none" style="background:rgba(212,163,115,.15);filter:blur(60px);"></div>
        <div class="absolute -bottom-24 -right-24 w-64 h-64 rounded-full pointer-events-none" style="background:rgba(255,255,255,.05);filter:blur(60px);"></div>

        <div class="p-8 relative z-10">
            <div class="flex items-start gap-4 mb-2">
                <div class="w-12 h-12 rounded-2xl flex items-center justify-center flex-shrink-0" style="background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.2);backdrop-filter:blur(10px);">
                    <svg class="w-6 h-6" style="color:#D4A373;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </div>
                <div>
                    <h2 class="text-2xl font-black text-white tracking-tight">
                        Habari, Officer {{ explode(' ', auth()->user()->name ?? 'Officer')[0] }}.
                    </h2>
                    <p class="text-sm font-medium mt-1" style="color:rgba(255,255,255,.7);">
                        Here is the real-time status of Muthurwa Market today.
                    </p>
                </div>
            </div>
        </div>

        {{-- County stripe --}}
        <div class="absolute bottom-0 left-0 right-0 h-1" style="background:linear-gradient(90deg,#1E5128 0%,#1E5128 33%,#D4A373 33%,#D4A373 66%,#1E5128 66%);"></div>
    </div>

    {{-- ── 2. Visual KPIs (Card Style) ──────────────────────────── --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 reveal">
        
        {{-- Total --}}
        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm transition-shadow hover:shadow-md flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center bg-slate-50 border border-slate-100">
                <span class="text-xl">🏢</span>
            </div>
            <div>
                <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Total Stalls</p>
                <p class="text-2xl font-black text-slate-800">{{ $stallsCount }}</p>
            </div>
        </div>

        {{-- Open --}}
        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm transition-shadow hover:shadow-md flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center" style="background:#f0fdf4;border:1px solid #bbf7d0;">
                <span class="text-xl text-emerald-600">✅</span>
            </div>
            <div>
                <p class="text-[10px] font-black uppercase tracking-widest text-emerald-600">Open</p>
                <p class="text-2xl font-black text-emerald-700">{{ $availableCount }}</p>
            </div>
        </div>

        {{-- In Use --}}
        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm transition-shadow hover:shadow-md flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center" style="background:#fef2f2;border:1px solid #fecaca;">
                <span class="text-xl text-rose-600">👤</span>
            </div>
            <div>
                <p class="text-[10px] font-black uppercase tracking-widest text-rose-600">In Use</p>
                <p class="text-2xl font-black text-rose-700">{{ $occupiedCount }}</p>
            </div>
        </div>

        {{-- Soon --}}
        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm transition-shadow hover:shadow-md flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center" style="background:#fffbeb;border:1px solid #fde68a;">
                <span class="text-xl text-amber-500">⏱️</span>
            </div>
            <div>
                <p class="text-[10px] font-black uppercase tracking-widest text-amber-600">Booked Soon</p>
                <p class="text-2xl font-black text-amber-700">{{ $bookedSoonCount }}</p>
            </div>
        </div>

    </div>

    {{-- ── 3. Action Buttons (Outline / Muted Style) ────────────── --}}
    <div class="flex flex-col sm:flex-row gap-4 reveal reveal-delay-1">
        <a href="{{ route('officer.violations.create') }}"
           class="flex-1 flex items-center justify-center gap-2 py-3.5 rounded-xl font-bold text-sm uppercase tracking-wider transition-all hover:bg-slate-50 active:scale-95 border-2"
           style="border-color:#1E5128; color:#1E5128;">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            Generate Violation
        </a>
        <a href="{{ route('officer.violations.index') }}"
           class="flex-1 flex items-center justify-center gap-2 py-3.5 rounded-xl font-bold text-sm uppercase tracking-wider transition-all hover:bg-slate-50 active:scale-95 border-2 border-slate-200 text-slate-600">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            View Past Violations
        </a>
    </div>

    {{-- ── 4. Interactive Data Table & Controls ─────────────────── --}}
    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden reveal reveal-delay-2 flex flex-col">
        
        {{-- Table Controls (Search & Filters) --}}
        <div class="p-5 border-b border-slate-100 flex flex-col lg:flex-row lg:items-center justify-between gap-4 bg-slate-50/50">
            <div class="flex items-center gap-2 flex-wrap" id="zoneFilters">
                <span class="text-[10px] font-black uppercase tracking-widest text-slate-400 mr-2">Filter Zone:</span>
                <button class="zone-btn active px-3 py-1.5 rounded-lg text-[10px] font-black uppercase transition-all border" data-zone="all" style="background:#1E5128;color:white;border-color:#1E5128;">All Zones</button>
                <button class="zone-btn px-3 py-1.5 rounded-lg text-[10px] font-bold uppercase transition-all border bg-white text-slate-600 border-slate-200 hover:border-slate-300" data-zone="Zone 1">Zone 1</button>
                <button class="zone-btn px-3 py-1.5 rounded-lg text-[10px] font-bold uppercase transition-all border bg-white text-slate-600 border-slate-200 hover:border-slate-300" data-zone="Zone 2">Zone 2</button>
                <button class="zone-btn px-3 py-1.5 rounded-lg text-[10px] font-bold uppercase transition-all border bg-white text-slate-600 border-slate-200 hover:border-slate-300" data-zone="Zone 3">Zone 3</button>
            </div>
            <div class="relative max-w-sm w-full">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <input type="text" id="tableSearch" placeholder="Search stall #, occupant, or contact..." 
                       class="w-full pl-10 pr-4 py-2 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:border-transparent transition-all"
                       style="focus:ring-color:rgba(30,81,40,.2);">
            </div>
        </div>

        {{-- Scrollable Table --}}
        <div class="max-h-[600px] overflow-y-auto">
            <table class="w-full text-left border-collapse" id="stallsTable">
                <thead class="sticky top-0 z-20 bg-white shadow-sm">
                    <tr class="text-[10px] uppercase tracking-widest font-black text-slate-400 border-b border-slate-100">
                        <th class="px-6 py-4">Stall Details</th>
                        <th class="px-6 py-4">Smart Status</th>
                        <th class="px-6 py-4">Current Occupant</th>
                        <th class="px-6 py-4">Schedule</th>
                        <th class="px-6 py-4">Upcoming</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 text-sm">
                    @forelse($occupancyData as $data)
                    @php 
                        $avail = $data->availability; 
                        $searchString = strtolower($data->stall->stall_number . ' ' . $data->stall->zone . ' ' . ($data->current_booking ? $data->current_booking->user->name . ' ' . $data->current_booking->user->phone_number : ''));
                    @endphp
                    <tr class="stall-row hover:bg-slate-50 transition-colors group" data-zone="{{ $data->stall->zone }}" data-search="{{ $searchString }}">

                        {{-- Stall Details --}}
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center font-black text-slate-700 text-sm border border-slate-200">
                                    #{{ $data->stall->stall_number }}
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-[10px] font-bold uppercase text-slate-500">{{ $data->stall->zone }}</span>
                                </div>
                            </div>
                        </td>

                        {{-- High-Contrast Glowing Pills --}}
                        <td class="px-6 py-4">
                            @if($avail->status === 'occupied')
                                <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[10px] font-black uppercase tracking-wider"
                                     style="background:#fff1f2;color:#be123c;border:1px solid #fecdd3;box-shadow:0 0 10px rgba(225,29,72,.15);">
                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500 animate-pulse"></span>
                                    Occupied
                                </div>
                                @if($data->is_renewing)
                                    <div class="mt-1 text-[9px] font-bold text-blue-600 uppercase">🔄 Extending</div>
                                @endif
                                @if($data->current_booking && $data->current_booking->end_time->isPast())
                                    <div class="mt-1 text-[9px] font-bold text-rose-600 uppercase animate-pulse">🚨 Overstay</div>
                                @endif
                            @elseif($avail->status === 'booked_soon')
                                <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[10px] font-black uppercase tracking-wider"
                                     style="background:#fffbeb;color:#b45309;border:1px solid #fde68a;box-shadow:0 0 10px rgba(245,158,11,.15);">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                    Soon
                                </div>
                                <div class="mt-1 text-[9px] font-bold text-amber-700">{{ $avail->detail }}</div>
                            @elseif($avail->status === 'available_until')
                                <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[10px] font-black uppercase tracking-wider"
                                     style="background:#f0fdf4;color:#15803d;border:1px solid #bbf7d0;box-shadow:0 0 10px rgba(34,197,94,.15);">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    Available
                                </div>
                                <div class="mt-1 text-[9px] font-bold text-blue-600">{{ $avail->detail }}</div>
                            @else
                                <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[10px] font-black uppercase tracking-wider"
                                     style="background:#f0fdf4;color:#15803d;border:1px solid #bbf7d0;box-shadow:0 0 10px rgba(34,197,94,.15);">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    Fully Open
                                </div>
                            @endif
                        </td>

                        {{-- Current Occupant with Avatar --}}
                        <td class="px-6 py-4">
                            @if($data->current_booking)
                                @php 
                                    $name = $data->current_booking->user->name;
                                    $initials = collect(explode(' ', $name))->map(fn($n) => substr($n,0,1))->take(2)->implode('');
                                @endphp
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-[10px] font-black text-white" style="background:#1E5128;">
                                        {{ strtoupper($initials) }}
                                    </div>
                                    <div class="flex flex-col">
                                        <p class="font-bold text-slate-800 text-sm">{{ $name }}</p>
                                        <p class="text-[10px] font-medium text-slate-500">{{ $data->current_booking->user->phone_number }}</p>
                                    </div>
                                </div>
                            @else
                                <p class="font-bold text-slate-400 italic text-sm">—</p>
                            @endif
                        </td>

                        {{-- Leaving Schedule --}}
                        <td class="px-6 py-4">
                            @if($data->current_booking)
                                @php $end = clone $data->current_booking->end_time; @endphp
                                <p class="font-black text-[10px] uppercase text-slate-400 mb-0.5">Move out by:</p>
                                <p class="font-bold text-sm text-rose-600">{{ $end->format('H:i') }}</p>
                                <p class="text-[10px] font-medium text-slate-500">{{ $end->format('jS M') }}</p>
                            @elseif($avail->status === 'booked_soon' && $avail->next_booking)
                                <p class="font-black text-[10px] uppercase text-amber-600 mb-0.5">Incoming:</p>
                                <p class="font-bold text-sm text-amber-700">{{ $avail->next_booking->start_time->format('H:i') }}</p>
                                <p class="text-[10px] font-medium text-slate-500">{{ $avail->next_booking->start_time->format('jS M') }}</p>
                            @else
                                <p class="text-slate-400 font-medium text-xs">--</p>
                            @endif
                        </td>
                        
                        {{-- Upcoming Booking --}}
                        <td class="px-6 py-4">
                            @if($data->next_booking)
                                @php $start = clone $data->next_booking->start_time; @endphp
                                <p class="font-bold text-slate-800 text-xs">{{ $data->next_booking->user->name }}</p>
                                <p class="font-black text-[9px] uppercase text-blue-600 mt-0.5">Arrives {{ $start->format('d M H:i') }}</p>
                            @elseif($data->is_renewing)
                                <p class="text-[10px] font-bold text-slate-400 italic uppercase">Renewal Active</p>
                            @elseif($avail->status === 'booked_soon' && $avail->next_booking)
                                <p class="font-bold text-amber-800 text-xs">{{ $avail->next_booking->user->name ?? 'Reserved' }}</p>
                                <p class="font-black text-[9px] uppercase text-amber-600 mt-0.5">Arrives {{ $avail->next_booking->start_time->format('d M H:i') }}</p>
                            @else
                                <p class="text-slate-400 font-medium text-[10px] uppercase italic">No Upcoming</p>
                            @endif
                        </td>

                        {{-- Hover Actions Column --}}
                        <td class="px-6 py-4 text-right">
                            {{-- Visible only on hover of the row --}}
                            <div class="opacity-0 group-hover:opacity-100 transition-opacity flex justify-end gap-2">
                                @if($data->current_booking)
                                    <a href="{{ route('officer.violations.create') }}" title="Report Violation"
                                       class="w-8 h-8 rounded-lg bg-white border border-slate-200 flex items-center justify-center text-slate-400 hover:text-rose-600 hover:border-rose-200 hover:bg-rose-50 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                    </a>
                                @endif
                                <button class="w-8 h-8 rounded-lg bg-white border border-slate-200 flex items-center justify-center text-slate-400 hover:text-blue-600 hover:border-blue-200 hover:bg-blue-50 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z"/></svg>
                                </button>
                            </div>
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-20 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <div class="w-14 h-14 rounded-2xl bg-slate-50 border-2 border-slate-100 flex items-center justify-center text-2xl">🛡️</div>
                                <p class="font-black uppercase text-xs tracking-widest text-slate-400">No Stalls in the System</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('tableSearch');
    const zoneBtns = document.querySelectorAll('.zone-btn');
    const rows = document.querySelectorAll('.stall-row');

    let currentZone = 'all';
    let currentSearch = '';

    function filterTable() {
        rows.forEach(row => {
            const zoneMatch = currentZone === 'all' || row.dataset.zone === currentZone;
            const searchMatch = currentSearch === '' || row.dataset.search.includes(currentSearch);
            
            if (zoneMatch && searchMatch) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    searchInput.addEventListener('input', (e) => {
        currentSearch = e.target.value.toLowerCase().trim();
        filterTable();
    });

    zoneBtns.forEach(btn => {
        btn.addEventListener('click', (e) => {
            // Update button styles
            zoneBtns.forEach(b => {
                b.classList.remove('active');
                b.style.background = 'white';
                b.style.color = '#475569'; // slate-600
                b.style.borderColor = '#e2e8f0'; // slate-200
            });
            
            const target = e.currentTarget;
            target.classList.add('active');
            target.style.background = '#1E5128';
            target.style.color = 'white';
            target.style.borderColor = '#1E5128';

            currentZone = target.dataset.zone;
            filterTable();
        });
    });
});
</script>
@endsection
