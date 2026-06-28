@extends('layouts.app')

@section('page-title', 'My Reservations')

@section('content')
<div class="space-y-6 pb-10" x-data="{ activeTab: 'active', showTicket: false, selectedTicket: null }">

    {{-- ── Compact Header ── --}}
    <div class="bg-gradient-to-br from-[#068930] via-[#057529] to-[#046122] text-white px-8 py-6 rounded-2xl shadow-xl border-b-4 border-[#FCDD07] relative overflow-hidden">
        <div class="absolute inset-x-0 bottom-0 opacity-10 pointer-events-none">
            <svg viewBox="0 0 800 100" fill="#FFF" class="w-full h-auto">
                <path d="M0 100V80H20V60H40V80H60V55H80V80H100V40H130V80H150V55H180V80H200V25H240V80H260V60H290V80H310V35H350V80H370V55H400V80H420V15H460V80H480V45H510V80H530V30H570V80H590V55H620V80H640V42H680V80H700V62H730V80H750V50H780V80H800V100H0Z"/>
            </svg>
        </div>
        <div class="relative z-10 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-5">
            <div class="flex items-center gap-4">
                <div class="bg-white/15 p-3 rounded-2xl backdrop-blur-xl border border-white/20 shadow-inner">
                    <span class="text-3xl">🦁</span>
                </div>
                <div>
                    <span class="text-[#FCDD07] text-[10px] uppercase tracking-[0.4em] font-black block">Muthurwa Digital Hub</span>
                    <h2 class="text-2xl font-black tracking-tight mt-0.5">Booking History</h2>
                    <div class="flex items-center gap-2 mt-1.5">
                        <span class="w-2 h-2 bg-[#FCDD07] rounded-full animate-pulse"></span>
                        <span class="text-[10px] font-black text-white uppercase tracking-widest">
                            {{ $bookings->where('status','confirmed')->where('end_time','>',now())->count() }} Active Reservations
                        </span>
                    </div>
                </div>
            </div>
            <div>
                <p class="text-emerald-100/70 text-xs font-medium text-right">
                    Habari, <span class="text-white font-black">{{ auth()->user()->name }}</span>! 👋
                </p>
                <p class="text-emerald-100/60 text-[10px] mt-1 text-right">
                    {{ $bookings->where('status','confirmed')->where('end_time','>',now())->count() }} active bookings at Muthurwa today.
                </p>
            </div>
        </div>
    </div>

    {{-- ── Underline Tabs ── --}}
    <div class="flex items-center border-b-2 border-[#E0E0E0] gap-0">
        <button @click="activeTab = 'active'"
                :class="activeTab === 'active' ? 'border-[#0F47AF] text-[#0F47AF]' : 'border-transparent text-slate-400 hover:text-slate-600'"
                class="px-6 py-3 text-sm font-black uppercase tracking-widest border-b-[3px] transition-all flex items-center gap-2 -mb-[2px]">
            Active Bookings
            <span :class="activeTab === 'active' ? 'bg-[#EFF4FF] text-[#0F47AF]' : 'bg-slate-100 text-slate-400'"
                  class="px-2 py-0.5 rounded-lg text-[10px] font-black">
                {{ $bookings->whereIn('status',['confirmed','pending'])->where('end_time','>',now())->count() }}
            </span>
        </button>
        <button @click="activeTab = 'history'"
                :class="activeTab === 'history' ? 'border-[#0F47AF] text-[#0F47AF]' : 'border-transparent text-slate-400 hover:text-slate-600'"
                class="px-6 py-3 text-sm font-black uppercase tracking-widest border-b-[3px] transition-all -mb-[2px]">
            Past History
        </button>
    </div>

    @if($bookings->count() > 0)

        {{-- ══ ACTIVE BOOKINGS ══ --}}
        <div x-show="activeTab === 'active'" class="space-y-4">
            @forelse($bookings->whereIn('status',['confirmed','pending'])->where('end_time','>',now()) as $booking)
                @php
                    $total   = $booking->start_time->diffInMinutes($booking->end_time);
                    $elapsed = $booking->start_time->diffInMinutes(now());
                    $pct     = $total > 0 ? min(100, max(0, ($elapsed / $total) * 100)) : 100;
                    $remain  = 100 - $pct;
                    $hoursLeft = now()->diffInHours($booking->end_time, false);
                    $progressColor = $hoursLeft >= 24 ? '#068930' : ($hoursLeft >= 2 ? '#D97706' : '#e11d48');
                @endphp

                <div class="bg-white rounded-2xl border border-[#E0E0E0] shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden">
                    {{-- Top colour strip = urgency colour --}}
                    <div class="h-1 w-full" style="background-color: {{ $progressColor }};"></div>

                    <div class="p-5 flex flex-col lg:flex-row items-start lg:items-center gap-5">

                        {{-- Col 1: Icon + Stall Info --}}
                        <div class="flex items-center gap-4 lg:w-56 shrink-0">
                            <div class="w-14 h-14 bg-[#F4F7F6] rounded-2xl flex items-center justify-center text-2xl border border-[#E0E0E0] shrink-0">🏪</div>
                            <div>
                                <h4 class="font-black text-xl text-[#333333] tracking-tight">#{{ $booking->stall->stall_number }}</h4>
                                <p class="text-[10px] font-bold text-[#0F47AF] uppercase tracking-widest mt-0.5">{{ $booking->stall->location_desc ?? 'Muthurwa Market' }}</p>
                                <p class="text-[10px] text-slate-400 mt-0.5">{{ $booking->start_time->format('jS M, Y') }}</p>
                            </div>
                        </div>

                        {{-- Col 2: Progress Bar + Timer --}}
                        <div class="flex-1 w-full min-w-0">
                            <div class="flex justify-between items-center mb-1.5">
                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Validity Progress</span>
                                <span class="text-[10px] font-bold text-slate-500">{{ $booking->start_time->format('H:i') }} → {{ $booking->end_time->format('H:i') }}</span>
                            </div>

                            @if($booking->status === 'confirmed')
                                {{-- Progress Bar --}}
                                <div class="h-3 w-full bg-slate-100 rounded-full overflow-hidden shadow-inner">
                                    <div class="h-full rounded-full transition-all duration-1000"
                                         style="width: {{ $remain }}%; background-color: {{ $progressColor }};
                                                box-shadow: 0 0 6px {{ $progressColor }}55;"></div>
                                </div>
                                <div class="flex items-center gap-3 mt-2">
                                    <div class="countdown-timer text-[11px] font-black uppercase tracking-widest px-3 py-1.5 rounded-lg flex items-center gap-1.5 border"
                                         data-end-time="{{ $booking->end_time->toIso8601String() }}">
                                        Calculating...
                                    </div>
                                    @if($hoursLeft < 24)
                                        <a href="{{ route('trader.bookings.renew', $booking->id) }}"
                                           class="text-[10px] font-black text-white px-4 py-1.5 rounded-lg shadow-sm transition-all hover:opacity-90"
                                           style="background-color: #068930;">
                                            🔄 Renew Now
                                        </a>
                                    @endif
                                </div>
                            @else
                                <div class="flex items-center gap-2 text-[11px] font-black text-amber-700 bg-amber-50 px-4 py-2 rounded-lg border border-amber-100 w-fit mt-2">
                                    <span class="animate-pulse">⚠️</span>
                                    ACTION REQUIRED · Expires {{ $booking->end_time->format('H:i') }}
                                </div>
                            @endif
                        </div>

                        {{-- Col 3: Price + Action Buttons --}}
                        <div class="flex items-center gap-4 lg:w-48 justify-between lg:justify-end shrink-0">
                            <div class="text-right">
                                <div class="flex items-center gap-1.5 justify-end">
                                    <div class="w-5 h-5 bg-[#49B249] rounded flex items-center justify-center text-[9px] font-black text-white italic">M</div>
                                    <span class="text-lg font-black text-[#333333]">KES {{ number_format($booking->stall->price, 0) }}</span>
                                </div>
                                <p class="text-[9px] font-bold text-[#49B249] uppercase tracking-tight">Verified M-PESA</p>
                            </div>

                            <div class="flex items-center gap-2">
                                @if($booking->payment_status === 'paid')
                                    <button
                                        @click="selectedTicket = {{ json_encode([
                                            'id'        => $booking->id,
                                            'trader'    => auth()->user()->name,
                                            'stall'     => $booking->stall->stall_number,
                                            'receipt'   => $booking->receipt_number,
                                            'booked_on' => $booking->created_at->format('d M Y, H:i'),
                                            'start'     => $booking->start_time->format('d M Y, H:i'),
                                            'end'       => $booking->end_time->format('d M Y, H:i'),
                                            'duration'  => ($booking->duration_days ?? 1) . ' day' . (($booking->duration_days ?? 1) > 1 ? 's' : ''),
                                            'total'     => 'KES ' . number_format($booking->amount ?? $booking->stall->price, 2),
                                            'qr'        => 'https://api.qrserver.com/v1/create-qr-code/?size=220x220&data=' . urlencode(
                                                'MUTHURWA MARKET' . "\n" .
                                                'Trader: '   . auth()->user()->name . "\n" .
                                                'Stall: '    . $booking->stall->stall_number . "\n" .
                                                'Receipt: '  . $booking->receipt_number . "\n" .
                                                'Booked On: '. $booking->created_at->format('d M Y H:i') . "\n" .
                                                'Start: '    . $booking->start_time->format('d M Y H:i') . "\n" .
                                                'End: '      . $booking->end_time->format('d M Y H:i') . "\n" .
                                                'Duration: ' . ($booking->duration_days ?? 1) . ' day(s)' . "\n" .
                                                'Total: KES '. number_format($booking->amount ?? $booking->stall->price, 2)
                                            ),
                                        ]) }}; showTicket = true"
                                        class="px-4 py-2.5 rounded-xl text-[11px] font-black uppercase tracking-widest text-white transition-all hover:opacity-90 shadow-sm"
                                        style="background-color: #068930;">
                                        View Ticket
                                    </button>
                                @elseif($booking->status === 'pending')
                                    <a href="{{ route('trader.bookings.pay', $booking->id) }}"
                                       class="px-4 py-2.5 rounded-xl text-[11px] font-black uppercase tracking-widest text-white transition-all hover:opacity-90 shadow-sm"
                                       style="background-color: #49B249;">
                                        Pay Now
                                    </a>
                                @endif

                                <form action="{{ route('trader.bookings.cancel', $booking->id) }}" method="POST"
                                      onsubmit="return confirm('Release this stall?')">
                                    @csrf
                                    <button type="submit" class="p-2.5 text-slate-300 hover:text-rose-500 hover:bg-rose-50 rounded-xl transition-all">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="py-20 text-center bg-white rounded-2xl border-2 border-dashed border-[#E0E0E0]">
                    <span class="text-6xl mb-5 block opacity-20">🌍</span>
                    <h4 class="font-black text-[#333333] text-xl">No Active Bookings</h4>
                    <p class="text-slate-400 font-medium mt-2 max-w-xs mx-auto text-sm">Explore Muthurwa Market and secure your business spot today.</p>
                    <a href="{{ route('trader.stalls.index') }}"
                       class="mt-6 inline-flex items-center gap-2 text-white px-8 py-3 rounded-xl font-black text-sm shadow-lg transition-all hover:scale-105"
                       style="background-color: #068930;">
                        Browse Market Map
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                </div>
            @endforelse
        </div>

        {{-- ══ HISTORY ══ --}}
        <div x-show="activeTab === 'history'" class="space-y-3">
            @forelse($bookings->filter(fn($b) => !($b->status === 'confirmed' && $b->end_time->isFuture()) && !($b->status === 'pending' && $b->end_time->isFuture())) as $booking)
                <div class="bg-white p-4 rounded-xl border border-[#E0E0E0] flex flex-col md:flex-row items-start md:items-center gap-4 hover:shadow-md hover:border-slate-300 transition-all opacity-75 hover:opacity-100">
                    <div class="flex items-center gap-3 md:w-48 shrink-0">
                        <span class="w-11 h-11 bg-[#F4F7F6] rounded-xl flex items-center justify-center text-xl border border-[#E0E0E0]">📋</span>
                        <div>
                            <h5 class="font-black text-base text-[#333333]">#{{ $booking->stall->stall_number }}</h5>
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Closed</p>
                        </div>
                    </div>
                    <div class="flex-1 text-xs font-medium text-slate-500">
                        {{ $booking->start_time->format('jS M, Y') }} &bull;
                        {{ $booking->start_time->format('H:i') }}–{{ $booking->end_time->format('H:i') }}
                    </div>
                    <div class="flex items-center gap-3">
                        @if($booking->status === 'expired')
                            <a href="{{ route('trader.bookings.create', $booking->stall_id) }}"
                               class="text-[10px] font-black uppercase tracking-widest px-4 py-2 rounded-lg border-2 border-[#068930] text-[#068930] hover:bg-[#068930] hover:text-white transition-all">
                                Re-book
                            </a>
                        @endif
                        @if($booking->payment_status === 'paid')
                            <a href="{{ route('trader.bookings.receipt', $booking->id) }}"
                               class="p-2 text-slate-400 hover:text-[#0F47AF] rounded-lg hover:bg-slate-50 transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            </a>
                        @endif
                    </div>
                </div>
            @empty
                <div class="py-10 text-center text-slate-300 font-black uppercase tracking-widest text-xs">No previous transaction history</div>
            @endforelse
        </div>

    @else
        <div class="bg-white rounded-2xl border-2 border-dashed border-[#E0E0E0] p-20 text-center">
            <div class="w-24 h-24 bg-[#F0FAF3] rounded-3xl flex items-center justify-center text-5xl mx-auto mb-6 border border-emerald-100">🏢</div>
            <h3 class="text-2xl font-black text-[#333333] tracking-tight">Karibu Muthurwa!</h3>
            <p class="text-slate-500 font-medium mt-3 max-w-xs mx-auto text-sm leading-relaxed">Start your business journey in the heart of Nairobi. Reserve your spot today.</p>
            <a href="{{ route('trader.stalls.index') }}"
               class="mt-8 inline-flex items-center gap-3 text-white px-10 py-4 rounded-2xl font-black shadow-xl transition-all hover:scale-105"
               style="background-color: #068930;">
                Start Booking Now
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>
    @endif

    {{-- ── Market Notice Marquee ── --}}
    <div class="overflow-hidden bg-[#1A1A1B] text-white py-3 rounded-2xl relative border-l-8 border-[#FCDD07]">
        <div class="absolute left-0 top-0 bottom-0 px-5 bg-[#068930] z-10 flex items-center font-black text-[10px] uppercase tracking-widest border-r border-[#FCDD07]/20">
            NOTICE 🏛️
        </div>
        <div class="animate-marquee whitespace-nowrap flex items-center gap-12 pl-28">
            <span class="text-xs font-bold text-slate-200 uppercase tracking-wide italic underline decoration-[#FCDD07]">M-Pesa services will be under maintenance this Sunday 12 AM–4 AM. Please book in advance.</span>
            <span class="text-xs font-bold text-slate-200 uppercase tracking-wide">Market cleaning in Section 2 on Monday. All stalls cleared by 6 AM.</span>
            <span class="text-xs font-bold text-[#FCDD07] uppercase tracking-wide">Nairobi City County: Providing a path to prosperity for every trader.</span>
        </div>
    </div>

    {{-- ── Footer ── --}}
    <footer class="flex flex-col items-center gap-3 pt-8 border-t border-[#E0E0E0]">
        <span class="text-[10px] text-slate-400 font-black uppercase tracking-widest">Nairobi City County · Digital Services</span>
        <p class="text-[9px] text-slate-300 font-medium uppercase tracking-widest text-center max-w-sm">
            Built for traders, by the people of Nairobi. Ensuring fair and transparent stall management since 2026.
        </p>
    </footer>

    {{-- ── Digital Ticket Modal ── --}}
    <template x-if="showTicket">
        <div class="fixed inset-0 z-[100] flex items-center justify-center p-4">
            <div @click="showTicket = false" class="absolute inset-0 bg-slate-950/90 backdrop-blur-xl"></div>
            <div id="ticket-card" class="relative w-full max-w-md bg-white rounded-3xl shadow-2xl overflow-hidden border border-white/20">

                {{-- ── Ticket Top (Green Header) ── --}}
                <div class="bg-gradient-to-br from-[#068930] to-[#046122] px-7 pt-7 pb-10 text-white relative">
                    <div class="flex justify-between items-start">
                        <div>
                            <span class="text-[10px] font-black uppercase tracking-widest text-[#FCDD07] block mb-1">NAIROBI CITY COUNTY · OFFICIAL TICKET</span>
                            <h3 class="text-3xl font-black leading-tight">STALL #<span x-text="selectedTicket.stall"></span></h3>
                            <p class="text-emerald-100/80 text-sm font-bold mt-1" x-text="selectedTicket.trader"></p>
                        </div>
                        <div class="bg-white/20 p-3 rounded-2xl text-2xl border border-white/10">🦁</div>
                    </div>
                    {{-- Notch circles --}}
                    <div class="absolute left-0 bottom-0 translate-y-1/2 -translate-x-1/2 w-8 h-8 bg-slate-950 rounded-full"></div>
                    <div class="absolute right-0 bottom-0 translate-y-1/2 translate-x-1/2 w-8 h-8 bg-slate-950 rounded-full"></div>
                </div>

                {{-- ── Ticket Body ── --}}
                <div class="px-7 pt-6 pb-5">

                    {{-- 8-field detail grid --}}
                    <div class="grid grid-cols-2 gap-x-6 gap-y-4 mb-6 pb-6 border-b border-dashed border-[#E0E0E0]">

                        {{-- 1. Trader Name --}}
                        <div>
                            <span class="text-[9px] font-black uppercase text-slate-400 tracking-widest block mb-0.5">👤 Trader Name</span>
                            <span class="font-black text-sm text-[#333]" x-text="selectedTicket.trader"></span>
                        </div>

                        {{-- 2. Stall Number --}}
                        <div>
                            <span class="text-[9px] font-black uppercase text-slate-400 tracking-widest block mb-0.5">🏪 Stall Number</span>
                            <span class="font-black text-sm text-[#333]" x-text="selectedTicket.stall"></span>
                        </div>

                        {{-- 3. Receipt Number --}}
                        <div class="col-span-2">
                            <span class="text-[9px] font-black uppercase text-slate-400 tracking-widest block mb-0.5">🧾 Receipt Number</span>
                            <span class="font-black text-sm text-[#0F47AF] font-mono" x-text="selectedTicket.receipt"></span>
                        </div>

                        {{-- 4. Time Booked --}}
                        <div>
                            <span class="text-[9px] font-black uppercase text-slate-400 tracking-widest block mb-0.5">🕐 Time Booked</span>
                            <span class="font-bold text-sm text-[#333]" x-text="selectedTicket.booked_on"></span>
                        </div>

                        {{-- 7. Stay Duration --}}
                        <div>
                            <span class="text-[9px] font-black uppercase text-slate-400 tracking-widest block mb-0.5">📅 Stay Duration</span>
                            <span class="font-black text-sm text-[#068930]" x-text="selectedTicket.duration"></span>
                        </div>

                        {{-- 5. Start Time --}}
                        <div>
                            <span class="text-[9px] font-black uppercase text-slate-400 tracking-widest block mb-0.5">🟢 Start Time</span>
                            <span class="font-black text-sm text-[#068930]" x-text="selectedTicket.start"></span>
                        </div>

                        {{-- 6. End Time --}}
                        <div>
                            <span class="text-[9px] font-black uppercase text-slate-400 tracking-widest block mb-0.5">🔴 End Time</span>
                            <span class="font-black text-sm text-rose-600" x-text="selectedTicket.end"></span>
                        </div>

                        {{-- 8. Total Price --}}
                        <div class="col-span-2 bg-[#F0FAF3] rounded-xl px-4 py-3 border border-emerald-100">
                            <span class="text-[9px] font-black uppercase text-slate-400 tracking-widest block mb-0.5">💰 Total Price</span>
                            <div class="flex items-center gap-2">
                                <span class="text-2xl font-black text-[#068930]" x-text="selectedTicket.total"></span>
                                <span class="w-2 h-2 bg-[#49B249] rounded-full animate-pulse"></span>
                                <span class="text-[9px] font-black text-[#49B249] uppercase tracking-widest">M-PESA Verified</span>
                            </div>
                        </div>

                    </div>

                    {{-- QR Code --}}
                    <div class="flex flex-col items-center gap-2 mb-5">
                        <div class="bg-[#F8F9FA] p-4 rounded-2xl border border-[#E0E0E0]">
                            <img :src="selectedTicket.qr" class="w-40 h-40 rounded-lg" alt="QR Code">
                        </div>
                        <span class="text-[9px] text-slate-400 font-black uppercase tracking-widest">Scan to verify this booking</span>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="flex justify-between items-center pt-4 border-t border-[#E0E0E0]">
                        <span class="text-[9px] text-slate-300 font-black uppercase tracking-widest">Muthurwa Digital Hub 🌍</span>
                        <div class="flex gap-2">

                            {{-- 📥 Download Ticket as Image --}}
                            <button onclick="downloadTicket()"
                                    id="download-ticket-btn"
                                    class="flex items-center gap-1.5 text-white px-4 py-2.5 rounded-xl text-[11px] font-black uppercase tracking-widest shadow-lg transition-all hover:scale-105"
                                    style="background-color: #0F47AF;"
                                    title="Download Ticket as Image">
                                <svg id="btn-icon" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-5-4l-3 3m0 0l-3-3m3 3V4"/></svg>
                                <span id="btn-label">Save Ticket</span>
                            </button>

                            {{-- 📄 Download PDF Receipt --}}
                            <a :href="'/trader/bookings/' + selectedTicket.id + '/receipt'"
                               class="text-white p-2.5 rounded-xl shadow-lg transition-all hover:scale-110"
                               style="background-color: #068930;"
                               title="Download PDF Receipt">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            </a>

                            {{-- ✖ Close --}}
                            <button @click="showTicket = false"
                                    class="text-slate-400 p-2.5 rounded-xl hover:bg-slate-50 transition-all"
                                    title="Close">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </template>

