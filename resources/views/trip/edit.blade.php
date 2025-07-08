@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto py-8 space-y-8">
        <h1 class="text-3xl font-bold text-gray-800 dark:text-white">Edit Trip</h1>

        <form action="{{ route('trips.update', $trip->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')
            <!-- Image Edit -->
            <fieldset class="border rounded p-4 dark:border-gray-600">
                <legend class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Trip Images</legend>

                <!-- Existing images preview -->
                <div id="existingImages" class="flex flex-wrap gap-4 mb-4">
                    @foreach ($trip->images as $image)
                        <div class="relative inline-block" style="width: 150px;">
                            <img src="{{ asset('storage/' . $image->image_path) }}" alt="Image {{ $loop->iteration }}"
                                class="rounded shadow object-cover w-full h-32" />

                            <!-- Remove button -->
                            <button type="button"
                                class="absolute top-1 right-1 bg-red-600 text-white rounded-full w-6 h-6 flex items-center justify-center hover:bg-red-700"
                                onclick="removeExistingImage({{ $image->id }}, this)">
                                &times;
                            </button>

                            <!-- Hidden input to keep track of existing images to keep -->
                            <input type="hidden" name="existing_images[]" value="{{ $image->id }}">
                        </div>
                    @endforeach
                </div>

                <!-- Upload new images -->
                <div>
                    <label for="newImages" class="block mb-1 font-medium text-gray-700 dark:text-gray-300">
                        Upload New Images (multiple)
                    </label>
                    <input type="file" id="newImages" name="new_images[]" multiple accept="image/*"
                        class="block w-full text-sm text-gray-600 dark:text-gray-300" />
                    <div id="newImagePreview" class="flex flex-wrap gap-4 mt-2"></div>
                </div>
            </fieldset>

            @push('scripts')
                <script>
                    // Remove existing image: remove from DOM and remove its hidden input (mark as deleted)
                    function removeExistingImage(imageId, btn) {
                        // Remove hidden input corresponding to this image id
                        const existingImagesInputs = document.querySelectorAll('input[name="existing_images[]"]');
                        existingImagesInputs.forEach(input => {
                            if (input.value == imageId) {
                                input.remove();
                            }
                        });
                        // Remove image container div
                        btn.parentElement.remove();
                    }

                    // Optional: Preview new images before upload (client-side)
                    document.getElementById('newImages').addEventListener('change', function (e) {
                        const previewContainer = document.getElementById('newImagePreview');
                        previewContainer.innerHTML = '';
                        Array.from(e.target.files).forEach(file => {
                            const reader = new FileReader();
                            reader.onload = function (event) {
                                const img = document.createElement('img');
                                img.src = event.target.result;
                                img.style.width = '150px';
                                img.style.height = 'auto';
                                img.classList.add('rounded', 'shadow');
                                previewContainer.appendChild(img);
                            };
                            reader.readAsDataURL(file);
                        });
                    });
                </script>
            @endpush

            <!-- Title -->
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Title</label>
                <input type="text" name="title" id="title" value="{{ old('title', $trip->title) }}" required
                    class="mt-1 w-full rounded border-gray-300 dark:bg-gray-800 dark:text-white dark:border-gray-600 focus:ring-blue-500 focus:border-blue-500">
            </div>

            <!-- Location -->
            <div>
                <label for="location" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Location</label>
                <input type="text" name="location" id="location" value="{{ old('location', $trip->location) }}" required
                    class="mt-1 w-full rounded border-gray-300 dark:bg-gray-800 dark:text-white dark:border-gray-600 focus:ring-blue-500 focus:border-blue-500">
            </div>

            <!-- Date -->
            <div>
                <label for="date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Date</label>
                <input type="date" name="date" id="date" value="{{ old('date', $trip->date->format('Y-m-d')) }}" required
                    class="mt-1 w-full rounded border-gray-300 dark:bg-gray-800 dark:text-white dark:border-gray-600 focus:ring-blue-500 focus:border-blue-500">
            </div>

            <!-- Notes -->
            <div>
                <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Notes</label>
                <textarea name="notes" id="notes" rows="3"
                    class="mt-1 w-full rounded border-gray-300 dark:bg-gray-800 dark:text-white dark:border-gray-600 focus:ring-blue-500 focus:border-blue-500">{{ old('notes', $trip->notes) }}</textarea>
            </div>
            <fieldset class="border rounded p-4 dark:border-gray-600">
                <legend class="text-lg font-semibold text-gray-800 dark:text-white mb-2">Weather Info</legend>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="weather_info[precipitation]">Precipitation</label>
                        <input type="number" step="any" name="weather_info[precipitation]"
                            value="{{ old('weather_info.precipitation', $trip->precipitation) }}"
                            class="w-full px-2 py-1 border rounded dark:bg-gray-800 dark:text-white dark:border-gray-600">
                    </div>
                    <div>
                        <label for="weather_info[air_temp]">Air Temp (°C)</label>
                        <input type="number" step="any" name="weather_info[air_temp]"
                            value="{{ old('weather_info.air_temp', $trip->air_temp) }}"
                            class="w-full px-2 py-1 border rounded dark:bg-gray-800 dark:text-white dark:border-gray-600">
                    </div>
                    <div>
                        <label for="weather_info[wind_speed]">Wind Speed</label>
                        <input type="number" step="any" name="weather_info[wind_speed]"
                            value="{{ old('weather_info.wind_speed', $trip->wind_speed) }}"
                            class="w-full px-2 py-1 border rounded dark:bg-gray-800 dark:text-white dark:border-gray-600">
                    </div>
                    <div>
                        <label for="weather_info[wind_direction]">Wind Direction</label>
                        <input type="text" name="weather_info[wind_direction]"
                            value="{{ old('weather_info.wind_direction', $trip->wind_direction) }}"
                            class="w-full px-2 py-1 border rounded dark:bg-gray-800 dark:text-white dark:border-gray-600">
                    </div>
                    <div class="col-span-2">
                        <label for="weather_info[moon_phase]">Moon Phase</label>
                        <input type="text" name="weather_info[moon_phase]"
                            value="{{ old('weather_info.moon_phase', $trip->moon_phase) }}"
                            class="w-full px-2 py-1 border rounded dark:bg-gray-800 dark:text-white dark:border-gray-600">
                    </div>
                </div>
            </fieldset>
            <!-- Catches -->
            @if ($trip->catches && $trip->catches->count())
                <fieldset class="border rounded p-4 dark:border-gray-600">
                    <legend class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Edit Catches</legend>

                    <div class="space-y-6">
                        @foreach ($trip->catches as $index => $catch)
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 border-b pb-4 mb-4">
                                <input type="hidden" name="catches[{{ $index }}][id]" value="{{ $catch->id }}">

                                <div>
                                    <label for="species_{{ $index }}" class="block text-sm font-medium">Species</label>
                                    <input type="text" name="catches[{{ $index }}][species]" id="species_{{ $index }}"
                                        value="{{ old("catches.$index.species", $catch->species) }}"
                                        class="w-full px-2 py-1 border rounded dark:bg-gray-800 dark:text-white dark:border-gray-600">
                                </div>

                                <div>
                                    <label for="quantity_{{ $index }}" class="block text-sm font-medium">Quantity</label>
                                    <input type="number" min="1" name="catches[{{ $index }}][quantity]" id="quantity_{{ $index }}"
                                        value="{{ old("catches.$index.quantity", $catch->quantity) }}"
                                        class="w-full px-2 py-1 border rounded dark:bg-gray-800 dark:text-white dark:border-gray-600">
                                </div>

                                <div>
                                    <label for="weight_{{ $index }}" class="block text-sm font-medium">Weight (kg)</label>
                                    <input type="number" step="0.01" name="catches[{{ $index }}][weight]" id="weight_{{ $index }}"
                                        value="{{ old("catches.$index.weight", $catch->weight) }}"
                                        class="w-full px-2 py-1 border rounded dark:bg-gray-800 dark:text-white dark:border-gray-600">
                                </div>

                                <div>
                                    <label for="length_{{ $index }}" class="block text-sm font-medium">Length (cm)</label>
                                    <input type="number" step="0.1" name="catches[{{ $index }}][length]" id="length_{{ $index }}"
                                        value="{{ old("catches.$index.length", $catch->length) }}"
                                        class="w-full px-2 py-1 border rounded dark:bg-gray-800 dark:text-white dark:border-gray-600">
                                </div>

                                <div>
                                    <label for="depth_{{ $index }}" class="block text-sm font-medium">Depth (m)</label>
                                    <input type="number" step="0.1" name="catches[{{ $index }}][depth]" id="depth_{{ $index }}"
                                        value="{{ old("catches.$index.depth", $catch->depth) }}"
                                        class="w-full px-2 py-1 border rounded dark:bg-gray-800 dark:text-white dark:border-gray-600">
                                </div>

                                <div>
                                    <label for="water_temp_{{ $index }}" class="block text-sm font-medium">Water Temp (°C)</label>
                                    <input type="number" step="0.1" name="catches[{{ $index }}][water_temp]"
                                        id="water_temp_{{ $index }}"
                                        value="{{ old("catches.$index.water_temp", $catch->water_temp) }}"
                                        class="w-full px-2 py-1 border rounded dark:bg-gray-800 dark:text-white dark:border-gray-600">
                                </div>

                                <div class="md:col-span-2">
                                    <label for="bait_{{ $index }}" class="block text-sm font-medium">Bait</label>
                                    <input type="text" name="catches[{{ $index }}][bait]" id="bait_{{ $index }}"
                                        value="{{ old("catches.$index.bait", $catch->bait) }}"
                                        class="w-full px-2 py-1 border rounded dark:bg-gray-800 dark:text-white dark:border-gray-600">
                                </div>

                                <div class="md:col-span-2">
                                    <label for="notes_{{ $index }}" class="block text-sm font-medium">Notes</label>
                                    <textarea name="catches[{{ $index }}][notes]" id="notes_{{ $index }}" rows="2"
                                        class="w-full px-2 py-1 border rounded dark:bg-gray-800 dark:text-white dark:border-gray-600">{{ old("catches.$index.notes", $catch->notes) }}</textarea>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </fieldset>
            @endif
            <!-- Submit -->
            <div class="flex justify-end pt-4 border-t border-gray-200 dark:border-gray-600">
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-lg shadow-md">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
@endsection