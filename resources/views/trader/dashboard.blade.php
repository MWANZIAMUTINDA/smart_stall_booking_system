@extends('layouts.app')

@section('page-title', 'Market Overview')

@section('content')
<div class="space-y-6 pb-10">

    {{-- ── Restriction Alert ── --}}
    @if(auth()->user()->account_restriction !== null && auth()->user()->account_restriction !== 'none')
        <div class="p-5 rounded-2xl shadow border-l-8 flex items-start gap-4
            @if(auth()->user()->isBanned()) bg-rose-50 border-rose-500
            @elseif(auth()->user()->isBlocked()) bg-orange-50 border-orange-500
            @else bg-amber-50 border-amber-500 @endif">
            <div class="text-2xl mt-0.5">
                @if(auth()->user()->isBanned()) 🔨
                @elseif(auth()->user()->isBlocked()) 🚫
                @else ⚠️ @endif
            </div>
            <div>
                <h4 class="font-black text-xs uppercase tracking-widest mb-1
                    @if(auth()->user()->isBanned()) text-rose-800
                    @elseif(auth()->user()->isBlocked()) text-orange-800
                    @else text-amber-800 @endif">
                    {{ ucfirst(auth()->user()->account_restriction) }} Alert
                </h4>
                <p class="text-sm font-semibold
                    @if(auth()->user()->isBanned()) text-rose-700
                    @elseif(auth()->user()->isBlocked()) text-orange-700
                    @else text-amber-700 @endif">
                    {{ auth()->user()->restriction_reason ?? 'Please comply with market regulations.' }}
                </p>
                @if(auth()->user()->isBlocked())
                    <p class="text-[10px] font-black text-rose-600 mt-2 uppercase tracking-widest">
                        Booking Access Suspended — Visit Market Office to resolve.
                    </p>
                @endif
            </div>
        </div>
    @endif

    {{-- Success Flash --}}
    @if(session('success'))
        <div class="bg-emerald-50 text-emerald-800 px-6 py-4 rounded-xl border border-emerald-200 font-bold flex items-center gap-3">
            <div class="w-8 h-8 bg-emerald-500 rounded-lg flex items-center justify-center text-white shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
            </div>
            <span class="text-sm">{{ session('success') }}</span>
        </div>
    @endif

    {{-- ── 1. Hero Header — Forest Green ── --}}
    <div class="relative overflow-hidden rounded-2xl shadow-2xl border-b-4 border-[#FCDD07]"
         style="background: linear-gradient(135deg, #1B4332 0%, #1e5c3e 45%, #163d2c 100%);">

        {{-- Subtle dot-grid watermark --}}
        <div class="absolute inset-0 opacity-[0.06] pointer-events-none"
             style="background-image: radial-gradient(circle at 2px 2px, #ffffff 1px, transparent 0); background-size: 28px 28px;"></div>

        {{-- Nairobi skyline silhouette --}}
        <div class="absolute inset-x-0 bottom-0 opacity-[0.08] pointer-events-none">
            <svg viewBox="0 0 800 120" fill="#FFFFFF" class="w-full h-auto">
                <path d="M0 120V100H20V80H40V100H60V70H80V100H100V50H130V100H150V65H180V100H200V30H240V100H260V75H290V100H310V45H350V100H370V70H400V100H420V20H460V100H480V60H510V100H530V40H570V100H590V70H620V100H640V55H680V100H700V80H730V100H750V65H780V100H800V120H0Z"/>
            </svg>
        </div>

        <div class="relative z-10 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-6 px-8 py-7">

            {{-- Left: Branding + Greeting --}}
            <div class="flex items-center gap-5">
                <div class="bg-white/20 p-3.5 rounded-2xl backdrop-blur-xl border border-white/25 shadow-xl shrink-0">
                    <span class="text-3xl block">🦁</span>
                </div>
                <div>
                    <span class="text-[#FCDD07] text-[10px] uppercase tracking-[0.45em] font-black block mb-0.5">Muthurwa Digital Hub</span>
                    <h2 class="text-2xl font-black tracking-tight text-white leading-tight">
                        Habari, {{ explode(' ', auth()->user()->name ?? 'Trader')[0] }}! 👋
                    </h2>
                    <p class="text-white/70 text-xs font-medium mt-1">Find and book your business spot at Muthurwa Market.</p>
                </div>
            </div>

            @php $availCount = $stalls->filter(fn($s) => $s->getSmartAvailability()->can_book)->count(); @endphp

            {{-- Right: Badge + Search --}}
            <div class="flex flex-col items-end gap-3 w-full sm:w-auto">

                {{-- ★ Availability Badge — Yellow focal point ★ --}}
                <div class="flex items-center gap-3 px-5 py-3 rounded-2xl border-2 border-[#FCDD07]/40 shadow-xl"
                     style="background: rgba(252,221,7,0.15); backdrop-filter: blur(12px);">
                    <span class="relative flex h-3 w-3 shrink-0">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#FCDD07] opacity-80"></span>
                        <span class="relative inline-flex rounded-full h-3 w-3 bg-[#FCDD07]"></span>
                    </span>
                    <div>
                        <p class="text-[#FCDD07] text-2xl font-black leading-none">{{ $availCount }}</p>
                        <p class="text-[#FCDD07]/80 text-[9px] font-black uppercase tracking-[0.3em] mt-0.5">Stalls Available</p>
                    </div>
                </div>

                {{-- Search --}}
                <div class="relative w-full sm:w-72">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-[#FCDD07]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <input type="text" id="stallSearch" placeholder="Search stall #..."
                           class="w-full pl-10 pr-4 py-3 rounded-xl border-2 text-sm font-semibold focus:outline-none transition-all backdrop-blur-md text-white placeholder-white/40"
                           style="background: rgba(255,255,255,0.1); border-color: rgba(252,221,7,0.3);">
                </div>
            </div>
        </div>
    </div>

    {{-- ── 2. Filter & Sort Bar ── --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        {{-- Zone Filter Tabs (underline style) --}}
        <div class="flex items-center gap-1 overflow-x-auto no-scrollbar" id="zoneFilters">
            <button class="zone-btn tab-active px-5 py-2.5 text-[11px] font-black uppercase tracking-widest transition-all border-b-2 border-[#068930] text-[#068930] bg-white rounded-t-lg whitespace-nowrap shadow-sm"
                    data-zone="all" id="zone-all">All Zones</button>
            @foreach([
                'Zone A' => 'Zone 1',
                'Zone B' => 'Zone 2',
                'Zone C' => 'Zone 3',
                'Zone D' => 'Zone 4',
                'Zone E' => 'Zone 5'
            ] as $dbZone => $displayName)
                <button class="zone-btn px-5 py-2.5 text-[11px] font-black uppercase tracking-widest transition-all border-b-2 border-transparent text-slate-500 bg-white rounded-t-lg whitespace-nowrap hover:text-[#068930] hover:border-[#068930] shadow-sm"
                        data-zone="{{ $dbZone }}">{{ $displayName }}</button>
            @endforeach
        </div>

        <div class="flex items-center gap-3 bg-white px-4 py-2.5 rounded-xl border border-[#E0E0E0] shadow-sm">
            <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Sort</span>
            <select id="priceSort" class="border-none bg-transparent text-xs font-black text-[#333333] focus:outline-none cursor-pointer">
                <option value="default">Official Order</option>
                <option value="low">Price: Lowest First</option>
                <option value="high">Price: Highest First</option>
            </select>
        </div>
    </div>

    {{-- ── 3. Compact Stall Grid ── --}}
    @if($stalls->count() > 0)

        {{-- Zone Sections --}}
        @php
            // Sort by zone name so they appear alphabetically/numerically
            $zoneGroups = $stalls->groupBy('zone')->sortKeys();
            $zoneStyles = [
                'Zone A' => ['dot' => '#0F47AF', 'bg'  => '#EFF4FF', 'accent' => 'text-[#0F47AF]', 'label' => 'Zone 1 — Fresh Produce'],
                'Zone B' => ['dot' => '#068930', 'bg'  => '#F0FAF3', 'accent' => 'text-[#068930]', 'label' => 'Zone 2 — Cereals & Grains'],
                'Zone C' => ['dot' => '#B45309', 'bg'  => '#FFFBEB', 'accent' => 'text-amber-700', 'label' => 'Zone 3 — Clothing & Textiles'],
                'Zone D' => ['dot' => '#8B5CF6', 'bg'  => '#F5F3FF', 'accent' => 'text-violet-700', 'label' => 'Zone 4 — Electronics & Hardware'],
                'Zone E' => ['dot' => '#475569', 'bg'  => '#F8FAFC', 'accent' => 'text-slate-600', 'label' => 'Zone 5 — General Merchandise'],
            ];
        @endphp

        @foreach($zoneGroups as $zoneName => $zoneStalls)
            @php $zs = $zoneStyles[$zoneName] ?? ['dot' => '#475569', 'bg' => '#F8FAFC', 'accent' => 'text-slate-600', 'label' => $zoneName]; @endphp

            <div class="zone-section rounded-2xl border border-[#E0E0E0] overflow-hidden mb-6" data-zone-group="{{ $zoneName }}">
                {{-- Zone Header --}}
                <div class="flex items-center gap-3 px-5 py-3 border-b border-[#E0E0E0]" style="background-color: {{ $zs['bg'] }};">
                    <span class="w-3 h-3 rounded-full inline-block" style="background-color: {{ $zs['dot'] }};"></span>
                    <h3 class="text-xs font-black uppercase tracking-widest {{ $zs['accent'] }}">{{ $zs['label'] }}</h3>
                    <span class="text-[10px] font-bold text-slate-400 ml-auto">{{ $zoneStalls->count() }} stalls</span>
                </div>

                {{-- Compact Grid --}}
                <div class="p-4 bg-[#F8F9FA]">
                    <div class="grid gap-3" style="grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));" id="stallsGrid">
                        @foreach($zoneStalls as $stall)
                            @php
                                $availability = $stall->getSmartAvailability();
                                $statusDot = match($availability->status) {
                                    'occupied'    => ['color' => '#e11d48', 'label' => 'Occupied',  'bg' => '#FFF1F2', 'text' => 'text-rose-600',  'border' => 'border-rose-100'],
                                    'booked_soon' => ['color' => '#D97706', 'label' => 'Reserved',  'bg' => '#FFFBEB', 'text' => 'text-amber-700', 'border' => 'border-amber-100'],
                                    default       => ['color' => '#068930', 'label' => 'Available', 'bg' => '#F0FAF3', 'text' => 'text-[#068930]', 'border' => 'border-emerald-100'],
                                };
                            @endphp

                            <div class="stall-card stall-card-reveal group bg-white rounded-xl border border-[#E0E0E0] p-3 flex flex-col gap-2
                                        hover:shadow-lg hover:border-[#0F47AF] hover:-translate-y-1 transition-all duration-300 cursor-pointer relative"
                                 data-zone="{{ $stall->zone }}"
                                 data-price="{{ $stall->price }}"
                                 data-search="stall {{ $stall->stall_number }}">

                                {{-- Top Row: Stall ID + Status Dot --}}
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-black text-[#333333] tracking-tight">#{{ $stall->stall_number }}</span>
                                    <span class="w-2.5 h-2.5 rounded-full shrink-0" style="background-color: {{ $statusDot['color'] }};"
                                          title="{{ $statusDot['label'] }}"></span>
                                </div>

                                {{-- Location --}}
                                <p class="text-[10px] text-slate-500 font-medium leading-snug truncate">{{ $stall->location_desc }}</p>

                                {{-- Status Badge --}}
                                <div class="inline-flex items-center gap-1.5 px-2 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest border
                                            {{ $statusDot['text'] }} {{ $statusDot['border'] }}"
                                     style="background-color: {{ $statusDot['bg'] }};">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $availability->status !== 'occupied' ? ($availability->status === 'booked_soon' ? 'bg-amber-500' : 'bg-[#068930]') : 'bg-rose-500 animate-pulse' }}"></span>
                                    {{ $statusDot['label'] }}
                                </div>

                                {{-- Price --}}
                                <div class="flex items-baseline gap-1 mt-auto pt-2 border-t border-slate-50">
                                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-tight">KES</span>
                                    <span class="text-sm font-black text-[#333333]">{{ number_format($stall->price) }}</span>
                                    <span class="text-[9px] text-slate-400">/day</span>
                                </div>

                                {{-- Action Button --}}
                                @if(auth()->user()->isBlocked())
                                    <button disabled class="w-full py-2 bg-slate-100 text-slate-400 text-[9px] font-black uppercase tracking-wider rounded-lg cursor-not-allowed">🔒 Locked</button>
                                @elseif(!$availability->can_book)
                                    <button disabled class="w-full py-2 bg-[#F8F9FA] text-slate-400 text-[9px] font-black uppercase tracking-wider rounded-lg border border-dashed border-slate-200 cursor-not-allowed">Unavailable</button>
                                @else
                                    <a href="{{ route('trader.bookings.create', $stall->id) }}"
                                       class="w-full flex items-center justify-center gap-1.5 py-2 text-white text-[9px] font-black uppercase tracking-wider rounded-lg transition-all hover:opacity-90 active:scale-95 shadow-sm"
                                       style="background-color: #068930;">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                        Book Now
                                    </a>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach

    @else
        <div class="bg-white rounded-2xl border-2 border-dashed border-slate-200 p-20 text-center">
            <span class="text-6xl mb-6 block grayscale opacity-20">🏪</span>
            <h4 class="font-black text-[#333333] text-xl uppercase tracking-widest mb-2">Market is Full</h4>
            <p class="text-slate-400 font-medium text-sm">Check back later for available spots at Muthurwa.</p>
        </div>
    @endif

    {{-- No Results Message (hidden by default, shown by JS) --}}
    <div id="noResults" class="hidden bg-white rounded-2xl border-2 border-dashed border-slate-200 p-14 text-center">
        <span class="text-4xl mb-4 block opacity-30">🔍</span>
        <p class="font-black text-slate-400 uppercase tracking-widest text-sm">No stalls match your search</p>
    </div>

    {{-- ── 4. Trader Voice / Feedback ── --}}
    <div class="bg-white p-8 rounded-2xl shadow-sm border border-[#E0E0E0] relative overflow-hidden group mt-4">
        <div class="absolute -right-16 -bottom-16 w-52 h-52 bg-[#E7F3EF] rounded-full blur-3xl opacity-50 pointer-events-none group-hover:scale-110 transition-transform"></div>

        <div class="flex items-center gap-4 mb-6 relative z-10">
            <div class="w-12 h-12 rounded-2xl bg-[#068930] text-white flex items-center justify-center text-xl shadow-lg shadow-emerald-100">💬</div>
            <div>
                <h3 class="text-lg font-black text-[#333333] tracking-tight">Trader Voice</h3>
                <p class="text-slate-500 font-medium text-xs">Official feedback channel to Nairobi County Admin</p>
            </div>
        </div>

        @if ($errors->any())
            <div class="mb-4 bg-rose-50 border border-rose-100 text-rose-700 px-5 py-3 rounded-xl text-xs font-black uppercase tracking-widest">
                {{ $errors->first('message') }}
            </div>
        @endif

        <form action="{{ route('trader.feedback.store') }}" method="POST" class="relative z-10">
            @csrf
            <div class="mb-5">
                <textarea
                    name="message"
                    id="feedbackMessage"
                    maxlength="160"
                    rows="3"
                    class="w-full border-2 border-[#E0E0E0] rounded-xl bg-[#F8F9FA] focus:bg-white focus:outline-none focus:border-[#068930] transition-all font-medium p-5 text-sm placeholder-slate-300 text-[#333333]"
                    placeholder="Got an idea or issue? Let us know... (Max 160 chars)"
                    required>{{ old('message') }}</textarea>

                <div class="flex items-center gap-4 mt-3 px-1">
                    <div class="h-1.5 w-full bg-slate-100 rounded-full overflow-hidden shadow-inner flex-1">
                        <div id="charBar" class="h-full transition-all duration-300 rounded-full" style="background:#068930; width: 0%"></div>
                    </div>
                    <span class="text-[11px] font-black text-slate-400 whitespace-nowrap">
                        <span id="charCount" class="text-[#068930]">0</span> / 160
                    </span>
                </div>
            </div>

            <button type="submit"
                    class="px-8 py-3 rounded-xl text-white text-xs font-black uppercase tracking-widest transition-all hover:scale-[1.03] active:scale-95 flex items-center gap-3 shadow-lg shadow-emerald-100"
                    style="background:#068930;">
                <span>Send Message</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </button>
        </form>
    </div>

    {{-- ── 5. Footer ── --}}
    <div class="pt-8 pb-4 flex flex-col items-center gap-3 border-t border-[#E0E0E0]">
        <div class="flex items-center gap-3 text-[10px] font-black uppercase tracking-widest text-slate-400">
            <svg class="w-4 h-4 text-[#068930]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            Nairobi City County Verified Portal
        </div>
        <div class="flex gap-6">
            <a href="#" class="text-[10px] font-bold text-[#0F47AF] hover:underline uppercase tracking-widest">Booking Terms</a>
            <span class="text-slate-200">•</span>
            <a href="#" class="text-[10px] font-bold text-[#0F47AF] hover:underline uppercase tracking-widest">Market Regulations</a>
        </div>
        <p class="text-[9px] text-slate-300 font-bold uppercase tracking-widest">Built for Prosperity · © {{ date('Y') }} Muthurwa Digital</p>
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {

    const searchInput  = document.getElementById('stallSearch');
    const zoneBtns     = document.querySelectorAll('.zone-btn');
    const priceSort    = document.getElementById('priceSort');
    const noResults    = document.getElementById('noResults');
    const zoneSections = document.querySelectorAll('.zone-section');

    let allCards    = Array.from(document.querySelectorAll('.stall-card'));
    let currentZone = 'all';
    let currentSearch = '';

    /* ── Filter & Sort ── */
    function filterAndSort() {
        let anyVisible = false;

        zoneSections.forEach(section => {
            const sZone = section.dataset.zoneGroup;
            let sectionVisible = false;

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
        if (noResults) {
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
        }

        // Price sort within each visible grid
        if (priceSort.value !== 'default') {
            document.querySelectorAll('.zone-section').forEach(section => {
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
        btn.addEventListener('click', e => {
            zoneBtns.forEach(b => {
                b.classList.remove('tab-active', 'text-[#068930]', 'border-[#068930]');
                b.classList.add('text-slate-500', 'border-transparent');
            });
            btn.classList.add('tab-active', 'text-[#068930]', 'border-[#068930]');
            btn.classList.remove('text-slate-500', 'border-transparent');
            currentZone = btn.dataset.zone;
            filterAndSort();
        });
    });

    /* ── Feedback Char Count ── */
    const textarea  = document.getElementById('feedbackMessage');
    const charCount = document.getElementById('charCount');
    const charBar   = document.getElementById('charBar');

    if (textarea && charCount && charBar) {
        const update = () => {
            const len = textarea.value.length;
            charCount.textContent = len;
            charBar.style.width   = `${(len / 160) * 100}%`;
            if (len >= 150) {
                charCount.style.color = '#e11d48';
                charBar.style.background = '#e11d48';
            } else {
                charCount.style.color = '#068930';
                charBar.style.background = '#068930';
            }
        };
        update();
        textarea.addEventListener('input', update);
    }
});
</script>
@endsection