</div>

<style>
@keyframes marquee {
    0%   { transform: translateX(0); }
    100% { transform: translateX(-50%); }
}
.animate-marquee {
    animation: marquee 35s linear infinite;
    display: inline-flex;
    min-width: 200%;
}
.animate-marquee:hover { animation-play-state: paused; }
</style>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

<script>
// ── Countdown Timers ─────────────────────────────────────────────────────
function updateTimers() {
    document.querySelectorAll('.countdown-timer').forEach(el => {
        const end  = new Date(el.dataset.endTime).getTime();
        const now  = Date.now();
        const diff = end - now;

        if (diff <= 0) {
            el.textContent = 'EXPIRED';
            el.className = 'countdown-timer text-[11px] font-black uppercase tracking-widest px-3 py-1.5 rounded-lg flex items-center gap-1.5 border text-slate-400 bg-slate-100 border-slate-200';
            return;
        }

        const h = Math.floor(diff / 3600000);
        const m = Math.floor((diff % 3600000) / 60000);
        const s = Math.floor((diff % 60000) / 1000);

        let colorClass;
        if      (h >= 24) colorClass = 'text-[#068930] bg-[#F0FAF3] border-emerald-200';
        else if (h >= 2)  colorClass = 'text-amber-700 bg-amber-50 border-amber-200';
        else              colorClass = 'text-rose-600 bg-rose-50 border-rose-200';

        el.className = `countdown-timer text-[11px] font-black uppercase tracking-widest px-3 py-1.5 rounded-lg flex items-center gap-1.5 border ${colorClass}`;

        const dotColor = h >= 24 ? '#068930' : (h >= 2 ? '#D97706' : '#e11d48');
        const label    = h > 0
            ? `${h}h ${m}m left`
            : `${m}m ${s}s left`;

        el.innerHTML = `<span class="w-1.5 h-1.5 rounded-full animate-pulse" style="background-color:${dotColor};"></span> ${label}`;
    });
}

