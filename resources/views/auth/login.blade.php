<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-900 via-blue-800 to-indigo-900 py-6 px-4">
        
        <div class="max-w-md w-full">
            
            <!-- Logo / Heading -->
            <div class="text-center mb-8">
                <h1 class="text-4xl font-extrabold tracking-tight bg-clip-text text-transparent bg-gradient-to-r from-white to-blue-200">
                    Stall System
                </h1>
                <p class="mt-2 text-blue-200/70 text-sm">
                    Welcome back! Please enter your details.
                </p>
            </div>

            <!-- Session Status -->
            <x-auth-session-status 
                class="mb-4 text-center text-green-400" 
                :status="session('status')" 
            />

            <!-- Login Card -->
            <div class="bg-white/10 backdrop-blur-xl border border-white/20 rounded-3xl shadow-2xl p-8">
                
                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf

                    <!-- Email -->
                    <div>
                        <x-input-label 
                            for="email" 
                            :value="__('Email Address')" 
                            class="text-blue-100 text-sm font-medium mb-1" 
                        />

                        <x-text-input 
                            id="email"
                            type="email"
                            name="email"
                            :value="old('email')"
                            required
                            autofocus
                            class="block w-full bg-white/5 border-white/20 text-white placeholder-blue-300/50 focus:border-blue-400 focus:ring focus:ring-blue-400/20 rounded-xl transition-all"
                        />

                        <x-input-error 
                            :messages="$errors->get('email')" 
                            class="mt-2 text-red-300 text-xs" 
                        />
                    </div>

                    <!-- Password with Toggle -->
                    <div>
                        <x-input-label 
                            for="password" 
                            :value="__('Password')" 
                            class="text-blue-100 text-sm font-medium mb-1" 
                        />

                        <div class="relative">
                            <x-text-input 
                                id="password"
                                type="password"
                                name="password"
                                required
                                class="block w-full bg-white/5 border-white/20 text-white placeholder-blue-300/50 focus:border-blue-400 focus:ring focus:ring-blue-400/20 rounded-xl pr-12 transition-all"
                            />

                            <!-- Toggle Button -->
                            <button 
                                type="button"
                                onclick="togglePassword()"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-blue-300 hover:text-white transition-colors focus:outline-none"
                            >

                                <!-- Eye (Show) -->
                                <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                    class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.036 12.322a1.012 1.012 0 010-.644C3.67 8.501 
                                        7.534 5.75 12 5.75c4.466 0 8.33 2.751 
                                        9.964 5.928.047.101.047.215 0 .316-1.633 
                                        3.177-5.498 5.928-9.964 5.928-4.466 
                                        0-8.33-2.751-9.964-5.928z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>

                                <!-- Eye Slash (Hide) -->
                                <svg id="eyeSlashIcon" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                    class="w-5 h-5 hidden">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M3.98 8.223A10.477 10.477 0 001.934 
                                        12C3.226 16.338 7.244 19.5 12 
                                        19.5c.993 0 1.953-.138 2.863-.395M6.228 
                                        6.228A10.45 10.45 0 0112 
                                        4.5c4.756 0 8.773 3.162 10.065 
                                        7.498a10.523 10.523 0 01-4.293 
                                        5.774M6.228 6.228L3 3m3.228 
                                        3.228l3.65 3.65m7.894 
                                        7.894L21 21m-3.228-3.228l-3.65-3.65m0 
                                        0a3 3 0 10-4.243-4.243m4.242 
                                        4.242L9.88 9.88" />
                                </svg>
                            </button>
                        </div>

                        <x-input-error 
                            :messages="$errors->get('password')" 
                            class="mt-2 text-red-300 text-xs" 
                        />
                    </div>

                    <!-- Remember + Forgot -->
                    <div class="flex items-center justify-between">
                        <label class="inline-flex items-center text-xs text-blue-200/80 cursor-pointer">
                            <input id="remember_me" type="checkbox"
                                class="rounded border-white/20 bg-white/5 text-blue-500 focus:ring-offset-blue-900"
                                name="remember">
                            <span class="ml-2">{{ __('Remember me') }}</span>
                        </label>

                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}"
                                class="text-xs text-blue-300 hover:text-white transition-colors">
                                {{ __('Forgot password?') }}
                            </a>
                        @endif
                    </div>

                    <!-- Submit -->
                    <button type="submit"
                        class="w-full py-3 bg-white text-blue-900 font-bold rounded-xl shadow-lg hover:scale-[1.02] hover:bg-blue-50 transition-all active:scale-95">
                        {{ __('Sign In') }}
                    </button>
                </form>

                <!-- Register -->
                <div class="mt-6 text-center">
                    <p class="text-sm text-blue-200/60">
                        Don't have an account?
                        <a href="{{ route('register') }}"
                            class="text-blue-300 font-semibold hover:underline">
                            Register
                        </a>
                    </p>
                </div>
            </div>

            <!-- Footer -->
            <p class="mt-8 text-center text-xs text-blue-300/40">
                &copy; {{ date('Y') }} Stall System. All rights reserved.
            </p>

        </div>
    </div>

    <!-- Toggle Script -->
    <script>
        function togglePassword() {
            const password = document.getElementById('password');
            const eye = document.getElementById('eyeIcon');
            const eyeSlash = document.getElementById('eyeSlashIcon');

            if (password.type === 'password') {
                password.type = 'text';
                eye.classList.add('hidden');
                eyeSlash.classList.remove('hidden');
            } else {
                password.type = 'password';
                eye.classList.remove('hidden');
                eyeSlash.classList.add('hidden');
            }
        }
    </script>
</x-guest-layout>