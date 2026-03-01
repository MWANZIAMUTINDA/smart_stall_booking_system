<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Stall System') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gradient-to-br from-blue-100 via-blue-50 to-white">

    <div class="min-h-screen flex flex-col justify-center items-center px-4">

        <!-- Custom System Title -->
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold text-blue-800">
                Stall Booking System
            </h1>
            <p class="mt-2 text-sm text-blue-600">
                Manage your stall bookings easily and securely
            </p>
        </div>

        <!-- Authentication Card -->
        <div class="w-full max-w-md bg-white rounded-3xl shadow-2xl p-8">
            {{ $slot }}
        </div>

        <!-- Footer -->
        <div class="mt-6 text-center text-sm text-blue-600">
            &copy; {{ date('Y') }} Stall System. All rights reserved.
        </div>

    </div>

</body>
</html>