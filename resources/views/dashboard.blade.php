@extends('layouts.app')

@section('page-title', 'Available Stalls')

@section('content')

<div class="space-y-8">

    {{-- Page Intro --}}
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
                            <a href="{{ route('trader.bookings.create', $stall->id) }}"
                               class="block w-full text-center bg-indigo-600 hover:bg-indigo-700 text-white py-2.5 rounded-xl transition font-medium">
                                Book Stall
                            </a>
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

</div>

@endsection