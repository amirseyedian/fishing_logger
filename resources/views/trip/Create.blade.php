@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        {{ __('Add New Trip') }}
    </h2>
@endsection
@section('content')

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow space-y-6">
                <form action="{{ route('trips.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <!-- Section: Trip Details -->
                    <div>
                        <h3
                            class="text-lg font-semibold text-gray-900 dark:text-white mb-2 border-b border-gray-300 dark:border-gray-600 pb-1">
                            Trip Details</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Trip
                                    Title</label>
                                <input type="text" name="title" id="title" required
                                    class="mt-1 w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:text-white shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                            </div>

                            <div>
                                <label for="date"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Date</label>
                                <input type="date" name="date" id="date" required
                                    class="mt-1 w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:text-white shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                            </div>

                            <div class="md:col-span-2">
                                <label for="location"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Location Name</label>
                                <input type="text" name="location" id="location" required
                                    class="mt-1 w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:text-white shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                            </div>
                        </div>
                        <!-- Map -->
                        <div class="mt-4">
                            <x-leaflet-picker-map />
                        </div>

                        <!-- Section: Weather -->
                        <div>
                            <h3
                                class="text-lg font-semibold text-gray-900 dark:text-white mb-2 border-b border-gray-300 dark:border-gray-600 pb-1">
                                Weather
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="precipitation"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Precipitation
                                        (mm)</label>
                                    <input type="number" step="0.1" name="weather_info[precipitation]" id="precipitation"
                                        class="mt-1 w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:text-white shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                                </div>

                                <div>
                                    <label for="moon_phase"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Moon
                                        Phase</label>
                                    <input type="text" name="weather_info[moon_phase]" id="moon_phase"
                                        class="mt-1 w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:text-white shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                        placeholder="e.g. Full Moon" />
                                </div>

                                <div>
                                    <label for="wind_speed"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Wind Speed
                                        (km/h)</label>
                                    <input type="number" step="0.1" name="weather_info[wind_speed]" id="wind_speed"
                                        class="mt-1 w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:text-white shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                                </div>

                                <div>
                                    <label for="air_temp"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Air
                                        Temperature
                                        (°C)</label>
                                    <input type="number" step="0.1" name="air_temp" id="air_temp"
                                        class="mt-1 w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:text-white shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                                </div>

                                <div class="md:col-span-2">
                                    <label for="wind_direction"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Wind
                                        Direction</label>
                                    <input type="text" name="weather_info[wind_direction]" id="wind_direction"
                                        class="mt-1 w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:text-white shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                        placeholder="e.g. NW, SE" />
                                </div>
                            </div>
                        </div>
                        <!-- Section: Images -->
                        <div x-data="{ images: [{}] }">
                            <h3
                                class="text-lg font-semibold text-gray-900 dark:text-white mb-2 border-b border-gray-300 dark:border-gray-600 pb-1">
                                Images
                            </h3>

                            <template x-for="(image, index) in images" :key="index">
                                <div
                                    class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4 p-4 border border-gray-300 dark:border-gray-700 rounded-xl bg-gray-50 dark:bg-gray-900">
                                    <div>
                                        <label :for="'images[' + index + '][file]'"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Upload
                                            Image</label>
                                        <input type="file" :name="'images[' + index + '][file]'" accept="image/*"
                                            class="mt-1 w-full text-sm file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-600 file:text-white hover:file:bg-blue-700" />
                                    </div>

                                    <div>
                                        <label :for="'images[' + index + '][caption]'"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Image
                                            Caption</label>
                                        <input type="text" :name="'images[' + index + '][caption]'"
                                            class="mt-1 w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:text-white shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                                    </div>
                                </div>
                            </template>

                            <button type="button" @click="images.push({})"
                                class="mt-2 inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-md text-sm">
                                + Add Another Image
                            </button>
                        </div>
                        <!-- Section: Catches -->
                        <div x-data="{ catches: [{}] }">
                            <h3
                                class="text-lg font-semibold text-gray-900 dark:text-white mb-2 border-b border-gray-300 dark:border-gray-600 pb-1">
                                Catches
                            </h3>

                            <template x-for="(catchItem, index) in catches" :key="index">
                                <div
                                    class="mb-4 p-4 border border-gray-300 dark:border-gray-700 rounded-xl bg-gray-50 dark:bg-gray-900 space-y-3">
                                    <h4 class="font-medium text-gray-700 dark:text-gray-300">Catch #<span
                                            x-text="index + 1"></span></h4>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                        <div>
                                            <label class="block text-sm text-gray-700 dark:text-gray-300">Species</label>
                                            <input type="text" :name="'catches[' + index + '][species]'" required
                                                class="mt-1 w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:text-white shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                                        </div>

                                        <div>
                                            <label class="block text-sm text-gray-700 dark:text-gray-300">Length
                                                (cm)</label>
                                            <input type="number" step="0.1" :name="'catches[' + index + '][length]'"
                                                class="mt-1 w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:text-white shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                                        </div>

                                        <div>
                                            <label class="block text-sm text-gray-700 dark:text-gray-300">Weight
                                                (kg)</label>
                                            <input type="number" step="0.01" :name="'catches[' + index + '][weight]'"
                                                class="mt-1 w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:text-white shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                                        </div>

                                        <div>
                                            <label class="block text-sm text-gray-700 dark:text-gray-300">Quantity</label>
                                            <input type="number" min="1" value="1" :name="'catches[' + index + '][quantity]'"
                                                class="mt-1 w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:text-white shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                                        </div>

                                        <div>
                                            <label class="block text-sm text-gray-700 dark:text-gray-300">Depth (m)</label>
                                            <input type="number" step="0.1" :name="'catches[' + index + '][depth]'"
                                                class="mt-1 w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:text-white shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                                        </div>

                                        <div>
                                            <label class="block text-sm text-gray-700 dark:text-gray-300">Water Temperature
                                                (°C)</label>
                                            <input type="number" step="0.1" :name="'catches[' + index + '][water_temp]'"
                                                class="mt-1 w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:text-white shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                                        </div>

                                        <div class="md:col-span-2">
                                            <label class="block text-sm text-gray-700 dark:text-gray-300">Bait</label>
                                            <input type="text" :name="'catches[' + index + '][bait]'"
                                                class="mt-1 w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:text-white shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                                        </div>

                                        <div class="md:col-span-2">
                                            <label class="block text-sm text-gray-700 dark:text-gray-300">Notes</label>
                                            <textarea :name="'catches[' + index + '][notes]'" rows="2"
                                                class="mt-1 w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:text-white shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                                placeholder="Additional details about the catch..."></textarea>
                                        </div>
                                    </div>
                                </div>
                            </template>

                            <button type="button" @click="catches.push({})"
                                class="mt-2 inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-md text-sm">
                                + Add Another Catch
                            </button>
                        </div>
                        <!-- Submit -->
                        <div class="flex justify-end pt-4 border-t border-gray-200 dark:border-gray-600">
                            <button type="submit"
                                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-lg shadow-md">
                                Save Trip
                            </button>
                        </div>
                </form>
            </div>
        </div>
    </div>
@endsection