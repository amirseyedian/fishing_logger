<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Dashboard') }}
            </h2>
            <!-- Quick Add Button -->
            <a href="{{ route('trips.create') }}" 
               class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                + Log New Trip
            </a>
        </div>
    </x-slot>

    <div class="py-12 space-y-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Quick Stats -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 mb-10">
                <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow text-center">
                    <p class="text-sm text-gray-500">Total Trips</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">12</p>
                </div>
                <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow text-center">
                    <p class="text-sm text-gray-500">Total Catches</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">46</p>
                </div>
                <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow text-center">
                    <p class="text-sm text-gray-500">Best Catch</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">7.2 lbs</p>
                </div>
            </div>

            <!-- Recent Trips (Grid of 3) -->
            <div class="mb-10">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Recent Trips</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach ($recentTrips ?? [] as $trip)
                        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
                            <h4 class="font-semibold text-gray-900 dark:text-white">{{ $trip->location }}</h4>
                            <p class="text-sm text-gray-500">{{ $trip->date }}</p>
                            <p class="mt-2 text-gray-700 dark:text-gray-300">Catches: {{ $trip->catches_count }}</p>
                            <a href="{{ route('trips.show', $trip->id) }}" class="text-blue-600 hover:underline text-sm mt-2 block">View Details</a>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Catch Highlights -->
            <div class="mb-10">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Catch Highlights</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                    @foreach ($highlightCatches ?? [] as $catch)
                        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
                            <img src="{{ $catch->image_url }}" alt="Catch image" class="rounded mb-2">
                            <p class="text-sm text-gray-700 dark:text-gray-300">{{ $catch->description }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Weather Forecast -->
            <div>
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Weather at Favorite Spot</h3>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    @foreach ($weatherForecast ?? [] as $day)
                        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow text-center">
                            <p class="text-sm text-gray-500">{{ $day['day'] }}</p>
                            <p class="text-lg font-bold text-gray-900 dark:text-white">{{ $day['temp'] }}°C</p>
                            <p class="text-sm text-gray-500">{{ $day['description'] }}</p>
                            <img src="http://openweathermap.org/img/wn/{{ $day['icon'] }}@2x.png" alt="Weather Icon" class="mx-auto w-12">
                        </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
</x-app-layout>