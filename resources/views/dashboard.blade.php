@extends('layouts.app')

@section('page-title', 'Available Stalls')

@section('content')

<div class="space-y-10">

    {{-- Page Intro --}}
    <div class="bg-gradient-to-r from-pink-500 via-purple-500 to-indigo-500 text-white p-8 rounded-3xl shadow-xl relative overflow-hidden transform hover:scale-[1.01] transition-transform duration-300">
        <!-- Animated Background Blur -->
        <div class="absolute -top-20 -right-20 w-64 h-64 bg-white/20 blur-3xl rounded-full animate-pulse-slow"></div>
        
        <h2 class="text-3xl font-black tracking-tight relative z-10 drop-shadow-md">🚀 Available Stalls</h2>
        <p class="text-lg font-medium opacity-90 mt-2 relative z-10 text-pink-50">
            Browse available stalls in vibrant detail and book instantly.
        </p>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="bg-gradient-to-r from-emerald-400 to-teal-500 text-white px-6 py-4 rounded-2xl shadow-lg font-bold flex items-center gap-3 animate-bounce">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            {{ session('success') }}
        </div>
    @endif

    @if($stalls->isNotEmpty())

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">

            @foreach($stalls as $stall)

                <div class="stall-card-reveal bg-white rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.05)] hover:shadow-[0_10px_40px_rgba(236,72,153,0.2)] transform hover:-translate-y-2 transition-all duration-300 overflow-hidden border border-gray-100 group">

                    <!-- Card Header -->
                    <div class="p-6 border-b border-pink-100 bg-gradient-to-br from-pink-50 to-purple-50 flex justify-between items-center group-hover:from-pink-100 group-hover:to-purple-100 transition-colors">
                        <h3 class="text-xl font-extrabold text-gray-800 bg-clip-text text-transparent bg-gradient-to-r from-pink-600 to-purple-600">
                            Stall {{ $stall->stall_number }}
                        </h3>

                        <span class="text-xs bg-gradient-to-r from-emerald-400 to-teal-500 text-white px-4 py-1.5 rounded-full shadow-md font-bold uppercase tracking-wider animate-pulse">
                            Available
                        </span>
                    </div>

                    <!-- Card Body -->
                    <div class="p-6 space-y-4 text-sm text-gray-600">

                        <div class="flex items-center gap-2">
                            <span class="p-2 bg-pink-100 rounded-lg text-pink-600"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg></span>
                            <div>
                                <span class="font-bold text-gray-800 block">Zone:</span>
                                {{ $stall->zone ?? 'N/A' }}
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            <span class="p-2 bg-purple-100 rounded-lg text-purple-600"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></span>
                            <div>
                                <span class="font-bold text-gray-800 block">Location:</span>
                                {{ $stall->location_desc ?? 'N/A' }}
                            </div>
                        </div>

                        <!-- Price -->
                        <div class="pt-4 mt-2 border-t border-gray-100">
                            <p class="text-4xl font-black text-transparent bg-clip-text bg-gradient-to-r from-pink-500 to-purple-500 drop-shadow-sm group-hover:scale-105 transition-transform origin-left">
                                KES {{ number_format((float)$stall->price, 2) }}
                            </p>
                            <p class="text-xs text-gray-400 font-medium uppercase tracking-widest mt-1">
                                Per booking
                            </p>
                        </div>

                        <!-- Button -->
                        <div class="pt-6">
                            <a href="{{ route('trader.bookings.create', $stall->id) }}"
                               class="block w-full text-center bg-gradient-to-r from-pink-500 to-purple-600 hover:from-pink-600 hover:to-purple-700 text-white py-3.5 rounded-2xl transition-all duration-300 font-extrabold shadow-[0_5px_15px_rgba(236,72,153,0.3)] hover:shadow-[0_10px_25px_rgba(236,72,153,0.5)] active:scale-95">
                                ⚡ Book Stall Now
                            </a>
                        </div>

                    </div>

                </div>

            @endforeach

        </div>

    @else

        <div class="bg-white/50 backdrop-blur-lg shadow-xl rounded-3xl p-12 text-center text-gray-500 border border-pink-100 animate-float">
            <span class="text-6xl mb-4 block">🏝️</span>
            <p class="text-2xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-pink-400 to-purple-400">No available stalls right now.</p>
            <p class="text-md mt-2 text-gray-400 font-medium">Keep checking back, the market moves fast!</p>
        </div>

    @endif


    <!-- ========================= -->
    <!-- 📩 FEEDBACK SECTION -->
    <!-- ========================= -->
    <div class="bg-gradient-to-br from-white to-pink-50 p-8 rounded-3xl shadow-[0_5px_25px_rgba(0,0,0,0.05)] mt-12 border border-pink-100 relative overflow-hidden group">
        <!-- Decoration -->
        <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-pink-300/20 rounded-full blur-3xl group-hover:bg-pink-400/30 transition-colors"></div>

        <h3 class="text-xl font-extrabold mb-6 text-gray-800 flex items-center gap-2">
            <span class="text-2xl">💬</span> Send Feedback to Admin
        </h3>

        @if ($errors->any())
            <div class="mb-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded text-sm font-medium">
                {{ $errors->first('message') }}
            </div>
        @endif

        <form action="{{ route('trader.feedback.store') }}" method="POST" class="relative z-10">
            @csrf

            <div class="mb-4">
                <textarea 
                    name="message" 
                    id="feedbackMessage" 
                    maxlength="160" 
                    rows="4" 
                    class="w-full border-gray-200 rounded-2xl shadow-inner bg-white/80 focus:ring-4 focus:ring-pink-500/20 focus:border-pink-500 transition-all font-medium resize-none p-4"
                    placeholder="Got an idea or issue? Let us know... (Max 160 characters)"
                    required>{{ old('message') }}</textarea>
                
                <div class="flex justify-between items-center mt-2 px-2">
                    <div class="h-2 w-full bg-gray-200 rounded-full overflow-hidden mr-4">
                        <div id="charBar" class="h-full bg-gradient-to-r from-emerald-400 to-pink-500 transition-all duration-300" style="width: 0%"></div>
                    </div>
                    <div class="text-right text-xs font-bold text-gray-400 whitespace-nowrap">
                        <span id="charCount" class="text-gray-600">0</span> / 160
                    </div>
                </div>
            </div>

            <button type="submit" 
                class="px-8 py-3.5 bg-gray-900 text-white rounded-2xl hover:bg-black transition-all font-extrabold shadow-lg hover:shadow-2xl hover:-translate-y-1 active:translate-y-0 active:scale-95 flex items-center gap-2">
                <span>Send Feedback</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
            </button>
        </form>
    </div>

</div>

@endsection


@push('scripts')
<style>
    @keyframes float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }
    .animate-float { animation: float 4s ease-in-out infinite; }
    
    @keyframes pulse-slow {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.7; transform: scale(1.1); }
    }
    .animate-pulse-slow { animation: pulse-slow 4s ease-in-out infinite; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const textarea = document.getElementById('feedbackMessage');
    const charCount = document.getElementById('charCount');
    const charBar = document.getElementById('charBar');

    if (textarea) {
        const updateCharCount = () => {
            const length = textarea.value.length;
            charCount.textContent = length;
            
            // Update progress bar
            const percent = (length / 160) * 100;
            charBar.style.width = `${percent}%`;
            
            if (length >= 150) {
                charCount.classList.add('text-red-500');
                charBar.classList.remove('from-emerald-400', 'to-pink-500');
                charBar.classList.add('bg-red-500');
            } else {
                charCount.classList.remove('text-red-500');
                charBar.classList.add('from-emerald-400', 'to-pink-500');
                charBar.classList.remove('bg-red-500');
            }
        };

        updateCharCount(); // Init
        textarea.addEventListener('input', updateCharCount);
    }
});
</script>
@endpush