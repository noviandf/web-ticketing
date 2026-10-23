<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @auth
        <meta name="user-id" content="{{ Auth::id() }}">
    @endauth

    <title>TGA Ticketing System</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}?v={{ time() }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body class="font-sans antialiased text-gray-900 dark:text-gray-100">
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900 transition-colors duration-300">
        @include('layouts.navigation')

        @isset($header)
        <header class="bg-white dark:bg-gray-800 shadow transition-colors duration-300">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 flex justify-between items-center">
                <div>
                    {{ $header }}
                </div>
                <button id="theme-toggle" class="p-2 bg-gray-200 dark:bg-gray-700 rounded-md text-sm">
                    Ubah Tema 🌓
                </button>
            </div>
        </header>
        @endif

        <main>
            {{ $slot }}
        </main>
    </div>
    <script>
        const themeToggleBtn = document.getElementById('theme-toggle');
        const htmlElement = document.documentElement;

        // Cek memori saat halaman pertama kali dimuat
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            htmlElement.classList.add('dark');
        } else {
            htmlElement.classList.remove('dark');
        }

        // Logika saat tombol diklik
        if (themeToggleBtn) {
            themeToggleBtn.addEventListener('click', function() {
                htmlElement.classList.toggle('dark');

                // Simpan pilihan ke memori browser
                if (htmlElement.classList.contains('dark')) {
                    localStorage.theme = 'dark';
                } else {
                    localStorage.theme = 'light';
                }
            });
        }
    </script>
</body>

</html>