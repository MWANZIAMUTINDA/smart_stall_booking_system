@extends('layouts.app')

@section('page-title', 'Available Stalls')

@section('content')
<div class="space-y-6">

    <!-- Header & Live Count -->
    <div class="bg-gradient-to-r from-blue-900 to-indigo-900 p-8 rounded-3xl shadow-xl border border-white/10 flex flex-col md:flex-row justify-between items-center gap-4">
        <div>
            <h2 class="text-3xl font-black text-white tracking-tight">Market Map</h2>
            <p class="text-blue-200/60 text-xs uppercase tracking-widest font-bold mt-2 italic">Select a zone to begin trading</p>
        </div>
        <div class="bg-white/10 backdrop-blur-md px-6 py-3 rounded-2xl border border-white/20 text-center">
            <span class="text-white font-black text-2xl tabular-nums">{{ $stalls->count() }}</span>
            <p class="text-blue-300 text-[9px] uppercase font-black tracking-tighter">Units Ready</p>
        </div>
    </div>

    @if($stalls->count() > 0)
        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
            
            <!-- SCROLLABLE AREA -->
            <div class="max-h-[500px] overflow-y-auto scrollbar-thin scrollbar-thumb-blue-100">
                <table class="w-full text-left border-collapse">
                    <thead class="sticky top-0 z-20 bg-slate-50/95 backdrop-blur-sm border-b border-slate-100">
                        <tr class="text-slate-400 text-[10px] uppercase tracking-widest font-bold">
                            <th class="px-8 py-4 text-blue-600">Unit ID</th>
                            <th class="px-8 py-4">Market Zone</th>
                            <th class="px-8 py-4">Exact Location</th>
                            <th class="px-8 py-4">Daily Rate</th>
                            <th class="px-8 py-4 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 text-sm">
                        @foreach($stalls as $stall)
                            <tr class="hover:bg-blue-50/40 transition-colors group">
                                <td class="px-8 py-4 font-black text-slate-800 text-lg">
                                    #{{ $stall->stall_number }}
                                </td>
                                <td class="px-8 py-4">
                                    {{-- Automatic styling based on Zone name --}}
                                    <span class="px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-tighter border 
                                        {{ str_contains($stall->zone, '1') ? 'bg-blue-50 text-blue-600 border-blue-100' : 
                                           (str_contains($stall->zone, '2') ? 'bg-purple-50 text-purple-600 border-purple-100' : 
                                           (str_contains($stall->zone, '3') ? 'bg-amber-50 text-amber-600 border-amber-100' : 
                                           'bg-emerald-50 text-emerald-600 border-emerald-100')) }}">
                                        {{ $stall->zone }}
                                    </span>
                                </td>
                                <td class="px-8 py-4">
                                    <span class="text-slate-500 font-medium italic text-xs leading-tight block max-w-xs">
                                        {{ $stall->location_desc }}
                                    </span>
                                </td>
                                <td class="px-8 py-4">
                                    <span class="text-slate-900 font-black tabular-nums">KES {{ number_format($stall->price) }}</span>
                                </td>
                                <td class="px-8 py-4 text-center">
                                    <a href="{{ route('trader.bookings.create', $stall->id) }}" 
                                       class="inline-block px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-[11px] font-black uppercase rounded-xl shadow-lg shadow-blue-600/20 hover:scale-105 active:scale-95 transition-all">
                                        Reserve
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="bg-white rounded-3xl border-2 border-dashed border-slate-200 p-20 text-center shadow-inner">
            <p class="text-slate-400 font-bold uppercase text-xs tracking-widest">No Stalls Available in Any Zone</p>
        </div>
    @endif

    <p class="text-center text-[10px] text-slate-300 font-bold uppercase tracking-[0.3em] pt-4">
        Muthurwa Market Management &bull; Digital Hub
    </p>
</div>
@endsection
