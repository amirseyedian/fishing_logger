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
                            <div class="md:col-span-2">
                                <label for="wind_direction"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Trip Notes</label>
                                <div class="md:col-span-2">
                                    <textarea name="notes" id="notes" rows="5"
                                        class="mt-1 w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:text-white shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                        placeholder="How was your trip? Any details you want to remember...">{{ old('notes') }}</textarea>
                                </div>
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
                        <div>
                            <h3
                                class="text-lg font-semibold text-gray-900 dark:text-white mb-2 border-b border-gray-300 dark:border-gray-600 pb-1">
                                Images
                            </h3>

                            <!-- AJAX multi-image upload section -->
                            <div class="mt-6">
                                <label for="imageUpload"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Upload Images (You Can Select Multiple)
                                </label>
                                <input type="file" id="imageUpload" multiple accept="image/*"
                                    class="block w-full text-sm text-gray-600 dark:text-gray-300 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-600 file:text-white hover:file:bg-blue-700" />

                                <!-- Image preview area -->
                                <div id="imagePreviewContainer" class="flex flex-wrap mt-4 gap-2"></div>

                                <div id="uploadedImageInputs"></div>
                            </div>
                        </div>
                        <br>
                        <!-- Section: Action -->
                        <div>
                            <h3
                                class="text-lg font-semibold text-gray-900 dark:text-white mb-2 border-b border-gray-300 dark:border-gray-600 pb-1">
                                Fishing Action
                            </h3>
                            <select name="action" id="action"
                                class="mt-1 w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:text-white shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <option value="none" {{ old('action', 'none') == 'none' ? 'selected' : '' }}>None</option>
                                <option value="hot" {{ old('action') == 'hot' ? 'selected' : '' }}>Hot</option>
                                <option value="medium" {{ old('action') == 'medium' ? 'selected' : '' }}>Medium</option>
                                <option value="slow" {{ old('action') == 'slow' ? 'selected' : '' }}>Slow</option>
                            </select>
                            @error('action')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <br>
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
                                            <input type="text" :name="'catches[' + index + '][species]'"
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
                                            <input type="number" min="1" value="1"
                                                :name="'catches[' + index + '][quantity]'"
                                                class="mt-1 w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:text-white shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                                        </div>

                                        <div>
                                            <label class="block text-sm text-gray-700 dark:text-gray-300">Depth
                                                (m)</label>
                                            <input type="number" step="0.1" :name="'catches[' + index + '][depth]'"
                                                class="mt-1 w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:text-white shadow-sm focus:ring-blue-500 focus:border-blue-500" />
                                        </div>

                                        <div>
                                            <label class="block text-sm text-gray-700 dark:text-gray-300">Water
                                                Temperature
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
@push('scripts')
    <script>
        document.getElementById('imageUpload').addEventListener('change', function (e) {
            const files = Array.from(e.target.files);
            if (files.length === 0) return;

            files.forEach(file => uploadImageWithProgress(file));
        });

        function uploadImageWithProgress(file) {
            const MAX_FILE_SIZE_MB = 5;
            if (file.size > MAX_FILE_SIZE_MB * 1024 * 1024) {
                alert(`${file.name} exceeds the ${MAX_FILE_SIZE_MB}MB limit.`);
                return;
            }

            const formData = new FormData();
            formData.append('image', file);

            const xhr = new XMLHttpRequest();

            const container = document.createElement('div');
            container.style.position = 'relative';
            container.style.display = 'inline-block';
            container.style.margin = '5px';

            const img = document.createElement('img');
            img.style.maxWidth = '120px';
            img.style.maxHeight = '120px';
            img.style.objectFit = 'cover';
            img.style.borderRadius = '6px';
            img.style.boxShadow = '0 0 5px rgba(0,0,0,0.2)';
            img.style.display = 'block';

            const progress = document.createElement('progress');
            progress.value = 0;
            progress.max = 100;
            progress.style.width = '100%';
            progress.style.marginTop = '5px';

            const closeBtn = document.createElement('button');
            closeBtn.textContent = '×';
            closeBtn.style.position = 'absolute';
            closeBtn.style.top = '0px';
            closeBtn.style.right = '5px';
            closeBtn.style.background = 'rgba(0,0,0,0.5)';
            closeBtn.style.color = '#fff';
            closeBtn.style.border = 'none';
            closeBtn.style.borderRadius = '50%';
            closeBtn.style.cursor = 'pointer';
            closeBtn.style.width = '22px';
            closeBtn.style.height = '22px';
            closeBtn.style.lineHeight = '22px';
            closeBtn.style.padding = '0';

            closeBtn.addEventListener('click', () => {
                container.remove();
                if (hiddenInput) hiddenInput.remove();
            });

            const previewContainer = document.getElementById('imagePreviewContainer');
            previewContainer.appendChild(container);
            container.appendChild(img);
            container.appendChild(progress);
            container.appendChild(closeBtn);

            let hiddenInput;

            xhr.upload.addEventListener('progress', (e) => {
                if (e.lengthComputable) {
                    const percentComplete = Math.round((e.loaded / e.total) * 100);
                    progress.value = percentComplete;
                }
            });

            xhr.onreadystatechange = function () {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    progress.remove();

                    if (xhr.status === 200) {
                        const response = JSON.parse(xhr.responseText);
                        img.src = response.url;

                        // Create hidden input with image path
                        hiddenInput = document.createElement('input');
                        hiddenInput.type = 'hidden';
                        hiddenInput.name = 'uploaded_images[]';
                        hiddenInput.value = response.path;
                        document.getElementById('uploadedImageInputs').appendChild(hiddenInput);
                    } else {
                        img.src = '';
                        alert("Upload failed for " + file.name);
                        container.remove();
                    }
                }
            };

            xhr.open("POST", "{{ route('trips.uploadImage') }}", true);
            xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');
            xhr.send(formData);
        }
    </script>
    @endpush                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    