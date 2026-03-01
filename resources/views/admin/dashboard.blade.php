@extends('layouts.app')

@section('page-title', 'Admin Overview & Analytics')

@section('content')
<div class="space-y-6">
    
    <!-- 🖨️ Print Action Bar -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white/10 backdrop-blur-md p-6 rounded-3xl border border-white/20 shadow-xl bg-gradient-to-r from-blue-900 to-indigo-900 print:hidden">
        <div>
            <h2 class="text-2xl font-black text-white tracking-tight">Market Intelligence</h2>
            <p class="text-blue-200/70 text-xs uppercase font-bold mt-1 tracking-widest">Official Analytics Report</p>
        </div>

        <!-- Wrapper for buttons -->
        <div class="flex items-center gap-3">
            
            <!-- View All Booked Stalls Button -->
            <a href="{{ route('admin.stalls.booked') }}" 
               class="flex items-center gap-2 bg-indigo-600 text-white px-6 py-3 rounded-xl font-black text-xs uppercase tracking-widest shadow-lg hover:bg-indigo-700 transition-all">
                🏢 View All Booked Stalls
            </a>

            <!-- Print Button -->
            <button onclick="window.print()" class="flex items-center gap-2 bg-white text-blue-900 px-6 py-3 rounded-xl font-black text-xs uppercase tracking-widest shadow-lg hover:scale-105 active:scale-95 transition-all">
                <span>🖨️</span> Export as PDF / Print
            </button>

            <!-- ✅ New Manual Assignment Button -->
            <a href="{{ route('admin.stalls.assign.create') }}" 
               class="bg-indigo-600 text-white px-6 py-3 rounded-xl font-black text-xs uppercase shadow-lg hover:bg-indigo-700 transition-all">
                ➕ New Manual Assignment
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
        <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-100">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Stalls</p>
            <p class="text-xl font-black text-slate-800">{{ $totalStalls }}</p>
        </div>
        <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-100 text-emerald-600">
            <p class="text-[10px] font-bold uppercase tracking-widest opacity-60">Available</p>
            <p class="text-xl font-black">{{ $availableStalls }}</p>
        </div>
        <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-100 text-rose-600">
            <p class="text-[10px] font-bold uppercase tracking-widest opacity-60">Booked</p>
            <p class="text-xl font-black">{{ $bookedStalls }}</p>
        </div>
        <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-100 text-blue-600">
            <p class="text-[10px] font-bold uppercase tracking-widest opacity-60">Bookings</p>
            <p class="text-xl font-black">{{ $totalBookings }}</p>
        </div>
        <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-100 text-amber-600">
            <p class="text-[10px] font-bold uppercase tracking-widest opacity-60">Traders</p>
            <p class="text-xl font-black">{{ $totalTraders }}</p>
        </div>
    </div>

    <!-- Analytics Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 print:hidden">
        <div class="lg:col-span-2 bg-white p-6 rounded-3xl shadow-sm border border-slate-100">
            <h3 class="text-sm font-bold text-slate-800 uppercase mb-4 tracking-tight">30-Day Revenue Trend</h3>
            <div id="revenueChart" class="min-h-[300px]"></div>
        </div>
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100">
            <h3 class="text-sm font-bold text-slate-800 uppercase mb-4 tracking-tight">Revenue by Zone</h3>
            <div id="zoneChart" class="min-h-[300px]"></div>
        </div>
    </div>

    <!-- 👥 Trader Management & Account Control -->
    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden mt-8">
        <div class="px-6 py-4 border-b border-slate-50 flex justify-between items-center bg-white">
            <h3 class="text-sm font-bold text-slate-800 uppercase tracking-tight">Trader Account Control</h3>
            <span class="bg-blue-100 text-blue-700 text-[10px] font-black px-2 py-1 rounded-lg uppercase italic">
                Total: {{ $traders->count() }}
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50">
                    <tr class="text-slate-400 text-[10px] uppercase tracking-widest font-bold">
                        <th class="px-6 py-3">Trader Info</th>
                        <th class="px-6 py-3">Current Status</th>
                        <th class="px-6 py-3">Restriction</th>
                        <th class="px-6 py-3 text-right">Quick Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 text-sm">
                    @foreach($traders as $trader)
                        <tr class="hover:bg-slate-50/50 transition-all">
                            <td class="px-6 py-4">
                                <p class="font-bold text-slate-800">{{ $trader->name }}</p>
                                <p class="text-[10px] text-slate-500">{{ $trader->phone_number }}</p>
                            </td>

                            <td class="px-6 py-4">
                                <span class="px-2 py-1 rounded-md text-[10px] font-black uppercase 
                                    {{ $trader->status === 'active' 
                                        ? 'bg-emerald-100 text-emerald-700' 
                                        : 'bg-slate-100 text-slate-500' }}">
                                    {{ $trader->status }}
                                </span>
                            </td>

                            <td class="px-6 py-4">
                                @if($trader->account_restriction !== 'none')
                                    <div class="flex flex-col">
                                        <span class="text-[10px] font-black uppercase 
                                            {{ $trader->isBanned() 
                                                ? 'text-rose-600' 
                                                : ($trader->isBlocked() 
                                                    ? 'text-orange-600' 
                                                    : 'text-amber-600') }}">
                                            ⚠️ {{ $trader->account_restriction }}
                                        </span>
                                        <p class="text-[9px] text-slate-400 italic truncate max-w-[150px]">
                                            {{ $trader->restriction_reason }}
                                        </p>
                                    </div>
                                @else
                                    <span class="text-[10px] text-slate-300 italic uppercase">
                                        No Restrictions
                                    </span>
                                @endif
                            </td>

                            <td class="px-6 py-4 text-right">
                                <form action="{{ route('admin.traders.restrict', $trader->id) }}" 
                                      method="POST" 
                                      class="inline-flex gap-1">
                                    @csrf
                                    @method('PATCH')

                                    <input type="text" 
                                           name="reason" 
                                           placeholder="Reason..." 
                                           required 
                                           class="text-[10px] border-slate-200 rounded-lg px-2 py-1 focus:ring-blue-500 w-32">

                                    <button name="action" value="warned"
                                        class="p-1.5 bg-amber-100 text-amber-600 rounded-lg hover:bg-amber-200 transition-colors">📢</button>

                                    <button name="action" value="blocked"
                                        class="p-1.5 bg-orange-100 text-orange-600 rounded-lg hover:bg-orange-200 transition-colors">🚫</button>

                                    <button name="action" value="banned"
                                        class="p-1.5 bg-rose-100 text-rose-600 rounded-lg hover:bg-rose-200 transition-colors"
                                        onclick="return confirm('Ban this trader permanently?')">🔨</button>

                                    <button name="action" value="none"
                                        class="p-1.5 bg-slate-100 text-slate-600 rounded-lg hover:bg-slate-200 transition-colors">✅</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>

