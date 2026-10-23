<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>TGA Ticketing System</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}?v={{ time() }}">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="antialiased bg-gray-50 text-gray-800 font-sans">

    <div class="relative min-h-screen flex flex-col items-center justify-center selection:bg-blue-500 selection:text-white">

        <div class="max-w-7xl mx-auto p-6 lg:p-8 text-center">

            <div class="flex justify-center mb-6">
                <div class="bg-blue-600 p-4 rounded-xl shadow-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
            </div>

            <h1 class="text-4xl font-bold text-gray-900 mb-2">IT Helpdesk Portal</h1>
            <p class="text-lg text-gray-600 mb-8 max-w-2xl mx-auto">
                Sistem pelaporan tiket terpadu untuk menangani kendala teknis, jaringan, dan perangkat keras Anda.
            </p>

            <div class="flex justify-center gap-4">
                @if (Route::has('login'))
                <div class="space-x-4">
                    @auth
                    <a href="{{ url('/dashboard') }}"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg shadow transition duration-300">
                        Buka Dashboard
                    </a>
                    @else
                    <a href="{{ route('login') }}"
                        class="bg-blue-700 hover:bg-blue-800 text-white font-semibold py-3 px-8 rounded-lg shadow-lg hover:shadow-xl transition duration-300 transform hover:-translate-y-1">
                        Login Masuk
                    </a>

                    @endauth
                </div>
                @endif
            </div>

            <div class="mt-12 text-sm text-gray-400">
                &copy; {{ date('Y') }} IT Department - TGA Solution. All rights reserved.
            </div>
        </div>
    </div>
</body>

</html>