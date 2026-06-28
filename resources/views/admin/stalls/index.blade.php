@extends('layouts.app')

@section('page-title', 'Stall Management')

@section('content')
<div class="space-y-6">

    {{-- ── County Header Bar ──────────────────────────────────────── --}}
    <div class="relative overflow-hidden rounded-2xl shadow-lg"
         style="background:linear-gradient(135deg,#0a3282,#0F47AF,#1a5fd4);">

        {{-- County stripe --}}
        <div class="absolute top-0 left-0 right-0 h-1"
             style="background:linear-gradient(90deg,#0F47AF 0%,#0F47AF 33%,#fff 33%,#fff 66%,#068930 66%);"></div>

        <div class="p-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div style="width:44px;height:44px;border-radius:12px;background:rgba(255,255,255,.15);
                            border:1px solid rgba(255,255,255,.25);display:flex;align-items:center;justify-content:center;">
                    <svg style="width:24px;height:24px;" fill="none" stroke="#FCDD07" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl font-black text-white tracking-tight">Stall Management</h2>
                    <p style="color:rgba(255,255,255,.6);font-size:.68rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;">
                        Nairobi County · Muthurwa Market Control Panel
                    </p>
                </div>
            </div>
            <a href="{{ route('admin.dashboard') }}"
               class="flex items-center gap-2 text-white px-4 py-2.5 rounded-xl font-bold text-xs uppercase tracking-wider transition-all hover:scale-105 active:scale-95"
               style="background:rgba(255,255,255,.15);border:1px solid rgba(255,255,255,.25);">
                ← Back to Dashboard
            </a>
        </div>
    </div>

    {{-- ── Flash Messages ──────────────────────────────────────────── --}}
    @foreach (['success'=>'#dcfce7|#166534|✅', 'error'=>'#fee2e2|#b91c1c|❌', 'warning'=>'#fef9c3|#92400e|⚠️'] as $type => $cfg)
        @if(session($type))
            @php [$bg,$clr,$icon] = explode('|', $cfg); @endphp
            <div class="flex items-center gap-3 px-5 py-3 rounded-xl font-bold text-sm"
                 style="background:{{ $bg }};color:{{ $clr }};border:1px solid {{ $clr }}22;">
                <span>{{ $icon }}</span> {{ session($type) }}
            </div>
        @endif
    @endforeach

    {{-- ── KPI Cards ───────────────────────────────────────────────── --}}
    <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
        @php
            $kpis = [
                ['label'=>'Total Stalls',  'value'=>$stats['total'],       'color'=>'#0F47AF'],
                ['label'=>'Available',     'value'=>$stats['available'],   'color'=>'#068930'],
                ['label'=>'Occupied',      'value'=>$stats['occupied'],    'color'=>'#e11d48'],
                ['label'=>'Starting Soon', 'value'=>$stats['booked_soon'], 'color'=>'#f59e0b'],
                ['label'=>'Blocked',       'value'=>$stats['blocked'],     'color'=>'#475569'],
            ];
        @endphp
        @foreach($kpis as $kpi)
            <div class="bg-white rounded-2xl p-5 border-t-4 shadow-sm hover:shadow-md transition-shadow"
                 style="border-top-color:{{ $kpi['color'] }};">
                <p class="text-[10px] font-black uppercase tracking-widest" style="color:{{ $kpi['color'] }};">{{ $kpi['label'] }}</p>
                <p class="text-2xl font-black mt-1" style="color:{{ $kpi['color'] }};">{{ $kpi['value'] }}</p>
            </div>
        @endforeach
    </div>

    {{-- ── Legend ──────────────────────────────────────────────────── --}}
    <div class="flex flex-wrap items-center gap-5 px-1">
        <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Status Key:</span>
        @foreach([
            ['color'=>'#068930','label'=>'Available'],
            ['color'=>'#f59e0b','label'=>'Starting Soon'],
            ['color'=>'#e11d48','label'=>'Occupied'],
            ['color'=>'#475569','label'=>'Blocked / Maintenance'],
        ] as $leg)
            <div class="flex items-center gap-1.5">
                <span class="w-3 h-3 rounded-full inline-block" style="background:{{ $leg['color'] }};"></span>
                <span class="text-xs font-semibold text-slate-600">{{ $leg['label'] }}</span>
            </div>
        @endforeach
    </div>

    {{-- ── Stall Grid (Grouped by Zone) ────────────────────────────── --}}
    @php $zoneGroups = $stalls->groupBy('zone'); @endphp
    @forelse($zoneGroups as $zoneName => $zoneStalls)
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">

            {{-- Zone Header --}}
            <div class="px-6 py-3 flex items-center justify-between border-b border-gray-100"
                 style="background:linear-gradient(90deg,#f0f7ff,#fff);">
                <div class="flex items-center gap-2">
                    <div class="w-1 h-5 rounded-full" style="background:#0F47AF;"></div>
                    <h3 class="text-xs font-black uppercase tracking-widest" style="color:#0a3282;">
                        {{ $zoneName ?? 'Unassigned Zone' }}
                    </h3>
                    <span class="text-[10px] font-black px-2.5 py-0.5 rounded-lg ml-1"
                          style="background:#eff6ff;color:#0F47AF;border:1px solid #bfdbfe;">
                        {{ $zoneStalls->count() }} stalls
                    </span>
                </div>
                <span class="text-[10px] font-bold text-slate-400">
                    {{ $zoneStalls->where('is_blocked', false)->filter(fn($s) => $s->availability->can_book)->count() }} bookable
                </span>
            </div>

            {{-- Stall Rows --}}
            <div class="divide-y divide-gray-50">
                @foreach($zoneStalls as $stall)
                    @php
                        $av = $stall->availability;

                        // Determine row styling based on status
                        if ($stall->is_blocked) {
                            $rowBg    = 'bg-slate-50';
                            $dotColor = '#475569';
                            $badge    = ['bg'=>'#f1f5f9','text'=>'#475569','border'=>'#cbd5e1','label'=> $stall->status === 'maintenance' ? '🔧 Under Maintenance' : '🚫 Blocked'];
                        } elseif ($av->status === 'occupied') {
                            $rowBg    = 'bg-rose-50/30';
                            $dotColor = '#e11d48';
                            $badge    = ['bg'=>'#fff1f2','text'=>'#c0192d','border'=>'#fecdd3','label'=>'🔴 Occupied'];
                        } elseif ($av->status === 'booked_soon') {
                            $rowBg    = 'bg-amber-50/30';
                            $dotColor = '#f59e0b';
                            $badge    = ['bg'=>'#fffbeb','text'=>'#92400e','border'=>'#fde68a','label'=>'🟡 Starting Soon'];
                        } else {
                            $rowBg    = '';
                            $dotColor = '#068930';
                            $badge    = ['bg'=>'#f0faf3','text'=>'#065f46','border'=>'#a7f3d0','label'=>'🟢 Available'];
                        }
                    @endphp

                    <div class="px-6 py-4 flex flex-col lg:flex-row lg:items-center gap-4 {{ $rowBg }} transition-colors hover:bg-blue-50/20">

                        {{-- Stall identity --}}
                        <div class="flex items-center gap-3 min-w-[120px]">
                            <span class="w-2.5 h-2.5 rounded-full shrink-0" style="background:{{ $dotColor }};"></span>
                            <div>
                                <p class="font-black text-slate-800 text-sm">#{{ $stall->stall_number }}</p>
                                <p class="text-[10px] text-slate-400">{{ $stall->location_desc ?? '—' }}</p>
                            </div>
                        </div>

                        {{-- Status badge --}}
                        <span class="px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider border shrink-0"
                              style="background:{{ $badge['bg'] }};color:{{ $badge['text'] }};border-color:{{ $badge['border'] }};">
                            {{ $badge['label'] }}
                        </span>

                        {{-- Availability message --}}
                        <div class="flex-1 min-w-0">
                            @if($stall->is_blocked)
                                <p class="text-xs font-bold text-slate-600 truncate">
                                    <span class="font-black text-slate-800">Reason:</span>
                                    {{ $stall->blocked_reason }}
                                </p>
                                @if($stall->blocked_at)
                                    <p class="text-[10px] text-slate-400 mt-0.5">
                                        Blocked {{ $stall->blocked_at->diffForHumans() }}
                                    </p>
                                @endif
                            @else
                                <p class="text-[11px] text-slate-500">{{ $av->message }}</p>
                                @if($av->detail)
                                    <p class="text-[10px] text-slate-400 mt-0.5">{{ $av->detail }}</p>
                                @endif
                            @endif
                        </div>

                        {{-- Price --}}
                        <div class="shrink-0 text-right hidden md:block">
                            <p class="text-[10px] font-bold text-slate-400 uppercase">Daily Rate</p>
                            <p class="text-sm font-black" style="color:#0F47AF;">KES {{ number_format($stall->price) }}</p>
                        </div>

                        {{-- ── Action Panel ── --}}
                        <div class="flex items-center gap-2 shrink-0 flex-wrap">

                            @if($stall->is_blocked)
                                {{-- UNBLOCK button --}}
                                <form action="{{ route('admin.stalls.unblock', $stall->id) }}"
                                      method="POST"
                                      onsubmit="return confirm('Unblock Stall #{{ $stall->stall_number }}? It will immediately become bookable again.')">
                                    @csrf
                                    <button type="submit"
                                            class="flex items-center gap-1.5 px-4 py-2 rounded-xl text-white font-black text-[10px] uppercase tracking-wider transition-all hover:scale-105 active:scale-95"
                                            style="background:linear-gradient(135deg,#065f26,#068930);box-shadow:0 3px 10px rgba(6,137,48,.25);">
                                        ✅ Unblock Stall
                                    </button>
                                </form>

                            @else
                                {{-- BLOCK button — opens inline form --}}
                                <button type="button"
                                        onclick="toggleBlockForm('block-form-{{ $stall->id }}')"
                                        class="flex items-center gap-1.5 px-4 py-2 rounded-xl font-black text-[10px] uppercase tracking-wider transition-all hover:scale-105 active:scale-95"
                                        style="background:#fee2e2;color:#b91c1c;border:1px solid #fecaca;">
                                    🚫 Block Stall
                                </button>

                                {{-- MAINTENANCE shortcut --}}
                                <button type="button"
                                        onclick="toggleBlockForm('maint-form-{{ $stall->id }}')"
                                        class="flex items-center gap-1.5 px-4 py-2 rounded-xl font-black text-[10px] uppercase tracking-wider transition-all hover:scale-105 active:scale-95"
                                        style="background:#fef9c3;color:#92400e;border:1px solid #fde68a;">
                                    🔧 Maintenance
                                </button>
                            @endif
                        </div>
                    </div>

                    {{-- ── Inline Block Form (hidden by default) ── --}}
                    @if(!$stall->is_blocked)
                        <div id="block-form-{{ $stall->id }}"
                             class="hidden px-6 pb-5 pt-2"
                             style="background:#fef2f2;border-top:1px dashed #fecaca;">
                            <form action="{{ route('admin.stalls.block', $stall->id) }}" method="POST"
                                  class="flex flex-col sm:flex-row gap-3 items-start sm:items-end">
                                @csrf
                                <div class="flex-1">
                                    <label class="block text-[10px] font-black uppercase tracking-wider text-rose-700 mb-1">
                                        Reason for blocking <span class="text-rose-500">*</span>
                                    </label>
                                    <input type="text"
                                           name="blocked_reason"
                                           required
                                           minlength="5"
                                           placeholder="e.g. Structural inspection required — unsafe for occupation"
                                           class="w-full px-4 py-2.5 rounded-xl border text-sm font-medium focus:outline-none focus:ring-2 focus:ring-rose-400"
                                           style="border-color:#fecaca;background:#fff;">
                                </div>
                                <div class="flex gap-2 shrink-0">
                                    <button type="submit"
                                            class="px-5 py-2.5 rounded-xl text-white font-black text-[10px] uppercase tracking-wider transition-all hover:scale-105"
                                            style="background:#b91c1c;">
                                        Confirm Block
                                    </button>
                                    <button type="button"
                                            onclick="toggleBlockForm('block-form-{{ $stall->id }}')"
                                            class="px-4 py-2.5 rounded-xl font-black text-[10px] uppercase tracking-wider"
                                            style="background:#f1f5f9;color:#475569;">
                                        Cancel
                                    </button>
                                </div>
                            </form>
                        </div>

                        {{-- ── Inline Maintenance Form ── --}}
                        <div id="maint-form-{{ $stall->id }}"
                             class="hidden px-6 pb-5 pt-2"
                             style="background:#fffbeb;border-top:1px dashed #fde68a;">
                            <form action="{{ route('admin.stalls.maintenance', $stall->id) }}" method="POST"
                                  class="flex flex-col sm:flex-row gap-3 items-start sm:items-end">
                                @csrf
                                <div class="flex-1">
                                    <label class="block text-[10px] font-black uppercase tracking-wider text-amber-700 mb-1">
                                        Maintenance Reason <span class="text-amber-500">*</span>
                                    </label>
                                    <input type="text"
                                           name="blocked_reason"
                                           required
                                           minlength="5"
                                           placeholder="e.g. Roof repair — estimated 3 days downtime"
                                           class="w-full px-4 py-2.5 rounded-xl border text-sm font-medium focus:outline-none focus:ring-2 focus:ring-amber-400"
                                           style="border-color:#fde68a;background:#fff;">
                                </div>
                                <div class="flex gap-2 shrink-0">
                                    <button type="submit"
                                            class="px-5 py-2.5 rounded-xl text-white font-black text-[10px] uppercase tracking-wider transition-all hover:scale-105"
                                            style="background:#92400e;">
                                        Mark Maintenance
                                    </button>
                                    <button type="button"
                                            onclick="toggleBlockForm('maint-form-{{ $stall->id }}')"
                                            class="px-4 py-2.5 rounded-xl font-black text-[10px] uppercase tracking-wider"
                                            style="background:#f1f5f9;color:#475569;">
                                        Cancel
                                    </button>
                                </div>
                            </form>
                        </div>
                    @endif

                @endforeach
            </div>

            {{-- County stripe footer --}}
            <div class="h-1" style="background:linear-gradient(90deg,#0F47AF 0%,#0F47AF 33%,#fff 33%,#fff 66%,#068930 66%);"></div>
        </div>
    @empty
        <div class="py-24 text-center bg-white rounded-2xl border-2 border-dashed border-gray-200">
            <div class="text-5xl mb-4 opacity-20">🏪</div>
            <p class="font-black uppercase tracking-widest text-slate-400">No stalls found in the system.</p>
        </div>
    @endforelse

    {{-- County attribution --}}
    <p class="text-center text-[10px] font-bold uppercase tracking-[.2em] pb-2" style="color:#94a3b8;">
        Nairobi City County &bull; Muthurwa Market System &bull; &copy; {{ date('Y') }}
    </p>

</div>

<script>
function toggleBlockForm(id) {
    const el = document.getElementById(id);
    if (!el) return;
    el.classList.toggle('hidden');
    if (!el.classList.contains('hidden')) {
        el.querySelector('input[name="blocked_reason"]')?.focus();
    }
}
</script>
@endsection
