<x-app-layout>
    <!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Add New Trip') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
                <form action="{{ route('trip.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- Trip Title -->
                    <div class="mb-4">
                        <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Trip Title</label>
                        <input type="text" name="title" id="title" required
                               class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white" />
                    </div>

                    <!-- Location -->
                    <div class="mb-4">
                        <label for="location" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Location</label>
                        <input type="text" name="location" id="location" required
                               class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white" />
                    </div>

                    <!-- Map Location Picker -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Select Location on Map</label>
                        <div id="map" style="height: 300px;" class="rounded shadow-md mb-2"></div>
                        <input type="hidden" name="latitude" id="latitude">
                        <input type="hidden" name="longitude" id="longitude">
                    </div>

                    <!-- Date -->
                    <div class="mb-4">
                        <label for="date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Date</label>
                        <input type="date" name="date" id="date" required
                               class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white" />
                    </div>

                    <!-- Notes -->
                    <div class="mb-4">
                        <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Notes</label>
                        <textarea name="notes" id="notes" rows="4"
                                  class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                  placeholder="What happened during this trip?"></textarea>
                    </div>

                    <!-- Weather Info (JSON input) -->
                    <div class="mb-4">
                        <label for="weather_info" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Weather Info (JSON)</label>
                        <textarea name="weather_info" id="weather_info" rows="3"
                                  class="mt-1 block w-full font-mono text-sm rounded-md shadow-sm border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                  placeholder='{"temp": 22, "condition": "Sunny"}'></textarea>
                    </div>

                    <!-- Optional Catch Image Upload -->
                    <div class="mb-4">
                        <label for="image_path" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Upload Trip Image (Optional)</label>
                        <input type="file" name="image_path" id="image_path" accept="image/*"
                               class="mt-1 block w-full text-sm text-gray-500 dark:text-gray-300 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-blue-600 file:text-white hover:file:bg-blue-700" />
                    </div>

                    <!-- Image Caption -->
                    <div class="mb-6">
                        <label for="caption" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Image Caption (Optional)</label>
                        <input type="text" name="caption" id="caption"
                               class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white" />
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end">
                        <button type="submit"
                                class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Save Trip
                        </button>
                    </div>
                <!-- Catches -->
<h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-2">Catches</h3>

<div x-data="{ catches: [{}] }">
    <template x-for="(catchItem, index) in catches" :key="index">
        <div class="mb-6 p-4 border border-gray-300 rounded-md dark:border-gray-700 dark:bg-gray-900">
            <h4 class="font-medium mb-2 text-gray-700 dark:text-gray-300">Catch #<span x-text="index + 1"></span></h4>

            <div class="mb-2">
    <label class="block text-sm text-gray-700 dark:text-gray-300">Length (cm)</label>
    <input type="number" step="0.1" :name="'catches['+index+'][length]'" 
                       class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white" />
            </div>

            <div class="mb-2">
    <label class="block text-sm text-gray-700 dark:text-gray-300">Quantity</label>
    <input type="number" min="1" :name="'catches['+index+'][quantity]'" value="1"
                       class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white" />
            </div>

            <div class="mb-2">
    <label class="block text-sm text-gray-700 dark:text-gray-300">Bait</label>
    <input type="text" :name="'catches['+index+'][bait]'" 
                       class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white" />
            </div>

            <div>
                <label class="block text-sm text-gray-700 dark:text-gray-300">Notes</label>
                <textarea :name="'catches['+index+'][notes]'" rows="2"
                          class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                          placeholder="Additional details about the catch..."></textarea>
            </div>
        </div>
    </template>

    <!-- Add Another Catch Button -->
    <button type="button" @click="catches.push({})"
            class="mb-4 inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
        + Add Another Catch
    </button>
</div>
                </form>
            </div>
        </div>
    </div>

    <!-- Leaflet Map JS -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const map = L.map('map').setView([20.5937, 78.9629], 5); // Default center (India)

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            let marker;

            map.on('click', function (e) {
                const { lat, lng } = e.latlng;

                if (marker) {
                    marker.setLatLng([lat, lng]);
                } else {
                    marker = L.marker([lat, lng]).addTo(map);
                }

                document.getElementById('latitude').value = lat;
                document.getElementById('longitude').value = lng;
            });
        });
    </script>
</x-app-layout>