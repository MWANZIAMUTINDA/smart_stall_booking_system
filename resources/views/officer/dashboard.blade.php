@extends('layouts.app')

@section('page-title', 'Officer Portal')

@section('content')
<div class="space-y-6">

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white/10 backdrop-blur-md p-6 rounded-3xl border border-white/20 shadow-xl bg-gradient-to-r from-blue-900 to-indigo-900">
        <div>
            <h2 class="text-2xl font-black text-white tracking-tight">Active Market Bookings</h2>
            <p class="text-blue-200/70 text-xs uppercase tracking-widest font-bold mt-1">Live Occupancy Oversight</p>
        </div>
        <div class="bg-white/10 px-4 py-2 rounded-xl border border-white/10">
            <span class="text-white font-bold text-lg tabular-nums">{{ count($bookings) }}</span>
            <span class="text-blue-300 text-[10px] uppercase font-black ml-1">Total Stalls</span>
        </div>
    </div>

    <!-- Main Table Card -->
    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
        
        <!-- Scrollable Container with Fixed Height -->
        <div class="max-h-[550px] overflow-y-auto">
            <table class="w-full text-left border-collapse">
                <thead class="sticky top-0 z-20 bg-slate-50/95 backdrop-blur-sm shadow-sm">
                    <tr class="text-slate-400 text-[10px] uppercase tracking-widest font-bold">
                        <th class="px-6 py-4">Stall ID</th>
                        <th class="px-6 py-4">Zone</th>
                        <th class="px-6 py-4">Trader Name</th>
                        <th class="px-6 py-4">Contact Details</th>
                        <th class="px-6 py-4 text-right">Verification Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 text-sm">
                    @forelse($bookings as $booking)
                    <tr class="hover:bg-blue-50/40 transition-colors group">
                        <td class="px-6 py-4">
                            <span class="bg-blue-100 text-blue-700 px-3 py-1.5 rounded-lg font-black text-xs">
                                #{{ $booking->stall->stall_number }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="bg-slate-100 text-slate-600 px-2 py-1 rounded-md text-[10px] font-bold uppercase border border-slate-200">
                                {{ $booking->stall->zone }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <p class="font-bold text-slate-800">{{ $booking->user->name }}</p>
                        </td>
                        <td class="px-6 py-4 tabular-nums">
                            <p class="text-slate-600 font-medium">{{ $booking->user->phone_number }}</p>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <p class="text-slate-400 font-medium text-xs">{{ $booking->created_at->format('d M Y') }}</p>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-20 text-center">
                            <div class="flex flex-col items-center">
                                <span class="text-4xl mb-4">📂</span>
                                <p class="text-slate-400 font-bold uppercase text-xs tracking-widest">No Active Bookings Found</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Footer Branding -->
    <p class="text-center text-[10px] text-slate-400 font-bold uppercase tracking-[0.2em] pt-4">
        Official Officer Oversight &bull; Muthurwa Market
    </p>

</div>
@endsection
