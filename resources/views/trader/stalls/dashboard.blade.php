<x-app-layout>
    <div class="py-6 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Premium Welcome Banner -->
            <div class="relative overflow-hidden bg-gradient-to-r from-blue-900 to-indigo-900 rounded-3xl p-8 shadow-xl border border-white/10">
                <div class="relative z-10">
                    <h3 class="text-3xl font-black text-white tracking-tight">
                        Welcome back, {{ auth()->user()->name }}! 👋
                    </h3>
                    <p class="text-blue-200/80 mt-2 font-medium">
                        {{ __("Everything is ready for your trading day at Muthurwa.") }}
                    </p>
                </div>
                <!-- Abstract Background Shape -->
                <div class="absolute -right-10 -top-10 w-64 h-64 bg-white/5 rounded-full blur-3xl"></div>
            </div>

            <!-- Quick Actions Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                <!-- Action 1: Stalls -->
                <div class="group bg-white p-8 rounded-3xl shadow-sm border border-slate-100 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                    <div class="w-12 h-12 bg-blue-50 rounded-2xl flex items-center justify-center text-2xl mb-6 group-hover:scale-110 transition-transform">🏪</div>
                    <h4 class="text-xl font-bold text-slate-800 mb-2">Explore Stalls</h4>
                    <p class="text-slate-500 text-sm mb-6 leading-relaxed">
                        Find the perfect spot for your business. View live availability across all market zones.
                    </p>
                    <a href="{{ route('trader.stalls.index') }}" 
                       class="inline-flex items-center px-6 py-2.5 bg-blue-600 text-white text-sm font-bold rounded-xl hover:bg-blue-700 shadow-lg shadow-blue-600/20 transition-all">
                        Open Map
                    </a>
                </div>

                <!-- Action 2: Bookings -->
                <div class="group bg-white p-8 rounded-3xl shadow-sm border border-slate-100 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                    <div class="w-12 h-12 bg-emerald-50 rounded-2xl flex items-center justify-center text-2xl mb-6 group-hover:scale-110 transition-transform">📋</div>
                    <h4 class="text-xl font-bold text-slate-800 mb-2">My Bookings</h4>
                    <p class="text-slate-500 text-sm mb-6 leading-relaxed">
                        Track your active reservations, view receipts, and manage your rental history.
                    </p>
                    <a href="{{ route('trader.bookings.index') }}"
                       class="inline-flex items-center px-6 py-2.5 bg-emerald-600 text-white text-sm font-bold rounded-xl hover:bg-emerald-700 shadow-lg shadow-emerald-600/20 transition-all">
                        Manage Stalls
                    </a>
                </div>

                <!-- Action 3: Profile -->
                <div class="group bg-white p-8 rounded-3xl shadow-sm border border-slate-100 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                    <div class="w-12 h-12 bg-purple-50 rounded-2xl flex items-center justify-center text-2xl mb-6 group-hover:scale-110 transition-transform">👤</div>
                    <h4 class="text-xl font-bold text-slate-800 mb-2">Account Settings</h4>
                    <p class="text-slate-500 text-sm mb-6 leading-relaxed">
                        Update your contact details, phone number, and security preferences.
                    </p>
                    <a href="{{ route('profile.edit') }}"
                       class="inline-flex items-center px-6 py-2.5 bg-purple-600 text-white text-sm font-bold rounded-xl hover:bg-purple-700 shadow-lg shadow-purple-600/20 transition-all">
                        Edit Profile
                    </a>
                </div>

            </div>

            <!-- Footer Branding -->
            <p class="text-center text-[10px] text-slate-400 font-bold uppercase tracking-[0.3em] pt-8">
                Muthurwa Digital Marketplace &bull; {{ date('Y') }}
            </p>

        </div>
    </div>
</x-app-layout>
