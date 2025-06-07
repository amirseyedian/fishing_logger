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

    <main class="flex flex-col items-center justify-center flex-1 px-6 py-12 text-center space-y-6">
        <h1 class="text-5xl font-extrabold tracking-tight">🎣 Welcome to Fishing Trip Logger</h1>
        <p class="text-lg text-gray-600 dark:text-gray-300 max-w-xl">
            Track your fishing adventures, document your catches, and share memories with an image gallery.
        </p>

        @auth
            <a href="{{ route('trips.index') }}"
                class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-6 rounded-lg shadow">
                Go to My Trips
            </a>
        @endauth

        <!-- Weather Widget -->
        <div x-data="weatherWidget()" x-init="fetchWeather()"
            class="mt-10 bg-white dark:bg-gray-800 p-6 rounded-xl shadow-md w-full max-w-md">
            <h2 class="text-xl font-semibold mb-4">🌤️ Today's Weather</h2>
            <template x-if="loading">
                <p class="text-gray-500">Loading weather...</p>
            </template>
            <template x-if="!loading">
                <div>
                    <p class="text-2xl font-bold" x-text="weather.temp + '°C'"></p>
                    <p class="text-sm text-gray-600 dark:text-gray-400" x-text="'Condition: ' + weather.condition"></p>
                </div>
            </template>
        </div>
    </main>

    <footer class="text-sm text-center text-gray-500 dark:text-gray-400 py-6">
        © {{ date('Y') }} Fishing Trip Logger. Built with Laravel.
    </footer>

    <script>
        function weatherWidget() {
            return {
                loading: true,
                weather: {
                    temp: '',
                    condition: ''
                },
                fetchWeather() {
                    // Example: Adelaide (Latitude -34.93, Longitude 138.60)
                    fetch('https://api.open-meteo.com/v1/forecast?latitude=-34.93&longitude=138.60&current_weather=true')
                        .then(res => res.json())
                        .then(data => {
                            this.weather.temp = data.current_weather.temperature;
                            this.weather.condition = data.current_weather.weathercode === 0 ? 'Clear' : 'Cloudy'; // simplified
                            this.loading = false;
                        });
                }
            }
        }
    </script>

</body>

</html>