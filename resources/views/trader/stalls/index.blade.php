@extends('layouts.app')

@section('page-title', 'Find a Stall')

@section('content')
<div class="space-y-6">

    {{-- ── 1. Hero Header — Forest Green ── --}}
    <div class="relative overflow-hidden rounded-2xl shadow-xl border-b-4 border-[#FCDD07]"
         style="background: linear-gradient(135deg, #1B4332 0%, #1e5c3e 45%, #163d2c 100%);">
        
        {{-- Skyline Watermark --}}
        <svg class="absolute bottom-0 left-0 w-full opacity-[0.08] pointer-events-none" viewBox="0 0 800 120" fill="#FFFFFF">
            <path d="M0 120V100H20V80H40V100H60V70H80V100H100V50H130V100H150V65H180V100H200V30H240V100H260V75H290V100H310V45H350V100H370V70H400V100H420V20H460V100H480V60H510V100H530V40H570V100H590V70H620V100H640V55H680V100H700V80H730V100H750V65H780V100H800V120H0Z"/>
        </svg>

        <div class="relative z-10 flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6 px-8 py-7">
            <div>
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-12 h-12 bg-white/20 backdrop-blur-xl rounded-2xl flex items-center justify-center text-2xl shadow-xl border border-white/25">🦁</div>
                    <span class="text-[10px] font-black uppercase tracking-[0.45em] text-[#FCDD07] bg-black/20 px-3 py-1 rounded-full border border-white/10">Nairobi City County</span>
                </div>
                <h2 class="text-3xl font-black text-white tracking-tight leading-tight">Find a Stall</h2>
                <p class="text-white/70 font-medium text-sm mt-1">Muthurwa Market — Green City in the Sun</p>
            </div>

            <div class="flex flex-col items-end gap-3 w-full lg:w-auto">
                {{-- Live Counter — Yellow focal point --}}
                @php $availCount = $stalls->filter(fn($s) => $s->getSmartAvailability()->can_book)->count(); @endphp
                <div class="flex items-center gap-4 px-6 py-3 rounded-2xl border-2 border-[#FCDD07]/40 shadow-xl"
                     style="background: rgba(252,221,7,0.15); backdrop-filter: blur(12px);">
                    <span class="relative flex h-3 w-3 shrink-0">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#FCDD07] opacity-80"></span>
                        <span class="relative inline-flex rounded-full h-3 w-3 bg-[#FCDD07]"></span>
                    </span>
                    <p class="text-xs font-black text-white uppercase tracking-widest leading-none">
                        <span class="text-[#FCDD07] text-2xl mr-1">{{ $availCount }}</span> Slots Remaining
                    </p>
                </div>

                {{-- Search --}}
                <div class="relative w-full lg:w-80">
                    <input type="text" id="stallSearch" placeholder="Enter Stall # (e.g. 102)..."
                           class="w-full pl-10 pr-4 py-3 rounded-xl border-2 text-white placeholder-white/40 font-semibold text-sm focus:outline-none transition-all backdrop-blur-md"
                           style="background: rgba(255,255,255,0.1); border-color: rgba(252,221,7,0.3);">
                    <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-[#FCDD07] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Legend (Status Key) ── --}}
    <div class="flex flex-wrap items-center gap-4 px-1">
        <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Status Key:</span>
        <div class="flex items-center gap-1.5">
            <span class="w-3 h-3 rounded-full bg-[#068930] inline-block"></span>
            <span class="text-xs font-semibold text-[#333333]">Available</span>
        </div>
        <div class="flex items-center gap-1.5">
            <span class="w-3 h-3 rounded-full bg-amber-500 inline-block"></span>
            <span class="text-xs font-semibold text-[#333333]">Reserved Soon</span>
        </div>
        <div class="flex items-center gap-1.5">
            <span class="w-3 h-3 rounded-full bg-rose-500 inline-block"></span>
            <span class="text-xs font-semibold text-[#333333]">Occupied</span>
        </div>
        <div class="flex items-center gap-1.5">
            <span class="w-3 h-3 rounded-full bg-slate-400 inline-block"></span>
            <span class="text-xs font-semibold text-[#333333]">Blocked / Maintenance</span>
        </div>
    </div>

    {{-- ── 2. Filter & Sort ── --}}
    <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        {{-- Zone filter (underline tab style) --}}
        <div class="flex items-center gap-1 overflow-x-auto no-scrollbar border-b border-[#E0E0E0] w-full md:w-auto" id="zoneFilters">
            <button class="zone-btn tab-underline tab-active px-5 py-2.5 text-[11px] font-black uppercase tracking-widest text-[#0F47AF] whitespace-nowrap border-b-[3px] border-[#0F47AF]"
                    data-zone="all">All Zones</button>
            @foreach([
                'Zone A' => 'Zone 1',
                'Zone B' => 'Zone 2',
                'Zone C' => 'Zone 3',
                'Zone D' => 'Zone 4',
                'Zone E' => 'Zone 5'
            ] as $dbZone => $displayName)
                <button class="zone-btn tab-underline px-5 py-2.5 text-[11px] font-black uppercase tracking-widest text-slate-400 whitespace-nowrap hover:text-[#0F47AF]"
                        data-zone="{{ $dbZone }}">{{ $displayName }}</button>
            @endforeach
        </div>

        <div class="flex items-center gap-3 bg-white px-4 py-2.5 rounded-xl border border-[#E0E0E0] shadow-sm shrink-0">
            <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Sort:</span>
            <select id="priceSort" class="bg-transparent text-xs font-black text-[#333333] focus:outline-none cursor-pointer">
                <option value="default">Standard</option>
                <option value="low">Lowest Price First</option>
                <option value="high">Premium First</option>
            </select>
        </div>
    </div>

    {{-- ── 3. Compact Stall Grid ── --}}
    @if($stalls->count() > 0)

        @php
            // Sort by zone name so they appear alphabetically/numerically
            $zoneGroups = $stalls->groupBy('zone')->sortKeys();
            $zoneStyles = [
                'Zone A' => ['dot' => '#0F47AF', 'bg' => '#EFF4FF', 'accent' => 'text-[#0F47AF]', 'label' => 'Zone 1 — Fresh Produce'],
                'Zone B' => ['dot' => '#068930', 'bg' => '#F0FAF3', 'accent' => 'text-[#068930]', 'label' => 'Zone 2 — Cereals & Grains'],
                'Zone C' => ['dot' => '#B45309', 'bg' => '#FFFBEB', 'accent' => 'text-amber-700', 'label' => 'Zone 3 — Clothing & Textiles'],
                'Zone D' => ['dot' => '#8B5CF6', 'bg' => '#F5F3FF', 'accent' => 'text-violet-700', 'label' => 'Zone 4 — Electronics & Hardware'],
                'Zone E' => ['dot' => '#475569', 'bg' => '#F8FAFC', 'accent' => 'text-slate-600', 'label' => 'Zone 5 — General Merchandise'],
            ];
        @endphp

        @foreach($zoneGroups as $zoneName => $zoneStalls)
            @php $zs = $zoneStyles[$zoneName] ?? ['dot' => '#475569', 'bg' => '#F8FAFC', 'accent' => 'text-slate-600', 'label' => $zoneName]; @endphp

            <div class="zone-section rounded-2xl border border-[#E0E0E0] overflow-hidden mb-6" data-zone-group="{{ $zoneName }}">
                {{-- Zone Header --}}
                <div class="flex items-center gap-3 px-5 py-3 border-b border-[#E0E0E0]" style="background-color: {{ $zs['bg'] }}">
                    <span class="w-3 h-3 rounded-full shrink-0" style="background-color: {{ $zs['dot'] }};"></span>
                    <h3 class="text-xs font-black uppercase tracking-widest {{ $zs['accent'] }}">{{ $zs['label'] }}</h3>
                    @php
                        $zoneAvail = $zoneStalls->filter(fn($s) => $s->getSmartAvailability()->can_book)->count();
                    @endphp
                    <div class="ml-auto flex items-center gap-4 text-[10px] font-bold">
                        <span class="text-slate-400">{{ $zoneStalls->count() }} total</span>
                        <span class="text-[#068930] font-black">{{ $zoneAvail }} free</span>
                    </div>
                </div>

                {{-- Compact Card Grid --}}
                <div class="p-4 bg-[#F8F9FA]">
                    <div class="grid gap-3" style="grid-template-columns: repeat(auto-fill, minmax(155px, 1fr));">
                        @foreach($zoneStalls as $stall)
                            @php
                                $availability = $stall->getSmartAvailability();
                                $statusMap = match($availability->status) {
                                    'occupied'    => ['color' => '#e11d48', 'label' => 'Occupied',           'bg' => '#FFF1F2', 'text' => '#c0192d', 'border' => '#FECDD3'],
                                    'booked_soon' => ['color' => '#D97706', 'label' => 'Reserved Soon',      'bg' => '#FFFBEB', 'text' => '#92400E', 'border' => '#FDE68A'],
                                    'blocked'     => ['color' => '#64748b', 'label' => $availability->label, 'bg' => '#F1F5F9', 'text' => '#475569', 'border' => '#CBD5E1'],
                                    default       => ['color' => '#068930', 'label' => 'Available',          'bg' => '#F0FAF3', 'text' => '#065F46', 'border' => '#A7F3D0'],
                                };
                            @endphp

                            <div class="stall-card stall-card-reveal group bg-white rounded-xl border border-[#E0E0E0] flex flex-col overflow-hidden
                                        hover:shadow-xl hover:border-[#0F47AF] hover:-translate-y-1 transition-all duration-300"
                                 data-zone="{{ $stall->zone }}"
                                 data-price="{{ $stall->price }}"
                                 data-search="stall {{ $stall->stall_number }}">

                                {{-- Card Top Colour Strip --}}
                                <div class="h-1.5 w-full" style="background-color: {{ $statusMap['color'] }};"></div>

                                <div class="p-3 flex flex-col gap-2 flex-1">
                                    {{-- Stall ID + Dot --}}
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm font-black text-[#333333]">#{{ $stall->stall_number }}</span>
                                        <span class="w-2.5 h-2.5 rounded-full shrink-0 {{ $availability->status === 'occupied' ? 'animate-pulse' : '' }}"
                                              style="background-color: {{ $statusMap['color'] }};"></span>
                                    </div>

                                    {{-- Location --}}
                                    <p class="text-[10px] text-slate-500 leading-snug line-clamp-2">{{ $stall->location_desc }}</p>

                                    {{-- Amenities Row --}}
                                    <div class="flex items-center gap-2 text-sm">
                                        <span title="Power">⚡</span>
                                        <span title="Water">💧</span>
                                        <span title="Security">🛡️</span>
                                    </div>

                                    {{-- Status Badge --}}
                                    <div class="flex items-center gap-1.5 px-2 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest border"
                                         style="background-color: {{ $statusMap['bg'] }}; color: {{ $statusMap['text'] }}; border-color: {{ $statusMap['border'] }};">
                                        <span class="w-1.5 h-1.5 rounded-full shrink-0" style="background-color: {{ $statusMap['color'] }};"></span>
                                        {{ $statusMap['label'] }}
                                    </div>

                                    {{-- Price --}}
                                    <div class="flex items-baseline gap-0.5 pt-2 border-t border-slate-50 mt-auto">
                                        <span class="text-[9px] font-bold text-slate-400 uppercase">KES</span>
                                        <span class="text-sm font-black text-[#333333]"> {{ number_format($stall->price) }}</span>
                                        <span class="text-[9px] text-slate-400">/day</span>
                                    </div>

                                    {{-- Action --}}
                                    @if($availability->status === 'blocked')
                                        {{-- Admin-blocked: show the reason to trader --}}
                                        <div class="w-full rounded-lg border border-dashed border-slate-300 bg-slate-50 px-2 py-1.5">
                                            <p class="text-[8px] font-black uppercase tracking-wider text-slate-500 mb-0.5">
                                                {{ $availability->label }}
                                            </p>
                                            <p class="text-[9px] text-slate-500 leading-snug line-clamp-2">
                                                {{ $availability->message }}
                                            </p>
                                        </div>
                                    @elseif(!$availability->can_book)
                                        <button disabled
                                                class="w-full py-2 rounded-lg text-[9px] font-black uppercase tracking-widest cursor-not-allowed border border-dashed border-slate-200 text-slate-400 bg-slate-50">
                                            {{ $availability->status === 'occupied' ? 'In Use' : 'Unavailable' }}
                                        </button>
                                    @else
                                        <a href="{{ route('trader.bookings.create', $stall->id) }}"
                                           class="w-full flex items-center justify-center gap-1.5 py-2 rounded-lg text-white text-[9px] font-black uppercase tracking-widest transition-all hover:opacity-90 active:scale-95 shadow-sm"
                                           style="background-color: #068930;">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                            Book &amp; Pay
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach

    @else
        <div class="py-24 text-center bg-white rounded-2xl border-2 border-dashed border-[#E0E0E0]">
            <div class="text-5xl mb-5 opacity-20">🏪</div>
            <h3 class="text-lg font-black text-slate-400 uppercase tracking-widest">No stalls matching your search</h3>
        </div>
    @endif

    {{-- Hidden no-results placeholder --}}
    <div id="noResults" class="hidden py-16 text-center bg-white rounded-2xl border-2 border-dashed border-[#E0E0E0]">
        <div class="text-4xl mb-3 opacity-20">🔍</div>
        <p class="font-black text-slate-400 uppercase tracking-widest text-sm">No stalls match your filter</p>
    </div>

    {{-- ── 4. Footnote ── --}}
    <div class="flex flex-col items-center gap-3 py-8 border-t border-[#E0E0E0]">
        <div class="flex items-center gap-2 text-[10px] font-black text-slate-400 uppercase tracking-widest">
            <div class="w-2 h-2 bg-[#068930] rounded-full"></div>
            Official Nairobi County Real-Time Inventory
            <div class="w-2 h-2 bg-[#0F47AF] rounded-full"></div>
        </div>
        <p class="text-xs font-medium text-slate-400 text-center max-w-md leading-relaxed">
            All bookings are final upon M-Pesa confirmation. Please read the
            <a href="#" class="text-[#068930] hover:underline font-bold">Market Trading Guidelines</a> before proceeding.
        </p>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput  = document.getElementById('stallSearch');
    const zoneBtns     = document.querySelectorAll('.zone-btn');
    const priceSort    = document.getElementById('priceSort');
    const noResults    = document.getElementById('noResults');
    const zoneSections = document.querySelectorAll('.zone-section');
    let allCards       = Array.from(document.querySelectorAll('.stall-card'));
    let currentZone    = 'all';
    let currentSearch  = '';

    function filterAndSort() {
        let anyVisible = false;
        
        zoneSections.forEach(section => {
            const sectionZone = section.dataset.zoneGroup;
            let sectionVisible = false;
            
            // Filter all cards inside this zone section
            const cards = section.querySelectorAll('.stall-card');
            cards.forEach(card => {
                const zoneMatch   = currentZone === 'all' || card.dataset.zone === currentZone;
                const searchMatch = currentSearch === '' || card.dataset.search.includes(currentSearch);
                
                if (zoneMatch && searchMatch) {
                    card.style.display = '';
                    sectionVisible = true;
                    anyVisible = true;
                } else {
                    card.style.display = 'none';
                }
            });
            
            // Hide the entire section if no cards match the filters
            section.style.display = sectionVisible ? '' : 'none';
        });

        // Toggle friendly no-results message dynamically
        if (anyVisible) {
            noResults.classList.add('hidden');
        } else {
            noResults.classList.remove('hidden');
            const msgEl = noResults.querySelector('p');
            if (msgEl) {
                if (currentSearch !== '') {
                    msgEl.textContent = 'No stalls match your search';
                } else {
                    msgEl.textContent = 'No stalls available in this zone';
                }
            }
        }

        // Sort within each grid
        if (priceSort.value !== 'default') {
            zoneSections.forEach(section => {
                if (section.style.display === 'none') return;
                const grid = section.querySelector('[style*="grid-template-columns"]');
                if (!grid) return;
                const visible = Array.from(grid.querySelectorAll('.stall-card')).filter(c => c.style.display !== 'none');
                visible.sort((a, b) => {
                    const pA = parseFloat(a.dataset.price), pB = parseFloat(b.dataset.price);
                    return priceSort.value === 'low' ? pA - pB : pB - pA;
                });
                visible.forEach(c => grid.appendChild(c));
            });
        }
    }

    searchInput?.addEventListener('input', e => {
        currentSearch = e.target.value.toLowerCase().trim();
        filterAndSort();
    });

    priceSort?.addEventListener('change', filterAndSort);

    zoneBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            zoneBtns.forEach(b => {
                b.classList.remove('tab-active', 'text-[#0F47AF]', 'border-[#0F47AF]');
                b.classList.add('text-slate-400', 'border-transparent');
                b.style.borderBottomColor = 'transparent';
                b.style.color = '';
            });
            btn.classList.add('tab-active');
            btn.style.borderBottomColor = '#0F47AF';
            btn.style.color = '#0F47AF';
            currentZone = btn.dataset.zone;
            filterAndSort();
        });
    });
});
</script>
@endsection
