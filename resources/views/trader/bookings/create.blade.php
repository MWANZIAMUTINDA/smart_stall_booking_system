@extends('layouts.app')

@section('page-title', 'Complete Reservation')

@section('content')
<div class="max-w-5xl mx-auto space-y-6 pb-12">

    {{-- Progress & FOMO Timer --}}
    <div class="flex flex-col md:flex-row justify-between items-center gap-4 bg-white p-4 rounded-2xl shadow-sm border border-slate-100">
        <div class="flex items-center gap-2">
            <div class="w-6 h-6 rounded-full bg-emerald-500 text-white flex items-center justify-center text-[10px] font-black">✓</div>
            <span class="text-[10px] font-black uppercase tracking-widest text-slate-400 hidden sm:inline">Select Stall</span>
            <div class="w-8 h-0.5 bg-emerald-500 mx-1"></div>
            <div class="w-6 h-6 rounded-full flex items-center justify-center text-[10px] font-black" style="background:#1E5128;color:white;">2</div>
            <span class="text-[10px] font-black uppercase tracking-widest" style="color:#1E5128;">Review & Pay</span>
            <div class="w-8 h-0.5 bg-slate-200 mx-1"></div>
            <div class="flex items-center gap-2 opacity-50">
                <div class="w-6 h-6 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center text-[10px] font-black border border-slate-200">3</div>
                <span class="text-[10px] font-black uppercase tracking-widest text-slate-400 hidden sm:inline">Success</span>
            </div>
        </div>
        <div class="flex items-center gap-3 bg-amber-50 px-4 py-2 rounded-xl border border-amber-200 w-full md:w-auto justify-center">
            <span class="text-lg animate-pulse">⏳</span>
            <p class="text-[11px] font-bold text-amber-800">Stall held for <span id="fomoTimer" class="font-black text-amber-900 tabular-nums">04:59</span>. Complete to secure it.</p>
        </div>
    </div>

    @if(session('error'))
        <div class="bg-rose-50 border border-rose-200 text-rose-700 px-6 py-4 rounded-2xl shadow-sm font-bold flex items-center gap-3">
            <svg class="w-5 h-5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">

        {{-- LEFT: Booking Form --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-8 relative overflow-hidden">
                <div class="absolute top-0 left-0 w-1 h-full" style="background:#49B249;"></div>

                <div class="mb-6">
                    <h3 class="text-xl font-black text-slate-800 tracking-tight">Booking Details</h3>
                    <p class="text-slate-500 text-sm font-medium mt-1">Choose your arrival time and how long you need the stall.</p>
                </div>

                <form method="POST" action="{{ route('trader.bookings.store') }}" class="space-y-6" id="bookingForm">
                    @csrf
                    <input type="hidden" name="stall_id" value="{{ $stall->id }}">
                    <input type="hidden" name="booking_date" value="{{ now()->toDateString() }}">
                    {{-- These are populated by JS --}}
                    <input type="hidden" name="duration_days" id="hiddenDuration">
                    <input type="hidden" name="amount" id="hiddenAmount">

                    {{-- Arrival Time --}}
                    <div class="space-y-2">
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Arrival Date & Time</label>
                        <input type="datetime-local" name="start_time" id="start_time" required
                               min="{{ now()->format('Y-m-d\TH:i') }}"
                               class="w-full border-2 border-slate-200 rounded-xl px-4 py-3.5 text-slate-700 font-bold focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all bg-slate-50 focus:bg-white">
                    </div>

                    {{-- Duration Preset --}}
                    <div class="space-y-2">
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Booking Duration</label>
                        <select id="durationPreset"
                                class="w-full border-2 border-slate-200 rounded-xl px-4 py-3.5 text-slate-700 font-bold focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all bg-slate-50 focus:bg-white appearance-none cursor-pointer">
                            <option value="">— Select a duration —</option>
                            <option value="1"  data-price="1"  data-label="1 Day">1 Day — KES 1</option>
                            <option value="7"  data-price="6"  data-label="1 Week">1 Week (7 days) — KES 6 <span>save 1 day!</span></option>
                            <option value="14" data-price="12" data-label="2 Weeks">2 Weeks (14 days) — KES 12 <span>save 2 days!</span></option>
                            <option value="21" data-price="18" data-label="3 Weeks">3 Weeks (21 days) — KES 18 <span>save 3 days!</span></option>
                            <option value="30" data-price="23" data-label="1 Month">1 Month (30 days) — KES 23 🔥 Best Value!</option>
                            <option value="custom">✏️ Custom (enter days below)</option>
                        </select>
                    </div>

                    {{-- Custom days input (hidden by default) --}}
                    <div id="customDaysWrapper" class="hidden space-y-2">
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Number of Days</label>
                        <div class="relative">
                            <input type="number" id="customDays" min="1" max="365" placeholder="e.g. 10"
                                   class="w-full border-2 border-slate-200 rounded-xl px-4 py-3.5 text-slate-700 font-bold focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all bg-slate-50 focus:bg-white">
                            <span class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm font-bold">days</span>
                        </div>
                        <p class="text-[10px] text-slate-400 ml-1">Price: KES 1/day · 1 free day every 7 days booked</p>
                    </div>

                    {{-- Auto-calculated End Time (readonly display) --}}
                    <div class="space-y-2">
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Booking Ends (auto-calculated)</label>
                        <input type="text" id="endTimeDisplay" readonly placeholder="Select arrival time & duration above"
                               class="w-full border-2 border-slate-100 rounded-xl px-4 py-3.5 text-slate-400 font-bold cursor-not-allowed bg-slate-100">
                    </div>

                    {{-- M-Pesa --}}
                    <div class="space-y-2 pt-2">
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">M-Pesa Phone Number</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <span class="text-slate-400 font-black">+254</span>
                            </div>
                            <input type="tel" name="mpesa_number" id="mpesa_number" required placeholder="712345678"
                                   value="{{ substr(auth()->user()->phone_number, -9) }}"
                                   class="w-full pl-14 pr-4 py-3.5 border-2 border-slate-200 rounded-xl text-slate-800 font-black tracking-wider focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all bg-slate-50 focus:bg-white">
                        </div>
                        <p class="text-[10px] text-slate-400 font-medium ml-1 italic">We will send an STK push prompt to this number.</p>
                    </div>

                    {{-- Pricing breakdown panel --}}
                    <div id="pricingPanel" class="hidden p-5 rounded-2xl border-2 border-emerald-200 bg-emerald-50">
                        <p class="text-[10px] font-black text-emerald-700 uppercase tracking-widest mb-3">💰 Price Breakdown</p>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-slate-600 font-medium">Duration</span>
                                <span id="breakdownDays" class="font-black text-slate-800">—</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-600 font-medium">Base rate</span>
                                <span class="font-bold text-slate-600">KES 1 / day</span>
                            </div>
                            <div id="discountRow" class="flex justify-between hidden">
                                <span class="text-emerald-600 font-bold">🎁 Free days (discount)</span>
                                <span id="breakdownFree" class="font-black text-emerald-600">—</span>
                            </div>
                            <div class="flex justify-between pt-2 border-t border-emerald-200 mt-2">
                                <span class="font-black text-slate-800 text-base">Total</span>
                                <span id="breakdownTotal" class="font-black text-emerald-700 text-xl">KES —</span>
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-100">
                        <button type="submit" id="payBtn" disabled
                                class="w-full flex items-center justify-center gap-3 py-4 text-white text-sm font-black uppercase tracking-widest rounded-xl transition-all opacity-50 cursor-not-allowed"
                                style="background-color:#49B249; box-shadow:0 10px 25px rgba(73,178,73,0.35);">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                            <span id="payBtnLabel">Select duration to continue</span>
                        </button>
                    </div>
                </form>
            </div>

            <p class="text-center text-[10px] font-black text-slate-400 uppercase tracking-widest mt-2">
                Haya, let's get you started at Muthurwa! 🇰🇪
            </p>
        </div>

        {{-- RIGHT: Sticky Summary --}}
        <div class="lg:col-span-1 space-y-6">

            <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-6 sticky top-24">

                <div class="text-center border-b border-slate-100 pb-5 mb-5">
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1">Selected Unit</span>
                    <h2 class="text-4xl font-black text-slate-800">#{{ $stall->stall_number }}</h2>
                    <span class="inline-block mt-2 bg-emerald-50 border border-emerald-200 text-emerald-600 px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest">
                        {{ $availability->label }}
                    </span>
                </div>

                <div class="space-y-3 mb-6">
                    <div class="flex justify-between items-center bg-slate-50 p-3 rounded-xl border border-slate-100">
                        <span class="text-[10px] text-slate-400 font-black uppercase tracking-widest">Zone</span>
                        <span class="text-sm text-slate-800 font-black uppercase">{{ $stall->zone ?? 'Main' }}</span>
                    </div>
                    <div class="flex justify-between items-center bg-slate-50 p-3 rounded-xl border border-slate-100">
                        <span class="text-[10px] text-slate-400 font-black uppercase tracking-widest">Location</span>
                        <span class="text-xs text-slate-800 font-bold text-right">{{ $stall->location_desc ?? 'N/A' }}</span>
                    </div>
                </div>

                {{-- Live Total --}}
                <div class="p-5 rounded-2xl border-2 flex flex-col items-center justify-center relative overflow-hidden" style="background:#f0fdf4; border-color:#bbf7d0;">
                    <div class="absolute -right-10 -top-10 w-32 h-32 bg-emerald-200/50 rounded-full blur-2xl pointer-events-none"></div>
                    <p class="text-[10px] font-black uppercase tracking-widest text-emerald-800 mb-1 relative z-10">Total to Pay</p>
                    <div class="flex items-baseline gap-1.5 relative z-10">
                        <span class="text-sm font-black text-emerald-700">KES</span>
                        <span id="liveTotal" class="text-4xl font-black text-emerald-900 tracking-tighter">—</span>
                    </div>
                    <p id="liveDurationLabel" class="text-[10px] text-emerald-600 font-bold mt-1 relative z-10"></p>
                </div>

                {{-- Discount info cards --}}
                <div class="mt-4 space-y-2">
                    <div class="bg-amber-50 border border-amber-100 rounded-xl p-3 text-center">
                        <p class="text-[10px] font-black text-amber-700 uppercase tracking-widest">🎁 Loyalty Savings</p>
                        <p class="text-xs text-amber-600 font-medium mt-1">Every 7 days booked = 1 free day!</p>
                    </div>
                    <div class="bg-blue-50 border border-blue-100 rounded-xl p-3 text-center">
                        <p class="text-[10px] font-black text-blue-700 uppercase tracking-widest">🔥 Monthly Deal</p>
                        <p class="text-xs text-blue-600 font-medium mt-1">Book 30 days for only KES 23 — extra savings!</p>
                    </div>
                </div>

                <div class="mt-6 flex flex-col items-center justify-center gap-2 pt-4 border-t border-slate-100 text-center">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center" style="background:rgba(30,81,40,.1);">
                        <svg class="w-6 h-6" style="color:#1E5128;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    </div>
                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-500">Verified by Nairobi City County</p>
                </div>
            </div>

            @if($availability->status === 'available_until')
                <div class="p-4 bg-amber-50 rounded-2xl border border-amber-200 shadow-sm relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-1 h-full bg-amber-500"></div>
                    <p class="text-[10px] text-amber-800 font-black uppercase tracking-widest mb-1 flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span> Time Capped
                    </p>
                    <p class="text-xs text-amber-700 font-medium leading-relaxed mb-2">Due to a future reservation, you can only book until:</p>
                    <p class="text-sm font-black text-amber-900 bg-amber-100/50 p-2 rounded-lg text-center">
                        {{ $maxEndTime ? $maxEndTime->format('d M Y, H:i') : 'N/A' }}
                    </p>
                </div>
            @endif

            @if($upcomingBookings->count() > 0)
                <div class="p-4 bg-blue-50 rounded-2xl border border-blue-100 shadow-sm relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-1 h-full bg-blue-500"></div>
                    <h4 class="text-[10px] font-black text-blue-800 uppercase tracking-widest mb-3 flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        Future Reservations
                    </h4>
                    <div class="space-y-2">
                        @foreach($upcomingBookings as $upcoming)
                            <div class="flex items-center justify-between p-2.5 bg-white/60 rounded-xl border border-blue-100/50">
                                <p class="text-[11px] font-black text-blue-900">{{ $upcoming->start_time->format('d M, H:i') }}</p>
                                <span class="text-[9px] font-black text-blue-600 uppercase tracking-widest px-2 py-1 bg-blue-100/50 rounded-lg">🔒 Reserved</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    // ── Pricing tables ──────────────────────────────────────────────
    const PRESET_PRICES = { 1: 1, 7: 6, 14: 12, 21: 18, 30: 23 };

    function calcAmount(days) {
        if (PRESET_PRICES[days] !== undefined) return PRESET_PRICES[days];
        return days - Math.floor(days / 7);   // 1 free day per 7 booked
    }

    function freeDays(days) {
        if (days === 30) return 7; // special monthly bonus
        return Math.floor(days / 7);
    }

    // ── DOM refs ────────────────────────────────────────────────────
    const startInput       = document.getElementById('start_time');
    const presetSel        = document.getElementById('durationPreset');
    const customWrapper    = document.getElementById('customDaysWrapper');
    const customDaysInput  = document.getElementById('customDays');
    const endDisplay       = document.getElementById('endTimeDisplay');
    const pricingPanel     = document.getElementById('pricingPanel');
    const liveTotal        = document.getElementById('liveTotal');
    const liveDurationLbl  = document.getElementById('liveDurationLabel');
    const breakdownDays    = document.getElementById('breakdownDays');
    const breakdownFree    = document.getElementById('breakdownFree');
    const discountRow      = document.getElementById('discountRow');
    const breakdownTotal   = document.getElementById('breakdownTotal');
    const hiddenDuration   = document.getElementById('hiddenDuration');
    const hiddenAmount     = document.getElementById('hiddenAmount');
    const payBtn           = document.getElementById('payBtn');
    const payBtnLabel      = document.getElementById('payBtnLabel');

    let currentDays   = null;
    let currentAmount = null;

    // ── Update UI whenever start time or duration changes ───────────
    function refresh() {
        const startVal = startInput.value;
        const days     = currentDays;

        if (!startVal || !days || days < 1) {
            endDisplay.value = '';
            pricingPanel.classList.add('hidden');
            liveTotal.textContent = '—';
            liveDurationLbl.textContent = '';
            payBtn.disabled = true;
            payBtn.classList.add('opacity-50', 'cursor-not-allowed');
            payBtnLabel.textContent = 'Select duration to continue';
            hiddenDuration.value = '';
            hiddenAmount.value   = '';
            return;
        }

        // Calculate end time
        const start   = new Date(startVal);
        const end     = new Date(start);
        end.setDate(end.getDate() + days);

        const fmt = d => d.toLocaleDateString('en-KE', { day:'2-digit', month:'short', year:'numeric' })
                      + ' ' + d.toLocaleTimeString('en-KE', { hour:'2-digit', minute:'2-digit' });
        endDisplay.value = fmt(end);

        // Calculate amount
        const amount = calcAmount(days);
        const free   = freeDays(days);
        currentAmount = amount;

        // Update breakdown panel
        const dayLabel = days === 1 ? '1 day' : `${days} days`;
        breakdownDays.textContent  = dayLabel;
        breakdownTotal.textContent = `KES ${amount}`;

        if (free > 0) {
            discountRow.classList.remove('hidden');
            breakdownFree.textContent = `−${free} day${free > 1 ? 's' : ''} FREE`;
        } else {
            discountRow.classList.add('hidden');
        }
        pricingPanel.classList.remove('hidden');

        // Sidebar live total
        liveTotal.textContent    = amount;
        liveDurationLbl.textContent = dayLabel;

        // Hidden fields
        hiddenDuration.value = days;
        hiddenAmount.value   = amount;

        // Enable submit
        payBtn.disabled = false;
        payBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        payBtnLabel.textContent = `Lipa na M-Pesa — KES ${amount}`;
    }

    // ── Preset selector ─────────────────────────────────────────────
    presetSel.addEventListener('change', function () {
        const val = this.value;
        if (val === 'custom') {
            customWrapper.classList.remove('hidden');
            currentDays = parseInt(customDaysInput.value) || null;
        } else if (val !== '') {
            customWrapper.classList.add('hidden');
            currentDays = parseInt(val);
        } else {
            customWrapper.classList.add('hidden');
            currentDays = null;
        }
        refresh();
    });

    // ── Custom days input ───────────────────────────────────────────
    customDaysInput.addEventListener('input', function () {
        const v = parseInt(this.value);
        currentDays = (v && v >= 1) ? v : null;
        refresh();
    });

    // ── Arrival time input ──────────────────────────────────────────
    startInput.addEventListener('change', refresh);

    // ── FOMO Timer ──────────────────────────────────────────────────
    let timeLeft = 5 * 60;
    const timerEl = document.getElementById('fomoTimer');
    setInterval(() => {
        if (timeLeft > 0) timeLeft--;
        const m = Math.floor(timeLeft / 60);
        const s = timeLeft % 60;
        timerEl.textContent = `${String(m).padStart(2,'0')}:${String(s).padStart(2,'0')}`;
        if (timeLeft < 60) timerEl.classList.add('text-red-600');
    }, 1000);

    // ── Submit loading state ────────────────────────────────────────
    document.getElementById('bookingForm').addEventListener('submit', function (e) {
        if (!hiddenDuration.value || !hiddenAmount.value) {
            e.preventDefault();
            alert('Please select a duration before continuing.');
            return;
        }
        payBtnLabel.textContent = 'Creating booking…';
        payBtn.classList.add('opacity-80', 'cursor-not-allowed');
        payBtn.disabled = true;
    });
});
</script>
@endsection
