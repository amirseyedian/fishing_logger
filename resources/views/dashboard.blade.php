@extends('layouts.app')

@section('header')
    <div class="flex items-center justify-between">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
        <a href="{{ route('trips.create') }}"
            class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
            + Log New Trip
        </a>
    </div>
@endsection

@section('content')
    <div class="py-12 space-y-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Quick Stats + Weather -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6 mb-10">
                <!-- Total Trips -->
                <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow text-center">
                    <p class="text-sm text-gray-500">Total Trips</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $totalTrips }}</p>
                </div>

                <!-- Total Catches -->
                <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow text-center">
                    <p class="text-sm text-gray-500">Total Catches</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $totalCatches }}</p>
                </div>

                <!-- Best Catch -->
                <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow text-center">
                    <p class="text-sm text-gray-500">Best Catch (by weight)</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $bestCatch }} KGs</p>
                </div>

                <!-- Weather -->
                <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow text-center">
                    @if (!empty($weatherForecast))
                        <p class="text-sm text-gray-500">{{ $weatherForecast[0]['day'] }}</p>
                        <p class="text-lg font-bold text-gray-900 dark:text-white">{{ $weatherForecast[0]['temp'] }}°C</p>
                        <p class="text-sm text-gray-500">{{ $weatherForecast[0]['description'] }}</p>
                        <img src="http://openweathermap.org/img/wn/{{ $weatherForecast[0]['icon'] }}@2x.png" alt="Weather Icon"
                            class="mx-auto w-12">
                    @else
                        <p class="text-sm text-gray-500">Weather</p>
                        <p class="text-lg font-bold text-gray-900 dark:text-white">N/A</p>
                        <p class="text-sm text-gray-500">No data</p>
                    @endif
                </div>
            </div>
            <!-- Recent Trips -->
            <div class="mb-10">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Recent Trips</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @forelse ($recentTrips as $trip)
                        <a href="{{ route('trips.show', $trip->id) }}" class="block group">
                            <div
                                class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow group-hover:shadow-lg transition duration-200">
                                @php
                                    $firstImage = $trip->images->first();
                                @endphp

                                @if ($firstImage)
                                    <img src="{{ asset('storage/' . $firstImage->image_path) }}" alt="{{ $trip->title }}"
                                        class="w-full h-48 object-cover rounded mb-2 w-full h-48 object-cover">
                                @else
                                    <div
                                        class="bg-gray-200 dark:bg-gray-700 mb-2 w-full h-48 rounded flex items-center justify-center text-gray-500 dark:text-gray-400">
                                        No Image
                                    </div>
                                @endif
                                <h4 class="font-semibold text-gray-900 dark:text-white">{{ $trip->title }}</h4>
                                <p class="text-sm text-gray-500">{{ $trip->date->format('M d, Y') }}</p>
                                <p class="mt-2 text-gray-700 dark:text-gray-300">Catches: {{ $trip->catches->count() }}</p>
                            </div>
                        </a>
                    @empty
                        <p class="text-gray-500 dark:text-gray-400">No trips found.</p>
                    @endforelse
                </div>
            </div>


        </div>
    </div>

@endsection