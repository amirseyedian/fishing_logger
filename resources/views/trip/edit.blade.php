@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto py-8">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white mb-6">Edit Trip</h1>

        <form action="{{ route('trips.update', $trip->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Title -->
            <div>
                <label for="title" class="block font-medium text-gray-700 dark:text-gray-200">Title</label>
                <input type="text" name="title" id="title" value="{{ old('title', $trip->title) }}" required
                    class="w-full px-4 py-2 border rounded dark:bg-gray-800 dark:text-white dark:border-gray-600">
            </div>

            <!-- Location -->
            <div>
                <label for="location" class="block font-medium text-gray-700 dark:text-gray-200">Location</label>
                <input type="text" name="location" id="location" value="{{ old('location', $trip->location) }}" required
                    class="w-full px-4 py-2 border rounded dark:bg-gray-800 dark:text-white dark:border-gray-600">
            </div>

            <!-- Coordinates -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="latitude" class="block font-medium text-gray-700 dark:text-gray-200">Latitude</label>
                    <input type="number" step="any" name="latitude" id="latitude"
                        value="{{ old('latitude', $trip->latitude) }}" required
                        class="w-full px-4 py-2 border rounded dark:bg-gray-800 dark:text-white dark:border-gray-600">
                </div>
                <div>
                    <label for="longitude" class="block font-medium text-gray-700 dark:text-gray-200">Longitude</label>
                    <input type="number" step="any" name="longitude" id="longitude"
                        value="{{ old('longitude', $trip->longitude) }}" required
                        class="w-full px-4 py-2 border rounded dark:bg-gray-800 dark:text-white dark:border-gray-600">
                </div>
            </div>

            <!-- Date -->
            <div>
                <label for="date" class="block font-medium text-gray-700 dark:text-gray-200">Date</label>
                <input type="date" name="date" id="date" value="{{ old('date', $trip->date->format('Y-m-d')) }}" required
                    class="w-full px-4 py-2 border rounded dark:bg-gray-800 dark:text-white dark:border-gray-600">
            </div>

            <!-- Notes -->
            <div>
                <label for="notes" class="block font-medium text-gray-700 dark:text-gray-200">Notes</label>
                <textarea name="notes" id="notes" rows="3"
                    class="w-full px-4 py-2 border rounded dark:bg-gray-800 dark:text-white dark:border-gray-600">{{ old('notes', $trip->notes) }}</textarea>
            </div>

            <!-- Weather Info -->
            <fieldset class="border rounded p-4 dark:border-gray-600">
                <legend class="text-lg font-semibold text-gray-800 dark:text-white mb-2">Weather Info</legend>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="weather_info[precipitation]">Precipitation</label>
                        <input type="number" step="any" name="weather_info[precipitation]"
                            value="{{ old('weather_info.precipitation', $trip->weather_info['precipitation'] ?? '') }}"
                            class="w-full px-2 py-1 border rounded dark:bg-gray-800 dark:text-white dark:border-gray-600">
                    </div>
                    <div>
                        <label for="weather_info[air_temp]">Air Temp (°C)</label>
                        <input type="number" step="any" name="weather_info[air_temp]"
                            value="{{ old('weather_info.air_temp', $trip->weather_info['air_temp'] ?? '') }}"
                            class="w-full px-2 py-1 border rounded dark:bg-gray-800 dark:text-white dark:border-gray-600">
                    </div>
                    <div>
                        <label for="weather_info[wind_speed]">Wind Speed</label>
                        <input type="number" step="any" name="weather_info[wind_speed]"
                            value="{{ old('weather_info.wind_speed', $trip->weather_info['wind_speed'] ?? '') }}"
                            class="w-full px-2 py-1 border rounded dark:bg-gray-800 dark:text-white dark:border-gray-600">
                    </div>
                    <div>
                        <label for="weather_info[wind_direction]">Wind Direction</label>
                        <input type="text" name="weather_info[wind_direction]"
                            value="{{ old('weather_info.wind_direction', $trip->weather_info['wind_direction'] ?? '') }}"
                            class="w-full px-2 py-1 border rounded dark:bg-gray-800 dark:text-white dark:border-gray-600">
                    </div>
                    <div class="col-span-2">
                        <label for="weather_info[moon_phase]">Moon Phase</label>
                        <input type="text" name="weather_info[moon_phase]"
                            value="{{ old('weather_info.moon_phase', $trip->weather_info['moon_phase'] ?? '') }}"
                            class="w-full px-2 py-1 border rounded dark:bg-gray-800 dark:text-white dark:border-gray-600">
                    </div>
                </div>
            </fieldset>

            <!-- Submit -->
            <div class="flex justify-end">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
@endsection