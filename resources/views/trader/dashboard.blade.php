@extends('layouts.app')

@section('page-title', 'Available Stalls')

@section('content')
<div class="space-y-6" x-data="{ search: '' }">

    {{-- ===================== --}}
    {{-- 🔔 TRADER STATUS ALERTS --}}
    {{-- ===================== --}}

    @if(auth()->user()->hasWarning())
        <div class="bg-amber-50 border-l-4 border-amber-500 p-6 rounded-2xl shadow-sm flex items-start gap-4">
            <div class="text-2xl">⚠️</div>
            <div>
                <h4 class="text-amber-800 font-black text-sm uppercase tracking-tight">
                    Official Account Warning
                </h4>
                <p class="text-amber-700 text-xs mt-1 font-medium">
                    {{ auth()->user()->restriction_reason ?? 'Please comply with market regulations to avoid suspension.' }}
                </p>
            </div>
        </div>
    @endif

    @if(auth()->user()->isBlocked())
        <div class="bg-rose-50 border-l-4 border-rose-500 p-6 rounded-2xl shadow-sm flex items-start gap-4">
            <div class="text-2xl">🚫</div>
            <div>
                <h4 class="text-rose-800 font-black text-sm uppercase tracking-tight">
                    Booking Access Suspended
                </h4>
                <p class="text-rose-700 text-xs mt-1 font-medium italic">
                    Reason: {{ auth()->user()->restriction_reason }}
                </p>
                <p class="text-rose-600 text-[10px] mt-2 font-bold uppercase">
                    Please visit the Market Office to resolve this issue.
                </p>
            </div>
        </div>
    @endif


    {{-- ===================== --}}
    {{-- 🔵 HEADER + SEARCH BAR --}}
    {{-- ===================== --}}
    <div class="bg-gradient-to-r from-blue-900 to-indigo-900 p-8 rounded-3xl shadow-xl border border-white/10 flex flex-col md:flex-row justify-between items-center gap-6">
        
        <div class="w-full md:w-1/3 text-center md:text-left">
            <h2 class="text-3xl font-black text-white tracking-tight">
                Market Map
            </h2>
            <p class="text-blue-200/60 text-[10px] uppercase tracking-[0.2em] font-bold mt-1">
                Live Occupancy Oversight
            </p>
        </div>

        <div class="relative w-full md:w-2/3 max-w-lg">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-blue-300">
                🔍
            </div>
            <input 
                type="text" 
                x-model="search" 
                placeholder="Search by Stall #, Zone, or Location..." 
                class="w-full bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl py-3.5 pl-12 pr-4 text-white placeholder-blue-200/50 focus:ring-4 focus:ring-blue-500/20 focus:border-blue-400 outline-none transition-all"
            >
        </div>
    </div>


    {{-- ===================== --}}
    {{-- 📋 STALLS TABLE --}}
    {{-- ===================== --}}
    @if($stalls->isNotEmpty())
        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden 
            {{ auth()->user()->isBlocked() ? 'opacity-50 pointer-events-none grayscale' : '' }}">
            
            <div class="max-h-[500px] overflow-y-auto scrollbar-thin scrollbar-thumb-blue-100">
                <table class="w-full text-left border-collapse">

                    {{-- TABLE HEAD --}}
                    <thead class="sticky top-0 z-20 bg-slate-50/95 backdrop-blur-sm border-b border-slate-100">
                        <tr class="text-slate-400 text-[10px] uppercase tracking-widest font-bold">
                            <th class="px-8 py-4">Unit ID</th>
                            <th class="px-8 py-4">Zone</th>
                            <th class="px-8 py-4">Location</th>
                            <th class="px-8 py-4">Price</th>
                            <th class="px-8 py-4 text-center">Action</th>
                        </tr>
                    </thead>

                    {{-- TABLE BODY --}}
                    <tbody class="divide-y divide-slate-50 text-sm">
                        @foreach($stalls as $stall)
                            <tr 
                                x-show="search === '' || 
                                       '{{ strtolower($stall->stall_number) }}'.includes(search.toLowerCase()) || 
                                       '{{ strtolower($stall->zone) }}'.includes(search.toLowerCase()) || 
                                       '{{ strtolower($stall->location_desc) }}'.includes(search.toLowerCase())"
                                class="hover:bg-blue-50/40 transition-colors group"
                            >
                                <td class="px-8 py-4 font-black text-slate-800 text-lg">
                                    #{{ $stall->stall_number }}
                                </td>

                                <td class="px-8 py-4">
                                    <span class="px-2 py-1 bg-slate-100 text-slate-600 rounded-md text-[10px] font-bold uppercase border border-slate-200">
                                        {{ $stall->zone }}
                                    </span>
                                </td>

                                <td class="px-8 py-4">
                                    <span class="text-slate-500 italic text-xs leading-tight block max-w-xs">
                                        {{ $stall->location_desc }}
                                    </span>
                                </td>

                                <td class="px-8 py-4">
                                    <span class="text-slate-900 font-black tabular-nums">
                                        KES {{ number_format($stall->price) }}
                                    </span>
                                </td>

                                <td class="px-8 py-4 text-center">
                                    @if(auth()->user()->isBlocked())
                                        <button disabled class="inline-block px-6 py-2.5 bg-slate-300 text-slate-500 text-[11px] font-black uppercase rounded-xl cursor-not-allowed">
                                            Locked
                                        </button>
                                    @else
                                        <a href="{{ route('trader.bookings.create', $stall->id) }}" 
                                           class="inline-block px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-[11px] font-black uppercase rounded-xl shadow-lg shadow-blue-600/20 transition-all">
                                            Reserve
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>
        </div>
    @else
        {{-- EMPTY STATE --}}
        <div class="bg-white rounded-3xl border-2 border-dashed border-slate-200 p-20 text-center shadow-inner">
            <p class="text-slate-400 font-bold uppercase text-xs tracking-widest">
                No Stalls Available
            </p>
        </div>
    @endif

</div>
@endsection