@push('scripts')
<script>
    (function initCharts() {
        if (typeof window.ApexCharts === 'undefined') {
            setTimeout(initCharts, 100);
            return;
        }

        new ApexCharts(document.querySelector("#revenueChart"), {
            series: [{ name: 'Revenue', data: {!! json_encode($revenueTrend->pluck('daily_total')) !!} }],
            chart: { height: 300, type: 'area', toolbar: { show: false }, fontFamily: 'Figtree' },
            colors: ['#2563eb'],
            stroke: { curve: 'smooth', width: 3 },
            xaxis: { categories: {!! json_encode($revenueTrend->pluck('date')) !!} },
            fill: { type: 'gradient', gradient: { opacityFrom: 0.4, opacityTo: 0 } }
        }).render();

        new ApexCharts(document.querySelector("#zoneChart"), {
            series: {!! json_encode($zoneStats->pluck('revenue')->map(fn($r) => (float)$r)) !!},
            chart: { height: 300, type: 'donut', fontFamily: 'Figtree' },
            labels: {!! json_encode($zoneStats->pluck('zone')) !!},
            colors: ['#2563eb', '#8b5cf6', '#f59e0b', '#10b981'],
            plotOptions: { pie: { donut: { size: '70%', labels: { show: true, total: { show: true, label: 'TOTAL' } } } } }
        }).render();
    })();
</script>
@endpush
@endsection