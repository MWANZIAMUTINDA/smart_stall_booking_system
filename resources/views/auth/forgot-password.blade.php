<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-900 via-blue-800 to-indigo-900 py-6 px-4">
        
        <div class="max-w-md w-full">
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-4xl font-extrabold tracking-tight bg-clip-text text-transparent bg-gradient-to-r from-white to-blue-200">
                    Recover Access
                </h1>
                <p class="mt-2 text-blue-200/70 text-sm px-4">
                    Enter your email and we'll send you a secure link to reset your password.
                </p>
            </div>

            <!-- Session Status (Success Message) -->
            <x-auth-session-status class="mb-6 text-center text-sm font-medium text-green-400 bg-green-400/10 py-3 rounded-xl border border-green-400/20" :status="session('status')" />

            <!-- Glass Card -->
            <div class="bg-white/10 backdrop-blur-xl border border-white/20 rounded-3xl shadow-2xl p-8">
                <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
                    @csrf

                    <!-- Email Address -->
                    <div>
                        <x-input-label for="email" :value="__('Registered Email')" class="text-blue-100 text-sm font-medium mb-1" />
                        <x-text-input id="email" 
                            class="block w-full bg-white/5 border-white/20 text-white placeholder-blue-300/30 focus:border-blue-400 focus:ring focus:ring-blue-400/20 rounded-xl transition-all" 
                            type="email" name="email" :value="old('email')" required autofocus />
                        <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-300 text-xs" />
                    </div>

                    <!-- Actions -->
                    <div class="space-y-4">
                        <button type="submit" class="w-full py-3 bg-white text-blue-900 font-bold rounded-xl shadow-lg hover:scale-[1.02] hover:bg-blue-50 transition-all active:scale-95">
                            {{ __('Send Reset Link') }}
                        </button>

                        <div class="text-center">
                            <a href="{{ route('login') }}" class="text-xs text-blue-300 hover:text-white transition-colors">
                                &larr; Back to Login
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Footer -->
            <p class="mt-8 text-center text-[10px] text-blue-300/30 uppercase tracking-widest">
                Automated Recovery Portal
            </p>
        </div>
    </div>
</x-guest-layout>
