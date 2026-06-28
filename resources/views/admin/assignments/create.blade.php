@extends('layouts.app')

@section('page-title', 'Manual Stall Assignment')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    {{-- ── Hero / Header ──────────────────────────────────────────── --}}
    <div class="relative overflow-hidden rounded-2xl shadow-lg"
         style="background:linear-gradient(135deg,#0a3282,#0F47AF,#1a5fd4);">

        {{-- County stripe --}}
        <div class="absolute top-0 left-0 right-0 h-1"
             style="background:linear-gradient(90deg,#0F47AF 0%,#0F47AF 33%,#fff 33%,#fff 66%,#068930 66%);"></div>

        <div class="p-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.dashboard') }}"
                   class="flex items-center justify-center w-10 h-10 rounded-xl transition-all hover:scale-105 active:scale-95"
                   style="background:rgba(255,255,255,.15);border:1px solid rgba(255,255,255,.25);">
                    <svg style="width:18px;height:18px;" fill="none" stroke="white" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
                <div>
                    <h2 class="text-xl font-black text-white tracking-tight">Manual Stall Assignment</h2>
                    <p style="color:rgba(255,255,255,.6);font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;">
                        Service Desk · Muthurwa Market · Nairobi County
                    </p>
                </div>
            </div>
            <div style="background:rgba(252,221,7,.15);border:1px solid rgba(252,221,7,.35);border-radius:.9rem;padding:.5rem 1rem;">
                <p style="color:#FCDD07;font-size:.6rem;font-weight:900;text-transform:uppercase;letter-spacing:.12em;margin:0;">
                    ⚡ Awaits Trader Payment
                </p>
            </div>
        </div>
    </div>

    {{-- Flash messages --}}
    @if(session('success'))
        <div class="flex items-start gap-3 p-4 rounded-2xl border"
             style="background:#f0fdf4;border-color:#86efac;">
            <span class="text-xl mt-0.5">✅</span>
            <p class="text-sm font-bold" style="color:#166534;">{{ session('success') }}</p>
        </div>
    @endif
    @if(session('error'))
        <div class="flex items-start gap-3 p-4 rounded-2xl border"
             style="background:#fef2f2;border-color:#fca5a5;">
            <span class="text-xl mt-0.5">❌</span>
            <p class="text-sm font-bold" style="color:#991b1b;">{{ session('error') }}</p>
        </div>
    @endif

    {{-- ── Smart Stall Overview (All Stalls at a Glance) ─────────── --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between"
             style="background:linear-gradient(90deg,#f0f7ff,#fff);">
            <div class="flex items-center gap-2">
                <div class="w-1 h-6 rounded-full" style="background:#0F47AF;"></div>
                <h3 class="text-sm font-black uppercase tracking-tight" style="color:#0a3282;">
                    All Stalls — Smart Availability
                </h3>
            </div>
            <div class="flex gap-2 text-[9px] font-bold uppercase tracking-wider">
                @php
                    $bookableCount = $allStallsWithAvailability->where('availability.can_book', true)->count();
                    $blockedCount = $allStallsWithAvailability->where('availability.can_book', false)->count();
                @endphp
                <span class="px-2.5 py-1 rounded-lg" style="background:#dcfce7;color:#166534;border:1px solid #bbf7d0;">
                    {{ $bookableCount }} Bookable
                </span>
                <span class="px-2.5 py-1 rounded-lg" style="background:#fee2e2;color:#991b1b;border:1px solid #fecaca;">
                    {{ $blockedCount }} Blocked
                </span>
            </div>
        </div>

        <div class="max-h-[280px] overflow-y-auto">
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-2 p-4">
                @foreach($allStallsWithAvailability as $item)
                    @php $a = $item->availability; @endphp
                    <div class="p-3 rounded-xl border text-center transition-all hover:shadow-sm
                        {{ $a->status === 'available' ? 'bg-emerald-50 border-emerald-200' : '' }}
                        {{ $a->status === 'available_until' ? 'bg-emerald-50 border-blue-200' : '' }}
                        {{ $a->status === 'booked_soon' ? 'bg-amber-50 border-amber-300' : '' }}
                        {{ $a->status === 'occupied' ? 'bg-rose-50 border-rose-200' : '' }}">
                        <p class="font-black text-sm text-slate-800">#{{ $item->stall->stall_number }}</p>
                        <div class="flex items-center justify-center gap-1 mt-1">
                            <span class="w-1.5 h-1.5 rounded-full
                                {{ $a->color === 'emerald' ? 'bg-emerald-500' : '' }}
                                {{ $a->color === 'amber' ? 'bg-amber-500 animate-pulse' : '' }}
                                {{ $a->color === 'rose' ? 'bg-rose-500 animate-pulse' : '' }}"></span>
                            <span class="text-[9px] font-bold uppercase
                                {{ $a->color === 'emerald' ? 'text-emerald-700' : '' }}
                                {{ $a->color === 'amber' ? 'text-amber-700' : '' }}
                                {{ $a->color === 'rose' ? 'text-rose-700' : '' }}">
                                {{ $a->label }}
                            </span>
                        </div>
                        @if($a->status === 'available_until')
                            <p class="text-[8px] text-blue-600 font-bold mt-1 truncate" title="{{ $a->detail }}">
                                {{ $a->detail }}
                            </p>
                        @elseif($a->status === 'booked_soon')
                            <p class="text-[8px] text-amber-600 font-bold mt-1 truncate" title="{{ $a->detail }}">
                                {{ $a->detail }}
                            </p>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- ── Assignment Form ─────────────────────────────────────────── --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

        {{-- Title bar --}}
        <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-2"
             style="background:linear-gradient(90deg,#f0f7ff,#fff);">
            <div class="w-1 h-6 rounded-full" style="background:#0F47AF;"></div>
            <h3 class="text-sm font-black uppercase tracking-tight" style="color:#0a3282;">New Booking — Step by Step</h3>
        </div>

        <div class="p-8">
            <form action="{{ route('admin.stalls.assign.store') }}" method="POST" class="space-y-8" id="assignForm">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    {{-- Step 1: Stall (only bookable stalls) --}}
                    <div class="space-y-2">
                        <label class="flex items-center gap-2 text-xs font-black uppercase tracking-widest" style="color:#0a3282;">
                            <span class="w-6 h-6 rounded-lg flex items-center justify-center text-white text-[10px] font-black"
                                  style="background:#0F47AF;">1</span>
                            Choose Bookable Stall
                        </label>
                        <select name="stall_id" id="stall_id" required
                                class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm font-medium focus:outline-none focus:ring-4 transition-all"
                                style="background:#f8fafc;focus:ring-color:rgba(15,71,175,.12);"
                                onchange="updatePrice(this)">
                            <option value="" disabled selected>Select stall number / zone…</option>
                            @foreach($stalls as $stall)
                                @php $sa = $stall->getSmartAvailability(); @endphp
                                <option value="{{ $stall->id }}" 
                                        data-price="{{ $stall->price }}"
                                        data-max-end="{{ $stall->getMaxBookingEndTime() ? $stall->getMaxBookingEndTime()->format('Y-m-d\TH:i') : '' }}">
                                    #{{ $stall->stall_number }} — {{ $stall->zone }} · KES {{ number_format($stall->price) }}
                                    @if($sa->status === 'available_until')
                                        · (until {{ $sa->available_until->format('d M H:i') }})
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        <p class="text-[10px] text-slate-400 italic px-1">
                            Only bookable stalls shown ({{ $stalls->count() }} of {{ $allStallsWithAvailability->count() }} total).
                        </p>
                    </div>

                    {{-- Step 2: Trader --}}
                    <div class="space-y-2">
                        <label class="flex items-center gap-2 text-xs font-black uppercase tracking-widest" style="color:#0a3282;">
                            <span class="w-6 h-6 rounded-lg flex items-center justify-center text-white text-[10px] font-black"
                                  style="background:#0F47AF;">2</span>
                            Select Registered Trader
                        </label>
                        <select name="user_id" required
                                class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm font-medium focus:outline-none focus:ring-4 transition-all"
                                style="background:#f8fafc;">
                            <option value="" disabled selected>Search by name / phone…</option>
                            @foreach($traders as $trader)
                                <option value="{{ $trader->id }}">
                                    {{ $trader->name }} — {{ $trader->phone_number }}
                                </option>
                            @endforeach
                        </select>
                        <p class="text-[10px] text-slate-400 italic px-1">Traders must be registered before assignment.</p>
                    </div>

                    {{-- Step 3: Start Time --}}
                    <div class="space-y-2">
                        <label class="flex items-center gap-2 text-xs font-black uppercase tracking-widest" style="color:#0a3282;">
                            <span class="w-6 h-6 rounded-lg flex items-center justify-center text-white text-[10px] font-black"
                                  style="background:#0F47AF;">3</span>
                            Booking Start
                        </label>
                        <input type="datetime-local" name="start_time" id="start_time"
                               value="{{ now()->format('Y-m-d\TH:i') }}"
                               min="{{ now()->format('Y-m-d\TH:i') }}" required
                               class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm font-medium focus:outline-none focus:ring-4 transition-all"
                               style="background:#f8fafc;">
                    </div>

                    {{-- Step 4: End Time (auto-filled +24h, capped by buffer) --}}
                    <div class="space-y-2">
                        <label class="flex items-center gap-2 text-xs font-black uppercase tracking-widest" style="color:#0a3282;">
                            <span class="w-6 h-6 rounded-lg flex items-center justify-center text-white text-[10px] font-black"
                                  style="background:#0F47AF;">4</span>
                            Booking End
                        </label>
                        <input type="datetime-local" name="end_time" id="end_time" required
                               class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm font-medium focus:outline-none focus:ring-4 transition-all"
                               style="background:#f8fafc;">
                        <p class="text-[10px] text-slate-400 italic px-1">Auto-filled to 24h after start. Capped by buffer if needed.</p>
                    </div>

                    {{-- Step 5: Admin Notes (full width) --}}
                    <div class="space-y-2 md:col-span-2">
                        <label class="flex items-center gap-2 text-xs font-black uppercase tracking-widest" style="color:#0a3282;">
                            <span class="w-6 h-6 rounded-lg flex items-center justify-center text-white text-[10px] font-black"
                                  style="background:#068930;">5</span>
                            Admin Notes <span class="text-slate-400 font-normal normal-case tracking-normal">(optional)</span>
                        </label>
                        <textarea name="admin_notes" rows="3"
                                  placeholder="e.g. Trader requested same spot as last week. Priority booking — market manager approval."
                                  class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm font-medium focus:outline-none focus:ring-4 transition-all resize-none"
                                  style="background:#f8fafc;"></textarea>
                    </div>
                </div>

                {{-- Fee Preview --}}
                <div id="feePreview" class="hidden rounded-2xl p-5 border"
                     style="background:linear-gradient(135deg,#eff6ff,#f0fdf4);border-color:#bfdbfe;">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-widest" style="color:#0F47AF;">Estimated Fee</p>
                            <p class="text-2xl font-black mt-1" style="color:#0a3282;">
                                KES <span id="feeAmount">0</span>
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="text-[10px] font-black uppercase tracking-widest text-amber-700">Payment Mode</p>
                            <p class="text-sm font-black text-amber-800 mt-1">📱 M-Pesa (Trader Pays)</p>
                        </div>
                    </div>
                    <div id="bufferNotice" class="hidden mt-3 p-3 rounded-xl" style="background:rgba(245,158,11,.1);border:1px solid rgba(245,158,11,.3);">
                        <p class="text-[10px] font-bold" style="color:#92400e;">
                            ⚠️ <span id="bufferNoticeText"></span>
                        </p>
                    </div>
                    <div class="mt-3 p-3 rounded-xl" style="background:rgba(252,221,7,.12);border:1px solid rgba(252,221,7,.3);">
                        <p class="text-[10px] font-bold" style="color:#92400e;">
                            💡 This booking will be created as <strong>Pending</strong>. You must then prompt the trader to
                            complete M-Pesa payment before the stall is locked.
                        </p>
                    </div>
                </div>

                {{-- Action Footer --}}
                <div class="pt-6 border-t border-gray-100 flex items-center justify-between gap-4">
                    <a href="{{ route('admin.dashboard') }}"
                       class="text-xs font-black uppercase tracking-widest transition-all hover:text-slate-600"
                       style="color:#94a3b8;">← Cancel</a>

                    <button type="submit"
                            class="flex items-center gap-2 text-white px-10 py-4 rounded-xl font-black text-xs uppercase tracking-widest transition-all hover:scale-105 active:scale-95 shadow-lg"
                            style="background:linear-gradient(135deg,#0F47AF,#1a5fd4);box-shadow:0 4px 18px rgba(15,71,175,.35);">
                        <svg style="width:16px;height:16px;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                        </svg>
                        Create Booking
                    </button>
                </div>

            </form>
        </div>

        {{-- County footer stripe --}}
        <div class="h-1" style="background:linear-gradient(90deg,#0F47AF 0%,#0F47AF 33%,#fff 33%,#fff 66%,#068930 66%);"></div>
    </div>

    {{-- Info Notice --}}
    <div class="rounded-2xl p-5 border flex items-start gap-4" style="background:#fffbeb;border-color:#fcd34d;">
        <span class="text-2xl">📋</span>
        <div>
            <p class="text-sm font-black" style="color:#78350f;">How Manual Assignment Works</p>
            <ol class="mt-2 space-y-1 text-xs font-medium" style="color:#92400e;">
                <li>1️⃣ Admin fills this form → booking is created as <strong>Pending</strong></li>
                <li>2️⃣ Admin clicks <strong>"Prompt Payment"</strong> on the Booked Stalls page → SMS sent to trader</li>
                <li>3️⃣ Trader logs in and completes M-Pesa STK push → booking auto-confirms & stall locks</li>
                <li>4️⃣ The booking record shows which admin created it and when the prompt was sent</li>
            </ol>
            <div class="mt-3 p-2 rounded-lg" style="background:rgba(245,158,11,.1);border:1px solid rgba(245,158,11,.2);">
                <p class="text-[10px] font-bold" style="color:#92400e;">
                    🛡️ <strong>24-Hour Buffer Rule:</strong> New bookings are automatically blocked within 24 hours of any existing reservation
                    to allow preparation time. Only stalls with sufficient clearance appear in the dropdown.
                </p>
            </div>
        </div>
    </div>

</div>

<script>
    let currentMaxEnd = null;

    // Auto-fill end_time as start + 24h, capped by buffer
    document.getElementById('start_time').addEventListener('change', function () {
        const val = this.value;
        if (!val) return;
        const d = new Date(val);
        if (isNaN(d)) return;
        d.setHours(d.getHours() + 24);

        const bufferNotice = document.getElementById('bufferNotice');
        const bufferText = document.getElementById('bufferNoticeText');

        // Cap by max end time if set
        if (currentMaxEnd) {
            const maxDate = new Date(currentMaxEnd);
            if (d > maxDate) {
                d.setTime(maxDate.getTime());
                bufferNotice.classList.remove('hidden');
                bufferText.textContent = 'End time capped to ' + d.toLocaleString() + ' due to the 24-hour preparation buffer before the next booking.';
            } else {
                bufferNotice.classList.add('hidden');
            }
        } else {
            bufferNotice.classList.add('hidden');
        }

        const pad = n => String(n).padStart(2,'0');
        document.getElementById('end_time').value =
            `${d.getFullYear()}-${pad(d.getMonth()+1)}-${pad(d.getDate())}T${pad(d.getHours())}:${pad(d.getMinutes())}`;
    });

    // Show fee preview and set max end when stall is selected
    function updatePrice(sel) {
        const opt = sel.options[sel.selectedIndex];
        const price = opt ? opt.getAttribute('data-price') : null;
        const maxEnd = opt ? opt.getAttribute('data-max-end') : null;
        const preview = document.getElementById('feePreview');
        const feeAmt  = document.getElementById('feeAmount');

        currentMaxEnd = maxEnd || null;

        if (price) {
            feeAmt.textContent = Number(price).toLocaleString();
            preview.classList.remove('hidden');
        } else {
            preview.classList.add('hidden');
        }

        // Re-trigger start time change to apply new max end
        document.getElementById('start_time').dispatchEvent(new Event('change'));
    }
</script>
@endsection
