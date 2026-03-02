@extends('layouts.app')

@section('page-title', 'Available Stalls')

@section('content')

<div class="space-y-8">

    {{-- ========================= --}}
    {{-- 🔔 TRADER WARNINGS / RESTRICTIONS --}}
    {{-- ========================= --}}
    @if(auth()->user()->account_restriction !== null && auth()->user()->account_restriction !== 'none')
        <div class="p-6 rounded-2xl shadow-sm flex items-start gap-4
            @if(auth()->user()->isBanned()) bg-rose-50 border-l-4 border-rose-500
            @elseif(auth()->user()->isBlocked()) bg-orange-50 border-l-4 border-orange-500
            @else bg-amber-50 border-l-4 border-amber-500 @endif">
            
            <div class="text-2xl">
                @if(auth()->user()->isBanned()) 🔨 
                @elseif(auth()->user()->isBlocked()) 🚫
                @else ⚠️ @endif
            </div>

            <div>
                <h4 class="font-black text-sm uppercase tracking-tight
                    @if(auth()->user()->isBanned()) text-rose-800
                    @elseif(auth()->user()->isBlocked()) text-orange-800
                    @else text-amber-800 @endif">
                    {{ ucfirst(auth()->user()->account_restriction) }}
                </h4>
                <p class="text-xs mt-1 font-medium
                    @if(auth()->user()->isBanned()) text-rose-700
                    @elseif(auth()->user()->isBlocked()) text-orange-700
                    @else text-amber-700 @endif">
                    {{ auth()->user()->restriction_reason ?? 'Please comply with market regulations to avoid suspension.' }}
                </p>
                @if(auth()->user()->isBlocked())
                    <p class="text-[10px] font-bold text-rose-600 mt-2 uppercase">
                        Booking Access Suspended. Please visit the Market Office to resolve this issue.
                    </p>
                @elseif(auth()->user()->hasWarning())
                    <p class="text-[10px] font-bold text-amber-600 mt-2 uppercase">
                        Warning: Please comply with market regulations
                    </p>
                @endif
            </div>
        </div>
    @endif

    {{-- ========================= --}}
    {{-- Page Intro --}}
    {{-- ========================= --}}
    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white p-6 rounded-2xl shadow">
        <h2 class="text-2xl font-bold">Available Stalls</h2>
        <p class="text-sm opacity-90 mt-1">
            Browse available stalls and book instantly.
        </p>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="bg-green-100 border border-green-300 text-green-700 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    {{-- ========================= --}}
    {{-- Available Stalls --}}
    {{-- ========================= --}}
    @if($stalls->isNotEmpty())
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($stalls as $stall)
                <div class="bg-white rounded-2xl shadow-md hover:shadow-2xl transform hover:-translate-y-1 transition duration-300 overflow-hidden">

                    <!-- Card Header -->
                    <div class="p-5 border-b bg-gray-50 flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-gray-800">
                            Stall {{ $stall->stall_number }}
                        </h3>

                        <span class="text-xs bg-green-500 text-white px-3 py-1 rounded-full">
                            Available
                        </span>
                    </div>

                    <!-- Card Body -->
                    <div class="p-5 space-y-3 text-sm text-gray-600">

                        <div>
                            <span class="font-medium text-gray-800">Zone:</span>
                            {{ $stall->zone ?? 'N/A' }}
                        </div>

                        <div>
                            <span class="font-medium text-gray-800">Location:</span>
                            {{ $stall->location_desc ?? 'N/A' }}
                        </div>

                        <!-- Price -->
                        <div class="pt-4">
                            <p class="text-3xl font-bold text-indigo-600">
                                KES {{ number_format((float)$stall->price, 2) }}
                            </p>
                            <p class="text-xs text-gray-400">
                                Per booking
                            </p>
                        </div>

                        <!-- Button -->
                        <div class="pt-4">
                            @if(auth()->user()->isBlocked())
                                <button disabled class="block w-full text-center bg-gray-300 text-gray-600 py-2.5 rounded-xl cursor-not-allowed">
                                    Locked
                                </button>
                            @else
                                <a href="{{ route('trader.bookings.create', $stall->id) }}"
                                   class="block w-full text-center bg-indigo-600 hover:bg-indigo-700 text-white py-2.5 rounded-xl transition font-medium">
                                    Book Stall
                                </a>
                            @endif
                        </div>

                    </div>

                </div>
            @endforeach
        </div>
    @else
        <div class="bg-white shadow rounded-xl p-10 text-center text-gray-500">
            <p class="text-lg font-medium">No available stalls at the moment.</p>
            <p class="text-sm mt-2">Please check again later.</p>
        </div>
    @endif

    {{-- ========================= --}}
    {{-- Feedback Section --}}
    {{-- ========================= --}}
    <div class="bg-white p-6 rounded-2xl shadow-md mt-10 border border-slate-100">
        <h3 class="text-lg font-semibold mb-4 text-gray-700">
            Send Feedback to Admin
        </h3>

        @if ($errors->any())
            <div class="mb-4 text-red-600 text-sm font-medium">
                {{ $errors->first('message') }}
            </div>
        @endif

        <form action="{{ route('trader.feedback.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <textarea 
                    name="message" 
                    id="feedbackMessage" 
                    maxlength="160" 
                    rows="3" 
                    class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                    placeholder="How can we improve? (Max 160 characters)"
                    required>{{ old('message') }}</textarea>
                
                <div class="text-right text-xs text-gray-500 mt-1">
                    <span id="charCount">0</span> / 160 characters
                </div>
            </div>

            <button type="submit" 
                class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition font-medium">
                Send Feedback
            </button>
        </form>
    </div>

</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const textarea = document.getElementById('feedbackMessage');
    const charCount = document.getElementById('charCount');

    if (textarea) {
        charCount.textContent = textarea.value.length;

        textarea.addEventListener('input', function() {
            const length = this.value.length;
            charCount.textContent = length;
            
            if (length >= 150) {
                charCount.classList.add('text-red-500');
            } else {
                charCount.classList.remove('text-red-500');
            }
        });
    }
});
</script>
@endpush