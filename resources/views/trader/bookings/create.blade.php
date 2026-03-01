@extends('layouts.app')

@section('page-title', 'Reserve Your Stall')

@section('content')
<div class="max-w-4xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-8">

    <!-- Left: Stall Summary (Stick to top) -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-6 sticky top-24">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <span class="text-[10px] font-black text-blue-600 uppercase tracking-widest">Selected Unit</span>
                    <h2 class="text-3xl font-black text-slate-800">#{{ $stall->stall_number }}</h2>
                </div>
                <span class="bg-emerald-100 text-emerald-600 px-3 py-1 rounded-full text-[10px] font-bold uppercase">Available</span>
            </div>

            <div class="space-y-4 border-t border-slate-50 pt-6">
                <div class="flex justify-between text-sm">
                    <span class="text-slate-400 font-medium text-xs uppercase tracking-tighter">Market Zone</span>
                    <span class="text-slate-800 font-bold uppercase">{{ $stall->zone ?? 'Main' }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-slate-400 font-medium text-xs uppercase tracking-tighter">Location</span>
                    <span class="text-slate-800 font-bold">{{ $stall->location_desc ?? 'N/A' }}</span>
                </div>
            </div>

            <div class="mt-8 p-4 bg-blue-50 rounded-2xl border border-blue-100">
                <p class="text-xs text-blue-500 font-bold uppercase tracking-wider mb-1">Total Fee (24h)</p>
                <div class="flex items-baseline gap-1">
                    <span class="text-sm font-bold text-blue-600">KES</span>
                    <span class="text-3xl font-black text-blue-700">{{ number_format($stall->price, 0) }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Right: Booking Form -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-8">
            <h3 class="text-xl font-bold text-slate-800 mb-2">Booking Details</h3>
            <p class="text-slate-400 text-sm mb-8">Set your start time. Your reservation is valid for 24 hours.</p>

            <form method="POST" action="{{ route('trader.bookings.store') }}" class="space-y-6">
                @csrf
                <input type="hidden" name="stall_id" value="{{ $stall->id }}">
                <input type="hidden" name="booking_date" value="{{ now()->toDateString() }}">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Start Time -->
                    <div class="space-y-2">
                        <label class="text-[11px] font-bold text-slate-500 uppercase tracking-widest ml-1">Arrival Time</label>
                        <input type="datetime-local" name="start_time" id="start_time" required
                               min="{{ now()->format('Y-m-d\TH:i') }}"
                               class="w-full bg-slate-50 border-slate-200 rounded-xl px-4 py-3 text-slate-700 font-medium focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none">
                    </div>

                    <!-- End Time -->
                    <div class="space-y-2">
                        <label class="text-[11px] font-bold text-slate-500 uppercase tracking-widest ml-1">Auto Expiry (24h)</label>
                        <input type="datetime-local" name="end_time" id="end_time" readonly
                               class="w-full bg-slate-100 border-slate-200 rounded-xl px-4 py-3 text-slate-400 font-medium cursor-not-allowed">
                    </div>
                </div>

                <div class="pt-6">
                    <button type="submit"
                            class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white py-4 rounded-2xl font-bold text-lg shadow-xl shadow-blue-500/20 hover:scale-[1.02] active:scale-95 transition-all">
                        Complete Reservation
                    </button>
                    <p class="text-center text-[10px] text-slate-400 mt-4 uppercase tracking-tighter">
                        By clicking, you agree to the Muthurwa Market trading terms.
                    </p>
                </div>
            </form>
        </div>
    </div>

</div>

<script>
    document.getElementById('start_time').addEventListener('change', function () {
        let startValue = this.value;
        if (!startValue) return;
        let startTime = new Date(startValue);
        if (!isNaN(startTime.getTime())) {
            startTime.setHours(startTime.getHours() + 24);
            let formatted = startTime.getFullYear() + '-' + 
                String(startTime.getMonth() + 1).padStart(2, '0') + '-' + 
                String(startTime.getDate()).padStart(2, '0') + 'T' + 
                String(startTime.getHours()).padStart(2, '0') + ':' + 
                String(startTime.getMinutes()).padStart(2, '0');
            document.getElementById('end_time').value = formatted;
        }
    });
</script>
@endsection
