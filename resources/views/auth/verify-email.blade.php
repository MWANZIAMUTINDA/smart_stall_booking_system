<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-900 via-blue-800 to-indigo-900 py-6 px-4">
        
        <div class="max-w-md w-full text-center">
            <!-- Mail Icon -->
            <div class="inline-flex items-center justify-center w-16 h-16 bg-white/10 rounded-full mb-6 border border-white/20">
                <span class="text-blue-300 text-2xl">📩</span>
            </div>

            <h1 class="text-3xl font-extrabold tracking-tight bg-clip-text text-transparent bg-gradient-to-r from-white to-blue-200 mb-4">
                Verify Your Email
            </h1>

            <p class="text-blue-200/70 text-sm px-6 mb-8">
                Thanks for joining! We've sent a link to your inbox. Just click it to verify your account and get started.
            </p>

            <!-- Status Message (Success) -->
            @if (session('status') == 'verification-link-sent')
                <div class="mb-8 font-medium text-sm text-green-400 bg-green-400/10 py-3 px-4 rounded-xl border border-green-400/20">
                    {{ __('A fresh link has been sent to your email address.') }}
                </div>
            @endif

            <!-- Glass Card -->
            <div class="bg-white/10 backdrop-blur-xl border border-white/20 rounded-3xl shadow-2xl p-8">
                <div class="flex flex-col gap-4">
                    <form method="POST" action="{{ route('verification.send') }}">
                        @csrf
                        <button type="submit" class="w-full py-3 bg-white text-blue-900 font-bold rounded-xl shadow-lg hover:scale-[1.02] hover:bg-blue-50 transition-all active:scale-95">
                            {{ __('Resend Email') }}
                        </button>
                    </form>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-xs text-blue-300 hover:text-white transition-colors underline decoration-blue-300/30">
                            {{ __('Log Out') }}
                        </button>
                    </form>
                </div>
            </div>

            <!-- Minimal Footer -->
            <p class="mt-8 text-center text-[10px] text-blue-300/30 uppercase tracking-widest">
                Stall System &bull; Account Activation
            </p>
        </div>
    </div>
</x-guest-layout>
