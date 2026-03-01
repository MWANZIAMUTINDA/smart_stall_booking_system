@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
        <h2 class="text-xl font-black text-slate-800 uppercase tracking-tight">Current Occupancy Report</h2>
        <a href="{{ route('admin.dashboard') }}" class="text-xs font-bold text-blue-600 uppercase">Back to Analytics</a>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
        <table class="w-full text-left">
            <thead class="bg-slate-50 text-[10px] uppercase font-bold text-slate-400">
                <tr>
                    <th class="px-6 py-4">Stall #</th>
                    <th class="px-6 py-4">Trader Name</th>
                    <th class="px-6 py-4">Ends In</th>
                    <th class="px-6 py-4 text-right">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50 text-sm">
                @foreach($bookedStalls as $booking)
                    <tr class="hover:bg-slate-50/50">
                        <td class="px-6 py-4 font-black text-blue-600">#{{ $booking->stall->stall_number }}</td>
                        <td class="px-6 py-4">
                            <p class="font-bold text-slate-800">{{ $booking->user->name }}</p>
                            <p class="text-[10px] text-slate-500">{{ $booking->user->phone_number }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 bg-amber-50 text-amber-600 rounded text-[10px] font-bold uppercase">
                                {{ $booking->end_time->diffForHumans() }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('admin.traders.history', $booking->user->id) }}" 
                               class="inline-block px-4 py-2 bg-slate-100 text-slate-600 text-[10px] font-black uppercase rounded-xl hover:bg-blue-600 hover:text-white transition-all">
                                View Trader History
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
