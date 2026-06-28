<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Muthurwa Stall Booking') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        *, *::before, *::after { box-sizing: border-box; }

        /* ── Nairobi Official Palette ── */
        :root {
            --nairobi-green:  #068930;
            --nairobi-green-dark: #046122;
            --nairobi-yellow: #FCDD07;
            --nairobi-blue:   #0F47AF;
            --rich-black:     #1A1A1B;
            --text-body:      #333333;
            --bg-page:        #F4F7F6;
            --bg-card:        #FFFFFF;
            --border-soft:    #E0E0E0;
            --gray-subtle:    #F8F9FA;
        }

        body {
            font-family: 'Inter', sans-serif;
            margin: 0;
            background-color: var(--bg-page);
            color: var(--text-body);
        }

        /* ── Sidebar Animated Gradient ── */
        @keyframes sidebar-flow {
            0%   { background-position: 0%   50%; }
            50%  { background-position: 100% 50%; }
            100% { background-position: 0%   50%; }
        }
        .sidebar-bg {
            background: linear-gradient(135deg, #068930, #057529, #0F47AF, #046122);
            background-size: 400% 400%;
            animation: sidebar-flow 15s ease infinite;
            border-right: 5px solid var(--nairobi-yellow);
        }

        /* ── Navigation Links ── */
        .nav-link {
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }
        .nav-link:hover {
            background: rgba(255,255,255,0.12);
            transform: translateX(4px);
        }
        .nav-active {
            background: rgba(252,221,7,0.15) !important;
            color: var(--nairobi-yellow) !important;
            border-left: 4px solid var(--nairobi-yellow);
            font-weight: 900 !important;
        }
        .nav-active .nav-icon {
            color: var(--nairobi-yellow) !important;
            transform: scale(1.1);
        }

        /* ── Micro-animations ── */
        @keyframes bounce-subtle {
            0%, 100% { transform: translateY(0); }
            50%       { transform: translateY(-3px); }
        }
        .bounce-on-hover:hover { animation: bounce-subtle 0.6s ease infinite; }

        /* ── Scrollbar ── */
        ::-webkit-scrollbar       { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: var(--nairobi-green); border-radius: 10px; }

        /* ── Glassmorphism ── */
        .glass-card {
            background: rgba(255,255,255,0.85);
            backdrop-filter: blur(14px);
            border: 1px solid rgba(255,255,255,0.4);
        }

        /* ── Underline Tab Style ── */
        .tab-underline {
            border-bottom: 3px solid transparent;
            transition: all 0.25s ease;
        }
        .tab-underline.tab-active {
            border-bottom-color: var(--nairobi-blue);
            color: var(--nairobi-blue);
        }

        /* ── Progress bar colors ── */
        .progress-green  { background: #068930; }
        .progress-yellow { background: #FCDD07; }
        .progress-red    { background: #e11d48; }

        /* ── Card reveal animation ── */
        .stall-card-reveal {
            opacity: 0;
            transform: translateY(18px);
            transition: opacity 0.45s ease, transform 0.45s ease;
        }
        .stall-card-reveal.card-visible {
            opacity: 1;
            transform: translateY(0);
        }

        /* ── No-scrollbar utility ── */
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>

<body class="antialiased">
<div class="flex min-h-screen">

    <!-- ══ SIDEBAR ════════════════════════════════════════════ -->
    <aside class="sidebar-bg w-64 text-white hidden md:flex flex-col shadow-2xl relative z-40 shrink-0">

        <!-- Dot Pattern -->
        <div class="absolute inset-0 opacity-5 pointer-events-none"
             style="background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 0); background-size: 22px 22px;"></div>

        <!-- Brand Header -->
        <div class="p-6 pb-4 relative z-10">
            <div class="flex items-center gap-3 mb-5 group cursor-pointer">
                <div class="w-12 h-12 bg-white/20 rounded-2xl backdrop-blur-xl border border-white/30 flex items-center justify-center shadow-xl group-hover:rotate-12 transition-transform duration-500">
                    <span class="text-2xl">🦁</span>
                </div>
                <div>
                    <h1 class="text-base font-black tracking-tighter leading-none uppercase">Muthurwa</h1>
                    <p class="text-[10px] text-emerald-100/60 font-black tracking-[0.2em] uppercase mt-0.5">Digital Portal</p>
                </div>
            </div>

            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-black/20 backdrop-blur-md border border-white/10">
                <span class="w-2 h-2 rounded-full bg-[#FCDD07] animate-pulse"></span>
                <span class="text-[9px] font-black uppercase tracking-widest text-[#FCDD07]">Nairobi County System</span>
            </div>
        </div>

        <!-- Main Navigation -->
        <nav class="flex-1 px-3 py-4 space-y-1 relative z-10 overflow-y-auto no-scrollbar">
            @auth
                @php
                    $navBase = "nav-link group flex items-center gap-3 px-5 py-3.5 text-[12px] font-bold rounded-xl transition-all text-emerald-50 hover:text-white";
                    $icons = [
                        'Dashboard'    => '<svg class="nav-icon w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>',
                        'Manage Stalls'=> '<svg class="nav-icon w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>',
                        'Feedback'     => '<svg class="nav-icon w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>',
                        'View Stalls'  => '<svg class="nav-icon w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>',
                        'My Bookings'  => '<svg class="nav-icon w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>',
                        'Officer'      => '<svg class="nav-icon w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>',
                    ];
                @endphp

                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}"    class="{{ $navBase }} {{ request()->routeIs('admin.dashboard')   ? 'nav-active' : '' }}">{!! $icons['Dashboard']     !!} Dashboard</a>
                    <a href="{{ route('admin.stalls.index') }}" class="{{ $navBase }} {{ request()->routeIs('admin.stalls.*')    ? 'nav-active' : '' }}">{!! $icons['Manage Stalls'] !!} Stalls Inventory</a>
                    <a href="{{ route('admin.feedback.index') }}"class="{{ $navBase }} {{ request()->routeIs('admin.feedback.*') ? 'nav-active' : '' }}">{!! $icons['Feedback']      !!} Trader Feedback</a>
                @endif

                @if(auth()->user()->role === 'officer')
                    <a href="{{ route('officer.dashboard') }}"  class="{{ $navBase }} {{ request()->routeIs('officer.*') ? 'nav-active' : '' }}">{!! $icons['Officer'] !!} Officer Dashboard</a>
                @endif

                @if(auth()->user()->role === 'trader')
                    <a href="{{ route('trader.dashboard') }}"      class="{{ $navBase }} {{ request()->routeIs('trader.dashboard')  ? 'nav-active' : '' }}">{!! $icons['Dashboard']   !!} Market Overview</a>
                    <a href="{{ route('trader.stalls.index') }}"   class="{{ $navBase }} {{ request()->routeIs('trader.stalls.*')   ? 'nav-active' : '' }}">{!! $icons['View Stalls'] !!} Find a Stall</a>
                    <a href="{{ route('trader.bookings.index') }}" class="{{ $navBase }} {{ request()->routeIs('trader.bookings.*') ? 'nav-active' : '' }}">{!! $icons['My Bookings'] !!} My Reservations</a>
                @endif
            @endauth
        </nav>

        <!-- Footer / User Session -->
        <div class="p-5 mt-auto relative z-10 border-t border-white/10 bg-black/10">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-9 h-9 rounded-xl bg-[#FCDD07] flex items-center justify-center font-black text-[#068930] text-sm shadow-lg">
                    {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-black text-white truncate leading-tight">{{ auth()->user()->name ?? 'Trader' }}</p>
                    <p class="text-[10px] text-emerald-200/50 font-bold uppercase tracking-wider">{{ auth()->user()->role ?? 'User' }}</p>
                </div>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="w-full py-3 rounded-xl bg-white/10 text-white text-[11px] font-black uppercase tracking-widest hover:bg-rose-500 transition-all border border-white/10 flex items-center justify-center gap-2 group">
                    <svg class="w-4 h-4 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    Sign Out
                </button>
            </form>
        </div>
    </aside>

    <!-- ══ MAIN AREA ════════════════════════════════════════════ -->
    <div class="flex-1 flex flex-col min-w-0">

        <!-- Top Navigation Bar -->
        <header class="h-16 glass-card sticky top-0 z-30 px-6 flex justify-between items-center border-b border-slate-200/60 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="w-1 h-6 rounded-full bg-[#068930]"></div>
                <h2 class="text-base font-black text-[#1A1A1B] tracking-tight uppercase">
                    @yield('page-title', 'Market Hub')
                </h2>
            </div>

            <div class="flex items-center gap-4">
                <!-- Local Time -->
                <div class="hidden xl:flex items-center gap-2 bg-[#F4F7F6] px-3 py-1.5 rounded-lg border border-slate-200">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">EAT</span>
                    <span class="text-xs font-black text-[#068930]">{{ now()->format('H:i') }}</span>
                </div>

                <!-- Notification Bell -->
                <button class="w-10 h-10 rounded-xl bg-[#F4F7F6] border border-slate-100 flex items-center justify-center relative hover:bg-slate-100 transition-all group">
                    <svg class="w-4 h-4 text-slate-400 group-hover:text-[#0F47AF] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                    <span class="absolute top-1.5 right-1.5 w-2.5 h-2.5 bg-[#FCDD07] rounded-full border-2 border-white"></span>
                </button>

                <!-- Avatar -->
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-[#068930] to-[#0F47AF] flex items-center justify-center text-white font-black text-sm shadow-md cursor-pointer hover:rotate-6 transition-all">
                    {{ substr(auth()->user()->name ?? 'T', 0, 1) }}
                </div>
            </div>
        </header>

        <!-- Dynamic Content -->
        <main class="flex-1 overflow-y-auto scroll-smooth">
            <div class="max-w-screen-2xl mx-auto p-6 md:p-10">
                @yield('content')
            </div>
        </main>
    </div>

</div>

@stack('scripts')

<script>
(function () {
    /* Card reveal on scroll */
    document.addEventListener('DOMContentLoaded', function () {
        var cards = document.querySelectorAll('.stall-card-reveal');
        if (!cards.length) return;
        var cio = new IntersectionObserver(function (entries) {
            entries.forEach(function (e) {
                if (e.isIntersecting) {
                    var c = e.target;
                    c.style.transitionDelay = (parseInt(c.dataset.cardIdx || 0) * 0.06) + 's';
                    c.classList.add('card-visible');
                    cio.unobserve(c);
                }
            });
        }, { threshold: 0.06 });
        cards.forEach(function (c, i) { c.dataset.cardIdx = i; cio.observe(c); });
    });
})();
</script>

<x-chat-assistant />
</body>
</html>