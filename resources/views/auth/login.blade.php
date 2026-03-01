<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-900 via-blue-800 to-indigo-900 py-6 px-4">
        
        <div class="max-w-md w-full">
            <!-- Logo/Heading -->
            <div class="text-center mb-8">
                <h1 class="text-4xl font-extrabold tracking-tight bg-clip-text text-transparent bg-gradient-to-r from-white to-blue-200">
                    Stall System
                </h1>
                <p class="mt-2 text-blue-200/70 text-sm">Welcome back! Please enter your details.</p>
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4 text-center text-green-400" :status="session('status')" />

            <!-- Login Card (Glassmorphism) -->
            <div class="bg-white/10 backdrop-blur-xl border border-white/20 rounded-3xl shadow-2xl p-8">
                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf

                    <!-- Email Address -->
                    <div>
                        <x-input-label for="email" :value="__('Email Address')" class="text-blue-100 text-sm font-medium mb-1" />
                        <x-text-input id="email" 
                            class="block w-full bg-white/5 border-white/20 text-white placeholder-blue-300/50 focus:border-blue-400 focus:ring focus:ring-blue-400/20 rounded-xl transition-all" 
                            type="email" name="email" :value="old('email')" required autofocus />
                        <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-300 text-xs" />
                    </div>

                    <!-- Password -->
                    <div>
                        <x-input-label for="password" :value="__('Password')" class="text-blue-100 text-sm font-medium mb-1" />
                        <x-text-input id="password" 
                            class="block w-full bg-white/5 border-white/20 text-white placeholder-blue-300/50 focus:border-blue-400 focus:ring focus:ring-blue-400/20 rounded-xl transition-all" 
                            type="password" name="password" required />
                        <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-300 text-xs" />
                    </div>

                    <!-- Remember & Forgot -->
                    <div class="flex items-center justify-between">
                        <label for="remember_me" class="inline-flex items-center text-xs text-blue-200/80 cursor-pointer">
                            <input id="remember_me" type="checkbox" class="rounded border-white/20 bg-white/5 text-blue-500 focus:ring-offset-blue-900" name="remember">
                            <span class="ml-2">{{ __('Remember me') }}</span>
                        </label>

                        @if (Route::has('password.request'))
                            <a class="text-xs text-blue-300 hover:text-white transition-colors" href="{{ route('password.request') }}">
                                {{ __('Forgot password?') }}
                            </a>
                        @endif
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="w-full py-3 bg-white text-blue-900 font-bold rounded-xl shadow-lg hover:scale-[1.02] hover:bg-blue-50 transition-all active:scale-95">
                        {{ __('Sign In') }}
                    </button>
                </form>

                <!-- Register Link -->
                <div class="mt-6 text-center">
                    <p class="text-sm text-blue-200/60">
                        Don't have an account? 
                        <a href="{{ route('register') }}" class="text-blue-300 font-semibold hover:underline">Register</a>
                    </p>
                </div>
            </div>

            <!-- Footer -->
            <p class="mt-8 text-center text-xs text-blue-300/40">
                &copy; {{ date('Y') }} Stall System. All rights reserved.
            </p>
        </div>
    </div>
</x-guest-layout>