updateTimers();
setInterval(updateTimers, 1000);

// ── Download Ticket as PNG Image ─────────────────────────────────────────
function downloadTicket() {
    const card = document.getElementById('ticket-card');
    const btn  = document.getElementById('download-ticket-btn');
    const icon = document.getElementById('btn-icon');
    const label = document.getElementById('btn-label');

    if (!card) return;

    // Show loading state
    btn.disabled = true;
    label.textContent = 'Saving...';
    icon.innerHTML = `<circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2.5" fill="none" stroke-dasharray="31.4" stroke-dashoffset="10" style="animation:spin 1s linear infinite;transform-origin:center"/>`;

    // Hide the action button row so it doesn't appear in the screenshot
    const actionBar = card.querySelector('#ticket-action-bar');
    if (actionBar) actionBar.style.display = 'none';

    // Wait for QR image to fully load, then capture
    const qrImg = card.querySelector('img[alt="QR Code"]');
    const doCapture = () => {
        html2canvas(card, {
            scale: 3,
            useCORS: true,
            allowTaint: true,
            backgroundColor: '#ffffff',
            logging: false,
        }).then(canvas => {
            // Restore action bar
            if (actionBar) actionBar.style.display = '';

            // Get filename from receipt number text
            const receiptEl = card.querySelector('[x-text="selectedTicket.receipt"]');
            const filename  = receiptEl ? ('muthurwa-' + receiptEl.textContent.trim() + '.png') : 'muthurwa-ticket.png';

            const link = document.createElement('a');
            link.download = filename;
            link.href = canvas.toDataURL('image/png');
            link.click();

            // Restore button
            btn.disabled = false;
            label.textContent = 'Save Ticket';
            icon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-5-4l-3 3m0 0l-3-3m3 3V4"/>`;
        }).catch(() => {
            if (actionBar) actionBar.style.display = '';
            btn.disabled = false;
            label.textContent = 'Save Ticket';
        });
    };

    if (qrImg && !qrImg.complete) {
        qrImg.onload = doCapture;
    } else {
        setTimeout(doCapture, 200); // small delay to let Alpine finish rendering
    }
}
</script>
@endsection