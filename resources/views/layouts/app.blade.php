<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Smart Stall Booking') }}</title>

    <!-- Fonts: Switched to a more stable format to avoid CORB -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net" rel="stylesheet" />

    <!-- 1. FIXED APEXCHARTS CDN (Cloudflare is more reliable for CORB) -->
    <script src="https://cdnjs.cloudflare.com" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans bg-slate-50 antialiased text-slate-900">

<div class="flex min-h-screen">

    <!-- Modern Sidebar -->
    <aside class="w-64 bg-gradient-to-b from-blue-900 to-indigo-950 text-white hidden md:flex flex-col shadow-2xl">
        <div class="p-8 pb-4">
            <h1 class="text-xl font-extrabold tracking-wider bg-clip-text text-transparent bg-gradient-to-r from-white to-blue-300 uppercase leading-none">
                Stall System
            </h1>
            <p class="text-[10px] text-blue-300/50 font-bold tracking-widest mt-1 uppercase">Muthurwa Portal</p>
        </div>

        <nav class="flex-1 px-4 py-6 space-y-1">
            @auth
                @php 
                    $linkStyle = "flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 hover:bg-white/10 hover:translate-x-1";
                    $activeStyle = "bg-white/15 shadow-inner text-white border-l-4 border-blue-400";
                @endphp

                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}" class="{{ $linkStyle }} {{ request()->routeIs('admin.dashboard') ? $activeStyle : 'text-blue-100/70' }}">Dashboard</a>
                    <a href="{{ route('admin.stalls.index') }}" class="{{ $linkStyle }} {{ request()->routeIs('admin.stalls.*') ? $activeStyle : 'text-blue-100/70' }}">Manage Stalls</a>
                @endif

                @if(auth()->user()->role === 'trader')
                    <a href="{{ route('trader.dashboard') }}" class="{{ $linkStyle }} {{ request()->routeIs('trader.dashboard') ? $activeStyle : 'text-blue-100/70' }}">Dashboard</a>
                    <a href="{{ route('trader.stalls.index') }}" class="{{ $linkStyle }} {{ request()->routeIs('trader.stalls.*') ? $activeStyle : 'text-blue-100/70' }}">View Stalls</a>
                    <a href="{{ route('trader.bookings.index') }}" class="{{ $linkStyle }} {{ request()->routeIs('trader.bookings.*') ? $activeStyle : 'text-blue-100/70' }}">My Bookings</a>
                @endif
            @endauth
        </nav>

        <div class="p-4 border-t border-white/10 bg-black/10">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="w-full flex items-center justify-center gap-2 bg-red-500/10 hover:bg-red-500 text-red-400 hover:text-white py-2.5 rounded-xl text-xs font-bold transition-all border border-red-500/20 active:scale-95">
                    Sign Out
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col overflow-hidden">
        <header class="bg-white/80 backdrop-blur-md sticky top-0 z-30 px-8 py-4 flex justify-between items-center border-b border-slate-200">
            <h2 class="text-lg font-bold text-slate-800 tracking-tight">@yield('page-title', 'Overview')</h2>
            <div class="flex items-center gap-4">
                <div class="h-10 w-10 rounded-full bg-gradient-to-tr from-blue-600 to-indigo-600 flex items-center justify-center text-white font-bold shadow-lg">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </div>
            </div>
        </header>

        <main class="p-8 flex-1 overflow-y-auto bg-slate-50/50">
            <div class="max-w-7xl mx-auto">
                @yield('content')
            </div>
        </main>
    </div>
</div>

@stack('scripts')
</body>
</html>
