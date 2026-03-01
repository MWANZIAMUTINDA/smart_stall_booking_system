@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Trader Profile Header -->
    <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm flex flex-col md:flex-row justify-between gap-6">
        <div>
            <span class="text-[10px] font-black text-blue-500 uppercase tracking-widest">Trader Profile</span>
            <h1 class="text-3xl font-black text-slate-900">{{ $user->name }}</h1>
            <p class="text-slate-500 text-sm">{{ $user->email }} | {{ $user->phone_number }}</p>
        </div>
        <div class="flex items-center gap-2">
            <span class="px-4 py-2 rounded-2xl text-xs font-black uppercase {{ $user->account_restriction === 'none' ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">
                Status: {{ $user->account_restriction === 'none' ? 'Clear' : $user->account_restriction }}
            </span>
        </div>
    </div>

    <!-- Booking History Table -->
    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-50">
            <h3 class="text-sm font-bold text-slate-800 uppercase">Past & Present Bookings</h3>
        </div>
        <table class="w-full text-left">
            <thead class="bg-slate-50 text-[10px] font-black text-slate-400 uppercase">
                <tr>
                    <th class="px-6 py-4">Stall</th>
                    <th class="px-6 py-4">Date Range</th>
                    <th class="px-6 py-4 text-center">Amount Paid</th>
                    <th class="px-6 py-4 text-right">Final Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50 text-sm">
                @foreach($history as $booking)
                    <tr>
                        <td class="px-6 py-4 font-bold">#{{ $booking->stall->stall_number }}</td>
                        <td class="px-6 py-4 text-slate-500 text-xs">
                            {{ $booking->start_time->format('d M Y') }} - {{ $booking->end_time->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4 text-center font-bold text-slate-700">
                            KES {{ number_format($booking->stall->price) }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            <span class="text-[9px] font-black uppercase px-2 py-1 rounded {{ $booking->status === 'completed' ? 'bg-emerald-100 text-emerald-600' : 'bg-slate-100 text-slate-500' }}">
                                {{ $booking->status }}
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
