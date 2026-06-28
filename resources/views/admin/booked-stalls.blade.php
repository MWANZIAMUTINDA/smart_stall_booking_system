@extends('layouts.app')

@section('page-title', 'Booked Stalls — Admin Overview')

@section('content')
<div class="space-y-6">

    {{-- ── Nairobi County Colour Tokens ─────────────────────────────── --}}
    {{-- Yellow=#FCDD07 | Green=#068930 | White=#FFF | Blue=#0F47AF    --}}

    {{-- ── Hero Banner ──────────────────────────────────────────────── --}}
    <div class="relative overflow-hidden rounded-2xl shadow-lg"
         style="background:linear-gradient(135deg,#0a3282,#0F47AF,#1a5fd4);">

        <div class="absolute top-0 left-0 right-0 h-1"
             style="background:linear-gradient(90deg,#0F47AF 0%,#0F47AF 33%,#fff 33%,#fff 66%,#068930 66%);"></div>

        <div class="p-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.dashboard') }}"
                   class="flex items-center justify-center w-10 h-10 rounded-xl transition-all hover:scale-105"
                   style="background:rgba(255,255,255,.15);border:1px solid rgba(255,255,255,.25);">
                    <svg style="width:18px;height:18px;" fill="none" stroke="white" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
                <div>
                    <h2 class="text-xl font-black text-white tracking-tight">Booked Stalls Registry</h2>
                    <p style="color:rgba(255,255,255,.6);font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;">
                        Active &amp; Pending Bookings · Muthurwa Market
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                {{-- Pending count badge --}}
                @php $pendingCount = $bookedStalls->where('status','pending')->count(); @endphp
                @if($pendingCount)
                <div style="background:rgba(252,221,7,.2);border:1px solid rgba(252,221,7,.4);border-radius:.9rem;padding:.5rem 1rem;text-align:center;">
                    <span class="text-white font-black text-xl tabular-nums">{{ $pendingCount }}</span>
                    <p style="color:#FCDD07;font-size:.6rem;font-weight:900;text-transform:uppercase;letter-spacing:.1em;margin:0;">Awaiting Payment</p>
                </div>
                @endif
                {{-- Confirmed count badge --}}
                <div style="background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.22);border-radius:.9rem;padding:.5rem 1rem;text-align:center;">
                    <span class="text-white font-black text-xl tabular-nums">{{ $bookedStalls->where('status','confirmed')->count() }}</span>
                    <p style="color:#FCDD07;font-size:.6rem;font-weight:900;text-transform:uppercase;letter-spacing:.1em;margin:0;">Confirmed</p>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Flash messages ────────────────────────────────────────────── --}}
    @if(session('success'))
        <div class="flex items-start gap-3 p-4 rounded-2xl border"
             style="background:#f0fdf4;border-color:#86efac;">
            <span class="text-xl mt-0.5">✅</span>
            <p class="text-sm font-bold" style="color:#166534;">{{ session('success') }}</p>
        </div>
    @endif
    @if(session('warning'))
        <div class="flex items-start gap-3 p-4 rounded-2xl border"
             style="background:#fffbeb;border-color:#fcd34d;">
            <span class="text-xl mt-0.5">⚠️</span>
            <p class="text-sm font-bold" style="color:#92400e;">{{ session('warning') }}</p>
        </div>
    @endif
    @if(session('error'))
        <div class="flex items-start gap-3 p-4 rounded-2xl border"
             style="background:#fef2f2;border-color:#fca5a5;">
            <span class="text-xl mt-0.5">❌</span>
            <p class="text-sm font-bold" style="color:#991b1b;">{{ session('error') }}</p>
        </div>
    @endif

    {{-- ── Main Table ─────────────────────────────────────────────────── --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

        {{-- Table title bar --}}
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between"
             style="background:linear-gradient(90deg,#f0f7ff,#fff);">
            <div class="flex items-center gap-2">
                <div class="w-1 h-6 rounded-full" style="background:#0F47AF;"></div>
                <h3 class="text-sm font-black uppercase tracking-tight" style="color:#0a3282;">
                    All Active &amp; Pending Stall Bookings
                </h3>
            </div>
            <a href="{{ route('admin.stalls.assign.create') }}"
               class="flex items-center gap-2 text-white px-4 py-2 rounded-xl font-black text-xs uppercase tracking-wider transition-all hover:scale-105 active:scale-95"
               style="background:linear-gradient(135deg,#0F47AF,#1a5fd4);box-shadow:0 4px 12px rgba(15,71,175,.3);">
                ➕ New Assignment
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="sticky top-0 z-10" style="background:#f8fafc;">
                    <tr class="text-[10px] uppercase tracking-widest font-black" style="color:#94a3b8;">
                        <th class="px-5 py-4">Stall / Zone</th>
                        <th class="px-5 py-4">Trader</th>
                        <th class="px-5 py-4">Period</th>
                        <th class="px-5 py-4">Status</th>
                        <th class="px-5 py-4">Booked By</th>
                        <th class="px-5 py-4">Payment Prompt</th>
                        <th class="px-5 py-4 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 text-sm">
                    @forelse($bookedStalls as $booking)
                    <tr class="hover:bg-blue-50/30 transition-colors
                               {{ $booking->status === 'pending' ? 'bg-amber-50/40' : '' }}">

                        {{-- Stall / Zone --}}
                        <td class="px-5 py-4">
                            <span class="px-3 py-1.5 rounded-xl font-black text-xs block w-max mb-1"
                                  style="background:#eff6ff;color:#0F47AF;border:1px solid #bfdbfe;">
                                #{{ $booking->stall->stall_number }}
                            </span>
                            <span class="px-2 py-1 rounded-md text-[10px] font-bold uppercase"
                                  style="background:#f8fafc;color:#64748b;border:1px solid #e2e8f0;">
                                {{ $booking->stall->zone }}
                            </span>
                        </td>

                        {{-- Trader --}}
                        <td class="px-5 py-4">
                            <p class="font-bold text-slate-800">{{ $booking->user->name }}</p>
                            <p class="text-[10px] text-slate-400 mt-0.5 tabular-nums">{{ $booking->user->phone_number }}</p>
                            @if($booking->admin_notes)
                                <p class="text-[10px] italic text-blue-600 mt-1 max-w-[160px] truncate"
                                   title="{{ $booking->admin_notes }}">
                                    📝 {{ $booking->admin_notes }}
                                </p>
                            @endif
                        </td>

                        {{-- Period --}}
                        <td class="px-5 py-4 tabular-nums">
                            <p class="text-[11px] font-bold text-slate-700">
                                {{ $booking->start_time->format('d M Y, H:i') }}
                            </p>
                            <p class="text-[10px] text-slate-400 mt-0.5">
                                → {{ $booking->end_time->format('d M Y, H:i') }}
                            </p>
                            <p class="text-[9px] font-bold uppercase mt-1" style="color:#64748b;">
                                {{ $booking->start_time->diffForHumans() }}
                            </p>
                        </td>

                        {{-- Status Badge --}}
                        <td class="px-5 py-4">
                            @if($booking->status === 'confirmed')
                                <span class="px-2.5 py-1 rounded-lg text-[10px] font-black uppercase"
                                      style="background:#dcfce7;color:#166534;border:1px solid #bbf7d0;">
                                    <span class="inline-block w-1.5 h-1.5 rounded-full bg-green-500 mr-1 animate-pulse"></span>
                                    Confirmed
                                </span>
                            @else
                                <span class="px-2.5 py-1 rounded-lg text-[10px] font-black uppercase"
                                      style="background:#fef3c7;color:#92400e;border:1px solid #fcd34d;">
                                    ⏳ Pending Payment
                                </span>
                            @endif
                            @if($booking->receipt_number)
                                <p class="text-[9px] text-slate-400 mt-1.5 font-bold tabular-nums">
                                    {{ $booking->receipt_number }}
                                </p>
                            @endif
                        </td>

                        {{-- Booked By (Admin) --}}
                        <td class="px-5 py-4">
                            @if($booking->bookedByAdmin)
                                <p class="font-bold text-xs text-slate-700">{{ $booking->bookedByAdmin->name }}</p>
                                <span class="text-[9px] uppercase font-black px-1.5 py-0.5 rounded mt-1 inline-block"
                                      style="background:#eff6ff;color:#0F47AF;">Admin</span>
                            @else
                                <p class="text-slate-400 text-[10px] italic">Self-booked</p>
                            @endif
                        </td>

                        {{-- Payment Prompt Info --}}
                        <td class="px-5 py-4">
                            @if($booking->payment_prompt_sent_at)
                                <p class="text-[10px] font-bold text-emerald-700">
                                    ✉️ Sent {{ $booking->payment_prompt_sent_at->diffForHumans() }}
                                </p>
                                <p class="text-[9px] text-slate-400 tabular-nums mt-0.5">
                                    {{ $booking->payment_prompt_sent_at->format('d M, H:i') }}
                                </p>
                            @else
                                <p class="text-[10px] text-slate-400 italic">Not sent yet</p>
                            @endif
                        </td>

                        {{-- Action: Prompt Payment button (only for pending) --}}
                        <td class="px-5 py-4 text-right">
                            @if($booking->status === 'pending')
                                <form action="{{ route('admin.bookings.prompt', $booking->id) }}"
                                      method="POST"
                                      onsubmit="return confirm('Send M-Pesa payment prompt SMS to {{ addslashes($booking->user->name) }} ({{ $booking->user->phone_number }})?')">
                                    @csrf
                                    <button type="submit"
                                            class="flex items-center gap-1.5 ml-auto text-white px-3 py-2 rounded-xl font-black text-[10px] uppercase tracking-wider transition-all hover:scale-105 active:scale-95"
                                            style="background:linear-gradient(135deg,#065f26,#068930);box-shadow:0 3px 10px rgba(6,137,48,.3);">
                                        📱 Prompt Payment
                                    </button>
                                </form>
                                @if($booking->payment_prompt_sent_at)
                                    <p class="text-[9px] text-amber-600 font-bold mt-1.5 text-right">Re-send prompt</p>
                                @endif
                            @else
                                <span class="text-[10px] font-black uppercase px-2.5 py-1 rounded-lg"
                                      style="background:#dcfce7;color:#166534;">
                                    ✓ Paid
                                </span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-20 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <div style="width:56px;height:56px;border-radius:14px;background:#f0fdf4;
                                            border:2px solid #bbf7d0;display:flex;align-items:center;
                                            justify-content:center;font-size:1.75rem;margin:auto;">
                                    🏢
                                </div>
                                <p class="font-black uppercase text-xs tracking-widest" style="color:#94a3b8;">
                                    No Active or Pending Bookings Found
                                </p>
                                <a href="{{ route('admin.stalls.assign.create') }}"
                                   class="text-xs font-black uppercase tracking-wider text-white px-5 py-2.5 rounded-xl transition-all hover:scale-105"
                                   style="background:#0F47AF;">
                                    ➕ Create Manual Booking
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- County flag footer stripe --}}
        <div class="h-1" style="background:linear-gradient(90deg,#0F47AF 0%,#0F47AF 33%,#fff 33%,#fff 66%,#068930 66%);"></div>
    </div>

    {{-- Legend --}}
    <div class="flex flex-wrap items-center gap-4 px-2">
        <div class="flex items-center gap-2">
            <span class="w-3 h-3 rounded-full inline-block" style="background:#068930;"></span>
            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500">Confirmed — Stall Locked, Payment Received</span>
        </div>
        <div class="flex items-center gap-2">
            <span class="w-3 h-3 rounded-full inline-block" style="background:#FCDD07;border:1px solid #d97706;"></span>
            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500">Pending — Awaiting Trader M-Pesa Payment</span>
        </div>
    </div>

    {{-- County attribution --}}
    <p class="text-center text-[10px] font-bold uppercase tracking-[.2em] pb-2" style="color:#94a3b8;">
        Nairobi City County &bull; Muthurwa Market System &bull; &copy; {{ date('Y') }}
    </p>

</div>
@endsection
