@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <!-- Leaflet scrips -->
        <link rel="stylesheet" href="{{ asset('vendor/leaflet/leaflet.css') }}">
        <script src="{{ asset('vendor/leaflet/leaflet.js') }}"></script>

        {{-- Trip Image Preview --}}
        @if($trip->images->count())
            <div x-data="{ selectedImage: '{{ asset('storage/' . $trip->images->first()->image_path) }}' }" class="mb-6">
                <div
                    class="w-full h-[400px] bg-white dark:bg-gray-800 border rounded-lg shadow flex items-center justify-center overflow-hidden">
                    <img :src="selectedImage" alt="Main Image" class="object-contain w-full h-full" />
                </div>
                <div class="mt-2 text-sm text-center text-gray-600 dark:text-gray-300">
                    <template x-for="image in {{ $trip->images->pluck('image_path') }}">
                        <template x-if="selectedImage === '{{ asset('storage/') }}/' + image">
                            <p>
                                {{ $trip->images->where('image_path', '=', 'REPLACE_ME')->first()?->caption }}
                            </p>
                        </template>
                    </template>
                </div>
                <div class="flex mt-4 gap-2 overflow-x-auto">
                    @foreach($trip->images as $image)
                        <img src="{{ asset('storage/' . $image->image_path) }}" alt="Thumbnail"
                            class="w-20 h-20 object-cover rounded border hover:ring-2 ring-blue-500 cursor-pointer"
                            @click="selectedImage = '{{ asset('storage/' . $image->image_path) }}'">
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Trip Details --}}
        <div class="space-y-4 mb-8">
            <h1 class="text-3xl font-bold text-gray-800 dark:text-white flex items-center gap-2">
                <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M12 14l9-5-9-5-9 5 9 5zm0 7l9-5-9-5-9 5 9 5z" />
                </svg>
                {{ $trip->title }}
            </h1>
            <p><strong class="text-gray-700 dark:text-gray-300">Location:</strong> {{ $trip->location }}</p>
            <p><strong class="text-gray-700 dark:text-gray-300">Date:</strong> {{ $trip->date->format('F j, Y') }}</p>
            <p><strong class="text-gray-700 dark:text-gray-300">Coordinates:</strong> {{ $trip->latitude }},
                {{ $trip->longitude }}
            </p>
        </div>
        <!-- Section: Fishing Action -->
        <div class="mb-8">
            <h2 class="text-lg font-semibold text-gray-800 dark:text-white mb-2 border-b border-gray-300 dark:border-gray-600 pb-1">
                Fishing Action
            </h2>

            <p>
                @switch($trip->action)
                    @case('hot')
                        <span class="inline-block px-3 py-1 bg-red-100 text-red-700 rounded-full font-medium">Hot</span>
                        @break
                    @case('medium')
                        <span class="inline-block px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full font-medium">Medium</span>
                        @break
                    @case('slow')
                        <span class="inline-block px-3 py-1 bg-blue-100 text-blue-700 rounded-full font-medium">Slow</span>
                        @break
                    @default
                        <span class="inline-block px-3 py-1 bg-gray-100 text-gray-600 rounded-full font-medium">None</span>
                @endswitch
            </p>
        </div>
        <!--Map-->
        <div id="trip-map" style="height: 400px;">
            <h2 class="text-xl font-semibold mt-6">Trip Location</h2>
            <br>
            <x-leaflet-map :latitude="$trip->latitude" :longitude="$trip->longitude" :label="$trip->title" />
        </div>
        <br>
        <!-- trip notes -->
        @if($trip->notes)
            <div class="mb-8 bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-3 flex items-center gap-2">
                    <svg class="w-5 h-5 text-yellow-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M5 13l4 4L19 7" />
                    </svg>
                    Trip Notes
                </h2>
                <div class="prose dark:prose-invert max-w-none">
                    {!! nl2br(e($trip->notes)) !!}
                </div>
            </div>
        @endif
        <br>
        {{-- Weather Info --}}
        <div class="mb-8 bg-blue-50 dark:bg-blue-900 p-5 rounded-lg shadow">
            <h2 class="text-2xl font-semibold text-blue-800 dark:text-white mb-3 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M12 2a9 9 0 010 18 9 9 0 010-18z" />
                </svg>
                Weather Info
            </h2>
            <ul class="space-y-1 text-sm">
                <li><strong>Precipitation:</strong> {{ $trip->precipitation ?? 'N/A' }}</li>
                <li><strong>Moon Phase:</strong> {{ $trip->moon_phase ?? 'N/A' }}</li>
                <li><strong>Wind Speed:</strong> {{ $trip->wind_speed ?? 'N/A' }}</li>
                <li><strong>Wind Direction:</strong> {{ $trip->wind_direction ?? 'N/A' }}</li>
                <li><strong>Air Temperature:</strong> {{ $trip->air_temp ?? 'N/A' }}</li>
            </ul>
        </div>

        {{-- Catches --}}
        <div class="mb-10">
            <h2 class="text-2xl font-semibold text-gray-800 dark:text-white mb-4 flex items-center gap-2">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M9 17v-6a3 3 0 016 0v6m-6 0a3 3 0 006 0m-6 0v2a2 2 0 104 0v-2" />
                </svg>
                Catches
            </h2>

            @if($trip->catches->count())
                <div class="space-y-6">
                    @foreach($trip->catches as $catch)
                        <div
                            class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg shadow p-6 text-[17px] leading-relaxed">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-3 text-gray-800 dark:text-gray-100">
                                <div>
                                    <p><strong>Species:</strong> {{ $catch->species }}</p>
                                    <p><strong>Quantity:</strong> {{ $catch->quantity }}</p>
                                    <p><strong>Length:</strong> {{ $catch->length }} cm</p>
                                    <p><strong>Weight:</strong> {{ $catch->weight ?? 'N/A' }} kg</p>
                                </div>
                                <div>
                                    <p><strong>Depth:</strong> {{ $catch->depth ?? 'N/A' }} m</p>
                                    <p><strong>Water Temp:</strong> {{ $catch->water_temp ?? 'N/A' }} °C</p>
                                    <p><strong>Bait:</strong> {{ $catch->bait ?? 'N/A' }}</p>
                                </div>
                            </div>
                            @if($catch->notes)
                                <div class="mt-4 text-gray-700 dark:text-gray-300 text-base">
                                    <p><strong>Notes:</strong></p>
                                    <div
                                        class="mt-1 p-3 bg-gray-100 dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded text-[15px] leading-snug">
                                        {{ $catch->notes }}
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 dark:text-gray-400 italic">No catches recorded for this trip.</p>
            @endif
        </div>
        {{-- Action Buttons --}}
        <div class="flex flex-wrap items-center gap-4 mb-6">
            <a href="{{ route('trips.edit', $trip->id) }}"
                class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M15.232 5.232l3.536 3.536M9 11l6 6M3 21h18" />
                </svg>
                Edit Trip
            </a>

            <form action="{{ route('trips.destroy', $trip->id) }}" method="POST"
                onsubmit="return confirm('Are you sure you want to delete this trip?');">
                @csrf
                @method('DELETE')
                <button type="submit"
                    class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    Delete Trip
                </button>
            </form>
        </div>

        <a href="{{ route('trips.index') }}"
            class="inline-flex items-center text-blue-600 dark:text-blue-400 hover:underline">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M15 19l-7-7 7-7" />
            </svg>
            Back to Trips
        </a>
    </div>
@endsection