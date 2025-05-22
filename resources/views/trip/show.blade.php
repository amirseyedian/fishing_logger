@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-8">

    <!-- Trip Title -->
    <h1 class="text-2xl font-bold text-gray-800 dark:text-white mb-2">{{ $trip->title }}</h1>

    <!-- Trip Info -->
    <div class="mb-6 space-y-1 text-gray-700 dark:text-gray-300">
        <p><strong>Location:</strong> {{ $trip->location }}</p>
        <p><strong>Date:</strong> {{ $trip->date->format('F j, Y') }}</p>
        <p><strong>Coordinates:</strong> {{ $trip->latitude }}, {{ $trip->longitude }}</p>
        @if($trip->notes)
            <p><strong>Notes:</strong> {{ $trip->notes }}</p>
        @endif
    </div>

    <!-- Weather Info -->
    <div class="mb-6 bg-blue-50 dark:bg-blue-900 p-4 rounded-lg">
        <h2 class="text-lg font-semibold text-blue-800 dark:text-white mb-2">Weather Info</h2>
        <ul class="space-y-1 text-blue-900 dark:text-blue-200">
            <li><strong>Precipitation:</strong> {{ $trip->precipitation ?? 'N/A' }}</li>
            <li><strong>Moon Phase:</strong> {{ $trip->moon_phase ?? 'N/A' }}</li>
            <li><strong>Wind Speed:</strong> {{ $trip->wind_speed ?? 'N/A' }}</li>
            <li><strong>Wind Direction:</strong> {{ $trip->wind_direction ?? 'N/A' }}</li>
            <li><strong>Air Temperature:</strong> {{ $trip->air_temp ?? 'N/A' }}</li>
        </ul>
    </div>

    <!-- Trip Images -->
    @if($trip->images->count())
    <div class="mb-6">
        <h2 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">Photos</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @foreach($trip->images as $image)
                <div class="rounded-lg overflow-hidden shadow">
                    <img src="{{ asset('storage/' . $image->image_path) }}" alt="Trip Image" class="w-full h-48 object-cover">
                    @if($image->caption)
                        <p class="text-sm p-2 text-gray-600 dark:text-gray-300">{{ $image->caption }}</p>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Catches -->
    <div class="mb-6">
        <h2 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">Catches</h2>

        @if($trip->catches->count())
            @foreach($trip->catches as $catch)
                <div class="border border-gray-300 dark:border-gray-600 rounded-lg p-4 mb-4 bg-gray-50 dark:bg-gray-800">
                    <p><strong>Species:</strong> {{ $catch->species }}</p>
                    <p><strong>Quantity:</strong> {{ $catch->quantity }}</p>
                    <p><strong>Length:</strong> {{ $catch->length }} cm</p>
                    <p><strong>Weight:</strong> {{ $catch->weight ?? 'N/A' }} kg</p>
                    <p><strong>Depth:</strong> {{ $catch->depth ?? 'N/A' }} m</p>
                    <p><strong>Water Temp:</strong> {{ $catch->water_temp ?? 'N/A' }} °C</p>
                    <p><strong>Bait:</strong> {{ $catch->bait ?? 'N/A' }}</p>
                    <p><strong>Notes:</strong> {{ $catch->notes ?? '—' }}</p>
                </div>
            @endforeach
        @else
            <p class="text-gray-500 dark:text-gray-400">No catches recorded.</p>
        @endif

        <a href="{{ route('trips.index') }}" class="mt-4 inline-block text-blue-600 dark:text-blue-400 hover:underline">
            ← Back to Trips
        </a>
    </div>
</div>
@endsection