<!-- resources/views/welcome.blade.php -->
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Stall System</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gradient-to-br from-blue-900 via-blue-800 to-indigo-900 font-sans min-h-screen text-white selection:bg-blue-400">

    <!-- Compact Modern Header -->
    <header class="max-w-6xl mx-auto py-12 px-6 text-center">
        <h1 class="text-5xl md:text-6xl font-extrabold mb-4 tracking-tight bg-clip-text text-transparent bg-gradient-to-r from-white to-blue-200">
            Muthurwa Stall booking System
        </h1>
        <p class="text-lg md:text-xl text-blue-100/80 mb-8 max-w-2xl mx-auto">
            The digital pulse of Nairobi's trade. Efficient stall bookings and market management in one place.
        </p>
        
        <div class="flex flex-wrap justify-center gap-4">
            <a href="{{ route('login') }}" class="px-8 py-3 bg-white text-blue-900 font-bold rounded-xl shadow-lg hover:scale-105 hover:bg-blue-50 transition-all active:scale-95">
                Login
            </a>
            <a href="{{ route('register') }}" class="px-8 py-3 bg-blue-500/20 backdrop-blur-md border border-white/30 text-white font-bold rounded-xl hover:bg-white/20 transition-all active:scale-95">
                Create Account
            </a>
        </div>
    </header>

    <!-- Glassmorphic Grid -->
    <main class="max-w-6xl mx-auto px-6 pb-16 grid md:grid-cols-3 gap-6">
        
        <!-- About Card -->
        <div class="md:col-span-2 bg-white/10 backdrop-blur-xl border border-white/20 p-8 rounded-3xl shadow-2xl">
            <h2 class="text-2xl font-bold mb-4 text-blue-300">Market Modernized</h2>
            <p class="text-blue-50/80 leading-relaxed mb-4">
                We've replaced messy paperwork with a streamlined digital dashboard. Whether you're a vendor looking for space or an officer managing the market, everything is now instant.
            </p>
            <div class="flex gap-2">
                <span class="px-3 py-1 bg-blue-400/20 rounded-full text-xs font-medium text-blue-200 uppercase tracking-widest">Efficiency</span>
                <span class="px-3 py-1 bg-green-400/20 rounded-full text-xs font-medium text-green-200 uppercase tracking-widest">Transparency</span>
            </div>
        </div>

        <!-- Quick Features List -->
        <div class="space-y-4">
            <div class="p-5 bg-white/5 backdrop-blur-sm border border-white/10 rounded-2xl hover:bg-white/10 transition-colors">
                <h3 class="font-bold text-blue-300">01. Live Availability</h3>
                <p class="text-sm text-blue-100/60">Find open stalls across the market in real-time.</p>
            </div>
            <div class="p-5 bg-white/5 backdrop-blur-sm border border-white/10 rounded-2xl hover:bg-white/10 transition-colors">
                <h3 class="font-bold text-blue-300">02. Digital Records</h3>
                <p class="text-sm text-blue-100/60">Access all your booking history and receipts online.</p>
            </div>
            <div class="p-5 bg-white/5 backdrop-blur-sm border border-white/10 rounded-2xl hover:bg-white/10 transition-colors">
                <h3 class="font-bold text-blue-300">03. Rapid Support</h3>
                <p class="text-sm text-blue-100/60">Direct communication between traders and admins.</p>
            </div>
        </div>

    </main>

    <!-- Minimal Footer -->
    <footer class="mt-auto py-6 text-center text-blue-200/40 text-sm border-t border-white/5">
        &copy; {{ date('Y') }} Stall System. Built for Muthurwa.
    </footer>

</body>
</html>
