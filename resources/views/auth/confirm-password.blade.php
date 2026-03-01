<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-900 via-blue-800 to-indigo-900 py-6 px-4">
        
        <div class="max-w-md w-full">
            <!-- Security Icon/Header -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-12 h-12 bg-white/10 rounded-full mb-4 border border-white/20">
                    <span class="text-blue-300 text-xl">🔒</span>
                </div>
                <h1 class="text-3xl font-extrabold tracking-tight bg-clip-text text-transparent bg-gradient-to-r from-white to-blue-200">
                    Security Check
                </h1>
                <p class="mt-2 text-blue-200/70 text-sm px-6">
                    This is a secure area. Please confirm your password to proceed.
                </p>
            </div>

            <!-- Glass Card -->
            <div class="bg-white/10 backdrop-blur-xl border border-white/20 rounded-3xl shadow-2xl p-8">
                <form method="POST" action="{{ route('password.confirm') }}" class="space-y-6">
                    @csrf

                    <!-- Password -->
                    <div>
                        <x-input-label for="password" :value="__('Account Password')" class="text-blue-100 text-sm font-medium mb-1" />
                        <x-text-input id="password" 
                            class="block w-full bg-white/5 border-white/20 text-white focus:border-blue-400 focus:ring focus:ring-blue-400/20 rounded-xl transition-all" 
                            type="password" 
                            name="password" 
                            required 
                            autocomplete="current-password" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-300 text-xs" />
                    </div>

                    <!-- Action Button -->
                    <div class="pt-2">
                        <button type="submit" class="w-full py-3 bg-white text-blue-900 font-bold rounded-xl shadow-lg hover:scale-[1.02] hover:bg-blue-50 transition-all active:scale-95">
                            {{ __('Confirm Password') }}
                        </button>
                    </div>
                </form>
            </div>

            <!-- Minimal Footer -->
            <p class="mt-8 text-center text-[10px] text-blue-300/30 uppercase tracking-widest">
                Stall System &bull; Secure Protocol
            </p>
        </div>
    </div>
</x-guest-layout>
