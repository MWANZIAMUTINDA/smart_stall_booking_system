<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-900 via-blue-800 to-indigo-900 py-4 px-4">
        
        <div class="max-w-xl w-full">
            <!-- Header -->
            <div class="text-center mb-6">
                <h1 class="text-3xl font-extrabold tracking-tight bg-clip-text text-transparent bg-gradient-to-r from-white to-blue-200">
                    Join Stall System
                </h1>
                <p class="mt-1 text-blue-200/70 text-sm">Create your vendor account in seconds.</p>
            </div>

            <!-- Glass Card -->
            <div class="bg-white/10 backdrop-blur-xl border border-white/20 rounded-3xl shadow-2xl p-6 md:p-8">
                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <!-- Two-Column Grid for shorter height -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Name -->
                        <div>
                            <x-input-label for="name" :value="__('Full Name')" class="text-blue-100 text-xs mb-1" />
                            <x-text-input id="name" class="block w-full bg-white/5 border-white/20 text-white text-sm focus:ring-blue-400/20 rounded-xl" type="text" name="name" :value="old('name')" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-1 text-red-300 text-[10px]" />
                        </div>

                        <!-- Email -->
                        <div>
                            <x-input-label for="email" :value="__('Email Address')" class="text-blue-100 text-xs mb-1" />
                            <x-text-input id="email" class="block w-full bg-white/5 border-white/20 text-white text-sm focus:ring-blue-400/20 rounded-xl" type="email" name="email" :value="old('email')" required />
                            <x-input-error :messages="$errors->get('email')" class="mt-1 text-red-300 text-[10px]" />
                        </div>

                        <!-- Phone Number -->
                        <div class="md:col-span-2">
                            <x-input-label for="phone_number" :value="__('Phone Number')" class="text-blue-100 text-xs mb-1" />
                            <x-text-input id="phone_number" class="block w-full bg-white/5 border-white/20 text-white text-sm focus:ring-blue-400/20 rounded-xl" type="text" name="phone_number" required />
                            <x-input-error :messages="$errors->get('phone_number')" class="mt-1 text-red-300 text-[10px]" />
                        </div>

                        <!-- Password -->
                        <div>
                            <x-input-label for="password" :value="__('Password')" class="text-blue-100 text-xs mb-1" />
                            <x-text-input id="password" class="block w-full bg-white/5 border-white/20 text-white text-sm focus:ring-blue-400/20 rounded-xl" type="password" name="password" required />
                            <x-input-error :messages="$errors->get('password')" class="mt-1 text-red-300 text-[10px]" />
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <x-input-label for="password_confirmation" :value="__('Confirm')" class="text-blue-100 text-xs mb-1" />
                            <x-text-input id="password_confirmation" class="block w-full bg-white/5 border-white/20 text-white text-sm focus:ring-blue-400/20 rounded-xl" type="password" name="password_confirmation" required />
                        </div>
                    </div>

                    <div class="mt-6 space-y-4">
                        <button type="submit" class="w-full py-3 bg-white text-blue-900 font-bold rounded-xl shadow-lg hover:scale-[1.01] hover:bg-blue-50 transition-all active:scale-95">
                            {{ __('Register Now') }}
                        </button>

                        <div class="text-center">
                            <a class="text-xs text-blue-300 hover:text-white transition-colors" href="{{ route('login') }}">
                                {{ __('Already have an account? Sign in') }}
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            <p class="mt-6 text-center text-[10px] text-blue-300/30 uppercase tracking-widest">
                Secure Portal &bull; Muthurwa Market
            </p>
        </div>
    </div>
</x-guest-layout>
