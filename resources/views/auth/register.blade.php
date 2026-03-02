<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-900 via-blue-800 to-indigo-900 py-4 px-4">
        
        <div class="max-w-xl w-full">
            
            <!-- Header -->
            <div class="text-center mb-6">
                <h1 class="text-3xl font-extrabold tracking-tight bg-clip-text text-transparent bg-gradient-to-r from-white to-blue-200">
                    Join Stall System
                </h1>
                <p class="mt-1 text-blue-200/70 text-sm">
                    Create your vendor account in seconds.
                </p>
            </div>

            <!-- Glass Card -->
            <div class="bg-white/10 backdrop-blur-xl border border-white/20 rounded-3xl shadow-2xl p-6 md:p-8">
                
                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        <!-- Name -->
                        <div>
                            <x-input-label for="name" :value="__('Full Name')" class="text-blue-100 text-xs mb-1" />
                            <x-text-input id="name"
                                class="block w-full bg-white/5 border-white/20 text-white text-sm rounded-xl"
                                type="text" name="name" :value="old('name')" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-1 text-red-300 text-[10px]" />
                        </div>

                        <!-- Email -->
                        <div>
                            <x-input-label for="email" :value="__('Email Address')" class="text-blue-100 text-xs mb-1" />
                            <x-text-input id="email"
                                class="block w-full bg-white/5 border-white/20 text-white text-sm rounded-xl"
                                type="email" name="email" :value="old('email')" required />
                            <x-input-error :messages="$errors->get('email')" class="mt-1 text-red-300 text-[10px]" />
                        </div>

                        <!-- Phone -->
                        <div class="md:col-span-2">
                            <x-input-label for="phone_number" :value="__('Phone Number')" class="text-blue-100 text-xs mb-1" />
                            <x-text-input id="phone_number"
                                class="block w-full bg-white/5 border-white/20 text-white text-sm rounded-xl"
                                type="text" name="phone_number" required />
                            <x-input-error :messages="$errors->get('phone_number')" class="mt-1 text-red-300 text-[10px]" />
                        </div>

                        <!-- Password -->
                        <div>
                            <x-input-label for="password" :value="__('Password')" class="text-blue-100 text-xs mb-1" />
                            <div class="relative">
                                <x-text-input id="password"
                                    class="block w-full bg-white/5 border-white/20 text-white text-sm rounded-xl pr-12"
                                    type="password" name="password" required />

                                <button type="button" onclick="togglePassword('password','eye1','eyeSlash1')"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-blue-300 hover:text-white">
                                    
                                    <svg id="eye1" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                        class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M2.036 12.322a1.012 1.012 0 010-.644C3.67 8.501 7.534 
                                            5.75 12 5.75c4.466 0 8.33 2.751 
                                            9.964 5.928.047.101.047.215 0 .316-1.633 
                                            3.177-5.498 5.928-9.964 5.928-4.466 
                                            0-8.33-2.751-9.964-5.928z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>

                                    <svg id="eyeSlash1" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                        class="w-5 h-5 hidden">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M3 3l18 18"/>
                                    </svg>
                                </button>
                            </div>
                            <x-input-error :messages="$errors->get('password')" class="mt-1 text-red-300 text-[10px]" />
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="text-blue-100 text-xs mb-1" />
                            <div class="relative">
                                <x-text-input id="password_confirmation"
                                    class="block w-full bg-white/5 border-white/20 text-white text-sm rounded-xl pr-12"
                                    type="password" name="password_confirmation" required />

                                <button type="button" onclick="togglePassword('password_confirmation','eye2','eyeSlash2')"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-blue-300 hover:text-white">

                                    <svg id="eye2" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                        class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M2.036 12.322a1.012 1.012 0 010-.644C3.67 8.501 7.534 
                                            5.75 12 5.75c4.466 0 8.33 2.751 
                                            9.964 5.928.047.101.047.215 0 .316-1.633 
                                            3.177-5.498 5.928-9.964 5.928-4.466 
                                            0-8.33-2.751-9.964-5.928z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>

                                    <svg id="eyeSlash2" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                        class="w-5 h-5 hidden">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M3 3l18 18"/>
                                    </svg>
                                </button>
                            </div>
                        </div>

                    </div>

                    <div class="mt-6 space-y-4">
                        <button type="submit"
                            class="w-full py-3 bg-white text-blue-900 font-bold rounded-xl shadow-lg hover:scale-[1.01] hover:bg-blue-50 transition-all active:scale-95">
                            {{ __('Register Now') }}
                        </button>

                        <div class="text-center">
                            <a href="{{ route('login') }}"
                                class="text-xs text-blue-300 hover:text-white transition-colors">
                                {{ __('Already have an account? Sign in') }}
                            </a>
                        </div>
                    </div>

                </form>
            </div>

            <p class="mt-6 text-center text-[10px] text-blue-300/30 uppercase tracking-widest">
                Secure Portal • Muthurwa Market
            </p>
        </div>
    </div>

    <!-- Toggle Script -->
    <script>
        function togglePassword(fieldId, eyeId, slashId) {
            const field = document.getElementById(fieldId);
            const eye = document.getElementById(eyeId);
            const slash = document.getElementById(slashId);

            if (field.type === 'password') {
                field.type = 'text';
                eye.classList.add('hidden');
                slash.classList.remove('hidden');
            } else {
                field.type = 'password';
                eye.classList.remove('hidden');
                slash.classList.add('hidden');
            }
        }
    </script>
</x-guest-layout>