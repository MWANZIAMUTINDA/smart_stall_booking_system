@extends('layouts.app')

@section('page-title', 'Admin Overview & Analytics')

@section('content')
<div class="space-y-6">

    {{-- ── Nairobi County Colour Token Block ─────────────────────── --}}
    {{-- Yellow=#FCDD07 | Green=#068930 | White=#FFF | Blue=#0F47AF  --}}

    {{-- ── Action Bar (Blue — trust/authority) ──────────────────── --}}
    <div class="relative overflow-hidden rounded-2xl shadow-lg print:hidden"
         style="background:linear-gradient(135deg,#0a3282,#0F47AF,#1a5fd4);">

        {{-- County stripe accent --}}
        <div class="absolute top-0 left-0 right-0 h-1"
             style="background:linear-gradient(90deg,#0F47AF 0%,#0F47AF 33%,#fff 33%,#fff 66%,#068930 66%);"></div>

        <div class="p-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                {{-- Sun emblem --}}
                <div style="width:44px;height:44px;border-radius:12px;background:rgba(255,255,255,.15);
                            border:1px solid rgba(255,255,255,.25);display:flex;align-items:center;justify-content:center;">
                    <svg style="width:26px;height:26px;" fill="#FCDD07" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="4"/>
                        <path d="M12 2v2M12 20v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M2 12h2M20 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"
                              stroke="#FCDD07" stroke-width="2" stroke-linecap="round" fill="none"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl font-black text-white tracking-tight">Market Intelligence</h2>
                    <p style="color:rgba(255,255,255,.6);font-size:.68rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;">
                        Nairobi County · Official Analytics Report
                    </p>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('admin.stalls.booked') }}"
                   class="flex items-center gap-2 text-white px-4 py-2.5 rounded-xl font-bold text-xs uppercase tracking-wider transition-all hover:scale-105 active:scale-95"
                   style="background:#068930;box-shadow:0 4px 12px rgba(6,137,48,.4);">
                    🏢 All Booked Stalls
                </a>
                <a href="{{ route('admin.stalls.index') }}"
                   class="flex items-center gap-2 text-white px-4 py-2.5 rounded-xl font-bold text-xs uppercase tracking-wider transition-all hover:scale-105 active:scale-95"
                   style="background:rgba(255,255,255,.18);border:1px solid rgba(255,255,255,.3);">
                    🔧 Manage Stalls
                </a>
                <a href="{{ route('admin.feedback.index') }}"
                   class="flex items-center gap-2 text-white px-4 py-2.5 rounded-xl font-bold text-xs uppercase tracking-wider transition-all hover:scale-105 active:scale-95"
                   style="background:rgba(255,255,255,.15);border:1px solid rgba(255,255,255,.25);">
                    💬 Trader Feedback
                </a>
                <button onclick="window.print()"
                        class="flex items-center gap-2 px-4 py-2.5 rounded-xl font-bold text-xs uppercase tracking-wider transition-all hover:scale-105 active:scale-95"
                        style="background:#FCDD07;color:#1e293b;">
                    🖨️ Export / Print
                </button>
                <a href="{{ route('admin.stalls.assign.create') }}"
                   class="flex items-center gap-2 text-white px-4 py-2.5 rounded-xl font-bold text-xs uppercase tracking-wider transition-all hover:scale-105 active:scale-95"
                   style="background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.2);">
                    ➕ Manual Assignment
                </a>
            </div>
        </div>
    </div>

    {{-- ── KPI Stat Cards (Smart Availability Breakdown) ──────────── --}}
    <div class="grid grid-cols-2 md:grid-cols-7 gap-3 reveal">
        {{-- Total Stalls — Blue --}}
        <div class="bg-white rounded-2xl p-5 border-t-4 shadow-sm hover:shadow-md transition-shadow"
             style="border-top-color:#0F47AF;">
            <p class="text-[10px] font-black uppercase tracking-widest" style="color:#0F47AF;">Total Stalls</p>
            <p class="text-2xl font-black mt-1" style="color:#0a3282;">{{ $totalStalls }}</p>
        </div>

        {{-- Available (Bookable) — Green --}}
        <div class="bg-white rounded-2xl p-5 border-t-4 shadow-sm hover:shadow-md transition-shadow"
             style="border-top-color:#068930;">
            <p class="text-[10px] font-black uppercase tracking-widest" style="color:#068930;">Bookable</p>
            <p class="text-2xl font-black mt-1" style="color:#065f26;">{{ $availableStalls }}</p>
        </div>

        {{-- Occupied — Rose --}}
        <div class="bg-white rounded-2xl p-5 border-t-4 shadow-sm hover:shadow-md transition-shadow"
             style="border-top-color:#e11d48;">
            <p class="text-[10px] font-black uppercase tracking-widest text-rose-600">Occupied</p>
            <p class="text-2xl font-black mt-1 text-rose-700">{{ $occupiedStalls }}</p>
        </div>

        {{-- Booked Soon — Amber --}}
        <div class="bg-white rounded-2xl p-5 border-t-4 shadow-sm hover:shadow-md transition-shadow"
             style="border-top-color:#f59e0b;">
            <p class="text-[10px] font-black uppercase tracking-widest text-amber-600">Soon (≤24h)</p>
            <p class="text-2xl font-black mt-1 text-amber-700">{{ $bookedSoonStalls }}</p>
        </div>

        {{-- Blocked Stalls — Yellow --}}
        <div class="bg-white rounded-2xl p-5 border-t-4 shadow-sm hover:shadow-md transition-shadow"
             style="border-top-color:#FCDD07;">
            <p class="text-[10px] font-black uppercase tracking-widest text-amber-700">Blocked</p>
            <p class="text-2xl font-black mt-1 text-amber-800">{{ $blockedStalls }}</p>
        </div>

        {{-- Total Bookings — Blue --}}
        <div class="bg-white rounded-2xl p-5 border-t-4 shadow-sm hover:shadow-md transition-shadow"
             style="border-top-color:#0F47AF;">
            <p class="text-[10px] font-black uppercase tracking-widest" style="color:#0F47AF;">Bookings</p>
            <p class="text-2xl font-black mt-1" style="color:#0a3282;">{{ $totalBookings }}</p>
        </div>

        {{-- Traders — Green --}}
        <div class="bg-white rounded-2xl p-5 border-t-4 shadow-sm hover:shadow-md transition-shadow"
             style="border-top-color:#068930;">
            <p class="text-[10px] font-black uppercase tracking-widest" style="color:#068930;">Traders</p>
            <p class="text-2xl font-black mt-1" style="color:#065f26;">{{ $totalTraders }}</p>
        </div>
    </div>

    {{-- ── Pending Payments Action Panel ─────────────────────────── --}}
    @if($pendingBookings->count())
    <div class="rounded-2xl border overflow-hidden reveal"
         style="background:linear-gradient(135deg,#fffbeb,#fef9c3);border-color:#fcd34d;">

        {{-- Panel header --}}
        <div class="px-6 py-4 flex items-center justify-between border-b" style="border-color:#fcd34d;">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center text-xl"
                     style="background:#FCDD07;">
                    ⏳
                </div>
                <div>
                    <h3 class="text-sm font-black uppercase tracking-tight" style="color:#78350f;">
                        {{ $pendingBookings->count() }} Booking{{ $pendingBookings->count() > 1 ? 's' : '' }} Awaiting Payment
                    </h3>
                    <p style="color:#92400e;font-size:.65rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;">
                        Admin-assigned · Prompt the trader to complete M-Pesa payment
                    </p>
                </div>
            </div>
            <a href="{{ route('admin.stalls.booked') }}"
               class="text-xs font-black uppercase tracking-wider px-4 py-2 rounded-xl transition-all hover:scale-105"
               style="background:#0F47AF;color:#fff;">
                View All
            </a>
        </div>

        {{-- Pending booking rows --}}
        <div class="divide-y" style="border-color:rgba(252,211,7,.3);">
            @foreach($pendingBookings as $pb)
            <div class="px-6 py-4 flex flex-col md:flex-row md:items-center justify-between gap-3">
                <div class="flex items-center gap-4">
                    {{-- Stall badge --}}
                    <span class="px-3 py-1.5 rounded-xl font-black text-xs"
                          style="background:#eff6ff;color:#0F47AF;border:1px solid #bfdbfe;">
                        #{{ $pb->stall->stall_number }}
                    </span>
                    <div>
                        <p class="font-bold text-sm" style="color:#1e293b;">{{ $pb->user->name }}</p>
                        <p class="text-[10px] tabular-nums" style="color:#64748b;">{{ $pb->user->phone_number }}</p>
                    </div>
                    <div class="hidden md:block">
                        <p class="text-[10px] font-bold uppercase" style="color:#64748b;">
                            {{ $pb->start_time->format('d M Y, H:i') }} →
                            {{ $pb->end_time->format('d M Y, H:i') }}
                        </p>
                        <p class="text-[10px] font-bold mt-0.5" style="color:#0F47AF;">
                            KES {{ number_format($pb->stall->price, 0) }}
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-3 ml-auto">
                    @if($pb->bookedByAdmin)
                        <span class="text-[10px] font-bold" style="color:#92400e;">
                            Booked by: <strong>{{ $pb->bookedByAdmin->name }}</strong>
                        </span>
                    @endif

                    @if($pb->payment_prompt_sent_at)
                        <span class="text-[10px] font-bold px-2.5 py-1 rounded-lg"
                              style="background:#dcfce7;color:#166534;">
                            ✉️ Prompted {{ $pb->payment_prompt_sent_at->diffForHumans() }}
                        </span>
                    @endif

                    <form action="{{ route('admin.bookings.prompt', $pb->id) }}"
                          method="POST"
                          onsubmit="return confirm('Send M-Pesa prompt SMS to {{ addslashes($pb->user->name) }}?')">
                        @csrf
                        <button type="submit"
                                class="flex items-center gap-1.5 text-white px-4 py-2 rounded-xl font-black text-[10px] uppercase tracking-wider transition-all hover:scale-105 active:scale-95"
                                style="background:linear-gradient(135deg,#065f26,#068930);box-shadow:0 3px 10px rgba(6,137,48,.3);">
                            📱 {{ $pb->payment_prompt_sent_at ? 'Re-send Prompt' : 'Prompt Payment' }}
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- ── Charts (White cards) ───────────────────────────────────── --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 print:hidden">
        <div class="lg:col-span-2 bg-white p-6 rounded-2xl shadow-sm border border-gray-100 reveal">
            <div class="flex items-center gap-2 mb-4">
                <div class="w-3 h-3 rounded-full" style="background:#0F47AF;"></div>
                <h3 class="text-sm font-black uppercase tracking-tight" style="color:#0a3282;">30-Day Revenue Trend</h3>
            </div>
            <div id="revenueChart" class="min-h-[300px]"></div>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 reveal reveal-delay-1">
            <div class="flex items-center gap-2 mb-4">
                <div class="w-3 h-3 rounded-full" style="background:#068930;"></div>
                <h3 class="text-sm font-black uppercase tracking-tight" style="color:#0a3282;">Revenue by Zone</h3>
            </div>
            <div id="zoneChart" class="min-h-[300px]"></div>
        </div>
    </div>

    {{-- ── Active & Pending Bookings ────────────────────────────────── --}}
    @if($recentBookings->count())
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden reveal">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between"
             style="background:linear-gradient(90deg,#f0f7ff,#fff);">
            <div class="flex items-center gap-2">
                <div class="w-1 h-6 rounded-full" style="background:#0F47AF;"></div>
                <h3 class="text-sm font-black uppercase tracking-tight" style="color:#0a3282;">Active &amp; Pending Bookings</h3>
            </div>
            <a href="{{ route('admin.stalls.booked') }}"
               class="text-[10px] font-black uppercase tracking-wider px-3 py-1.5 rounded-lg transition-all hover:scale-105"
               style="background:#eff6ff;color:#0F47AF;border:1px solid #bfdbfe;">View All →</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead style="background:#f8fafc;">
                    <tr class="text-[10px] uppercase tracking-widest font-black" style="color:#94a3b8;">
                        <th class="px-5 py-3">Stall</th>
                        <th class="px-5 py-3">Trader</th>
                        <th class="px-5 py-3">Period</th>
                        <th class="px-5 py-3">Status</th>
                        <th class="px-5 py-3">Booked By</th>
                        <th class="px-5 py-3 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 text-sm">
                    @foreach($recentBookings->take(8) as $rb)
                    <tr class="hover:bg-blue-50/30 transition-colors
                               {{ $rb->status === 'pending' ? 'bg-amber-50/40' : '' }}">
                        <td class="px-5 py-3">
                            <span class="px-2.5 py-1 rounded-lg font-black text-xs"
                                  style="background:#eff6ff;color:#0F47AF;border:1px solid #bfdbfe;">
                                #{{ $rb->stall->stall_number }}
                            </span>
                        </td>
                        <td class="px-5 py-3">
                            <p class="font-bold text-slate-800 text-xs">{{ $rb->user->name }}</p>
                            <p class="text-[10px] text-slate-400">{{ $rb->user->phone_number }}</p>
                        </td>
                        <td class="px-5 py-3 tabular-nums">
                            <p class="text-[10px] font-bold text-slate-600">{{ $rb->start_time->format('d M, H:i') }}</p>
                            <p class="text-[9px] text-slate-400">→ {{ $rb->end_time->format('d M, H:i') }}</p>
                        </td>
                        <td class="px-5 py-3">
                            @if($rb->status === 'confirmed')
                                <span class="px-2.5 py-1 rounded-lg text-[10px] font-black uppercase"
                                      style="background:#dcfce7;color:#166534;border:1px solid #bbf7d0;">
                                    <span class="inline-block w-1.5 h-1.5 rounded-full bg-green-500 mr-1 animate-pulse"></span>Confirmed
                                </span>
                            @else
                                <span class="px-2.5 py-1 rounded-lg text-[10px] font-black uppercase"
                                      style="background:#fef3c7;color:#92400e;border:1px solid #fcd34d;">
                                    ⏳ Pending
                                </span>
                            @endif
                        </td>
                        <td class="px-5 py-3">
                            @if($rb->bookedByAdmin)
                                <p class="text-[10px] font-bold text-slate-700">{{ $rb->bookedByAdmin->name }}</p>
                                <span class="text-[9px] font-black px-1.5 py-0.5 rounded"
                                      style="background:#eff6ff;color:#0F47AF;">Admin</span>
                            @else
                                <span class="text-[10px] text-slate-400 italic">Self-booked</span>
                            @endif
                        </td>
                        <td class="px-5 py-3 text-right">
                            @if($rb->status === 'pending')
                                <form action="{{ route('admin.bookings.prompt', $rb->id) }}"
                                      method="POST"
                                      onsubmit="return confirm('Send SMS prompt to {{ addslashes($rb->user->name) }}?')">
                                    @csrf
                                    <button type="submit"
                                            class="text-white px-3 py-1.5 rounded-lg font-black text-[10px] uppercase tracking-wider transition-all hover:scale-105 active:scale-95"
                                            style="background:#068930;box-shadow:0 2px 8px rgba(6,137,48,.3);">
                                        📱 Prompt
                                    </button>
                                </form>
                            @else
                                <span class="text-[10px] font-black uppercase px-2 py-1 rounded-lg"
                                      style="background:#dcfce7;color:#166534;">✓ Paid</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="h-1" style="background:linear-gradient(90deg,#0F47AF 0%,#0F47AF 33%,#fff 33%,#fff 66%,#068930 66%);"></div>
    </div>
    @endif

    {{-- ── Trader Management Table ─────────────────────────────────── --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden reveal">

        {{-- Table header bar --}}
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center"
             style="background:linear-gradient(90deg,#f0f7ff,#fff);">
            <div class="flex items-center gap-2">
                <div class="w-1 h-6 rounded-full" style="background:#0F47AF;"></div>
                <h3 class="text-sm font-black uppercase tracking-tight" style="color:#0a3282;">Trader Account Control</h3>
            </div>
            <span class="text-[10px] font-black px-3 py-1 rounded-lg uppercase"
                  style="background:#eff6ff;color:#0F47AF;border:1px solid #bfdbfe;">
                Total: {{ $traders->count() }}
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead style="background:#f8fafc;">
                    <tr class="text-[10px] uppercase tracking-widest font-black" style="color:#94a3b8;">
                        <th class="px-6 py-3">Trader Info</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3">Restriction</th>
                        <th class="px-6 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 text-sm">
                    @foreach($traders as $trader)
                        <tr class="hover:bg-blue-50/30 transition-colors">
                            <td class="px-6 py-4">
                                <p class="font-bold text-slate-800">{{ $trader->name }}</p>
                                <p class="text-[10px] text-slate-400 mt-0.5">{{ $trader->phone_number }}</p>
                            </td>

                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 rounded-lg text-[10px] font-black uppercase
                                    {{ $trader->status === 'active'
                                        ? 'bg-green-100 text-green-800'
                                        : 'bg-slate-100 text-slate-500' }}">
                                    @if($trader->status === 'active')
                                        <span class="inline-block w-1.5 h-1.5 rounded-full bg-green-500 mr-1 animate-pulse"></span>
                                    @endif
                                    {{ $trader->status }}
                                </span>
                            </td>

                            <td class="px-6 py-4">
                                @if($trader->account_restriction !== 'none')
                                    <div class="flex flex-col">
                                        <span class="text-[10px] font-black uppercase
                                            {{ $trader->isBanned()
                                                ? 'text-red-600'
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
                                    <span class="text-[10px] font-black uppercase"
                                          style="color:#068930;">✓ Clear</span>
                                @endif
                            </td>

                            <td class="px-6 py-4 text-right">
                                <form action="{{ route('admin.traders.restrict', $trader->id) }}"
                                      method="POST" class="inline-flex gap-1 items-center">
                                    @csrf
                                    @method('PATCH')
                                    <input type="text" name="reason" placeholder="Reason..."
                                           required
                                           class="text-[10px] border border-gray-200 rounded-lg px-2 py-1 w-32 focus:outline-none focus:border-blue-400">
                                    <button name="action" value="warned"
                                            title="Warn"
                                            class="p-1.5 rounded-lg transition-colors"
                                            style="background:#fef9c3;color:#92400e;" >📢</button>
                                    <button name="action" value="blocked"
                                            title="Block"
                                            class="p-1.5 rounded-lg transition-colors"
                                            style="background:#ffedd5;color:#c2410c;">🚫</button>
                                    <button name="action" value="banned"
                                            title="Ban"
                                            class="p-1.5 rounded-lg transition-colors"
                                            style="background:#fee2e2;color:#b91c1c;"
                                            onclick="return confirm('Ban this trader permanently?')">🔨</button>
                                    <button name="action" value="none"
                                            title="Clear"
                                            class="p-1.5 rounded-lg transition-colors"
                                            style="background:#dcfce7;color:#166534;">✅</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- County footer stripe --}}
        <div class="h-1" style="background:linear-gradient(90deg,#0F47AF 0%,#0F47AF 33%,#fff 33%,#fff 66%,#068930 66%);"></div>
    </div>

    {{-- County attribution --}}
    <p class="text-center text-[10px] font-bold uppercase tracking-[.2em] pb-2"
       style="color:#94a3b8;">
        Nairobi City County &bull; Muthurwa Market System &bull; &copy; {{ date('Y') }}
    </p>

</div>

@push('scripts')
<script>
(function initCharts() {
    if (typeof window.ApexCharts === 'undefined') {
        setTimeout(initCharts, 100);
        return;
    }

    // 30-Day Revenue Trend — Nairobi Blue
    new ApexCharts(document.querySelector("#revenueChart"), {
        series: [{ name: 'Revenue (KES)', data: {!! json_encode($revenueTrend->pluck('daily_total')) !!} }],
        chart: { height: 300, type: 'area', toolbar: { show: false }, fontFamily: 'Inter, sans-serif' },
        colors: ['#0F47AF'],
        stroke: { curve: 'smooth', width: 3 },
        xaxis: { categories: {!! json_encode($revenueTrend->pluck('date')) !!},
                 labels: { style: { colors: '#94a3b8', fontSize: '10px' } } },
        fill: { type: 'gradient', gradient: { opacityFrom: 0.35, opacityTo: 0 } },
        grid: { borderColor: '#f1f5f9' },
        tooltip: { theme: 'light' }
    }).render();

    // Revenue by Zone — Nairobi palette
    new ApexCharts(document.querySelector("#zoneChart"), {
        series: {!! json_encode($zoneStats->pluck('revenue')->map(fn($r) => (float)$r)) !!},
        chart: { height: 300, type: 'donut', fontFamily: 'Inter, sans-serif' },
        labels: {!! json_encode($zoneStats->pluck('zone')) !!},
        colors: ['#0F47AF', '#068930', '#FCDD07', '#1a5fd4'],
        plotOptions: { pie: { donut: { size: '70%',
            labels: { show: true, total: { show: true, label: 'TOTAL', color: '#0a3282' } } } } },
        legend: { labels: { colors: '#64748b' } }
    }).render();
})();
</script>
@endpush
@endsection