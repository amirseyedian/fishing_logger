<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Fishing Trip Logger</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#f0f4f8] dark:bg-[#0a0a0a] text-gray-800 dark:text-white min-h-screen flex flex-col">

    {{-- Navbar --}}
    @include ('layouts.navbar')

    <main class="flex flex-col items-center justify-center flex-1 px-6 py-12 text-center">
        <h1 class="text-4xl font-bold mb-4">🎣 Welcome to Fishing Trip Logger</h1>
        <p class="text-lg text-gray-600 dark:text-gray-300 max-w-xl mb-6">
            Track your fishing adventures, document your catches, and share memories with an image gallery.
        </p>

        @guest

        @else
            <a href="{{ route('trips.index') }}"
                class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-6 rounded">
                Go to My Trips
            </a>
        @endguest
    </main>

    <footer class="text-sm text-center text-gray-500 dark:text-gray-400 py-6">
        © {{ date('Y') }} Fishing Trip Logger. Built with Laravel.
    </footer>

</body>

</html>