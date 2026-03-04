@extends('layouts.app')

@section('page-title', 'My Reservations')

@section('content')
<div class="space-y-6">

    <!-- Header Section -->
    <div class="bg-gradient-to-r from-blue-900 to-indigo-900 text-white p-8 rounded-3xl shadow-xl border border-white/10 relative overflow-hidden">
        <div class="relative z-10">
            <h2 class="text-2xl font-black tracking-tight">Booking History</h2>
            <p class="text-blue-200/70 text-sm mt-1 font-medium">
                Track your active stalls and past payments at Muthurwa.
            </p>
        </div>
        <div class="absolute right-0 bottom-0 opacity-10 text-9xl font-black select-none translate-y-10 translate-x-5">
            STALLS
        </div>
    </div>

    @if($bookings->count() > 0)
        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="max-h-[500px] overflow-y-auto scrollbar-thin scrollbar-thumb-slate-200">
                <table class="w-full text-left border-collapse">
                    <thead class="sticky top-0 z-20 bg-slate-50/95 backdrop-blur-sm border-b border-slate-100">
                        <tr class="text-slate-400 text-[10px] uppercase tracking-widest font-bold">
                            <th class="px-6 py-4">Unit ID</th>
                            <th class="px-6 py-4">Market Location</th>
                            <th class="px-6 py-4">Total Fee</th>
                            <th class="px-6 py-4">Reservation Date</th>
                            <th class="px-6 py-4 text-center">Current Status</th>
                            <th class="px-6 py-4 text-center">Actions</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-50 text-sm">
                        @foreach($bookings as $booking)
                            <tr class="hover:bg-blue-50/30 transition-colors group">
                                
                                <td class="px-6 py-4">
                                    <span class="font-black text-slate-800 text-base">
                                        #{{ $booking->stall->stall_number ?? 'N/A' }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 text-slate-500 font-medium italic">
                                    {{ $booking->stall->location_desc ?? 'Main Market Area' }}
                                </td>

                                <td class="px-6 py-4">
                                    <span class="text-blue-600 font-bold tabular-nums">
                                        KES {{ number_format($booking->stall->price ?? 0, 0) }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 text-slate-400 font-medium tabular-nums">
                                    {{ $booking->booking_date ?? $booking->created_at->format('d M Y') }}
                                </td>

                                <td class="px-6 py-4 text-center">
                                    @php
                                        $statusClasses = [
                                            'confirmed' => 'bg-emerald-100 text-emerald-600 border-emerald-200',
                                            'cancelled' => 'bg-rose-100 text-rose-600 border-rose-200',
                                            'pending'   => 'bg-amber-100 text-amber-600 border-amber-200',
                                            'expired'   => 'bg-slate-200 text-slate-700 border-slate-300'
                                        ];

                                        $currentClass = $statusClasses[$booking->status] 
                                            ?? 'bg-slate-100 text-slate-600 border-slate-200';
                                    @endphp

                                    <span class="{{ $currentClass }} px-3 py-1 rounded-full text-[10px] font-black uppercase border">
                                        {{ $booking->status }}
                                    </span>
                                </td>

                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center gap-3">

                                        {{-- CANCEL ACTION --}}
                                        @if($booking->status !== 'cancelled' && $booking->status !== 'expired')
                                            <form method="POST"
                                                  action="{{ route('trader.bookings.cancel', $booking->id) }}"
                                                  onsubmit="return confirm('Cancel this booking?')">
                                                @csrf
                                                <button type="submit"
                                                    class="text-rose-500 hover:text-rose-700 font-bold text-[11px] uppercase tracking-tighter hover:underline transition-all">
                                                    Cancel
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-slate-300 text-xs italic">N/A</span>
                                        @endif

                                    </div>
                                </td>

                            </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>
        </div>
    @else

        <!-- Empty State -->
        <div class="bg-white rounded-3xl border-2 border-dashed border-slate-200 p-16 text-center shadow-inner">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-slate-50 rounded-full mb-4 text-3xl">
                🏜️
            </div>
            <p class="text-slate-800 font-bold text-lg leading-tight">
                No Bookings Yet
            </p>
            <p class="text-slate-400 text-sm mt-1">
                Your reservation history will appear here once you book a stall.
            </p>

            <div class="mt-8">
                <a href="{{ route('trader.stalls.index') }}"
                   class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-xl font-bold text-sm shadow-lg shadow-blue-600/20 transition-all hover:scale-105 active:scale-95 inline-block">
                    Browse Available Stalls
                </a>
            </div>
        </div>

    @endif

    <p class="text-center text-[10px] text-slate-400 font-bold uppercase tracking-widest pt-4">
        Official Trader Dashboard • 2026 Reservation System
    </p>

</div>
@endsection