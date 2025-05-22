@extends('layouts.app')

@section('header')
    <div class="flex items-center justify-between">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('My Trips') }}
        </h2>
        <a href="{{ route('trips.create') }}" 
           class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
            + Log New Trip
        </a>
    </div>
@endsection

@section('content')
    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div 
                    x-data="{ show: true }" 
                    x-show="show" 
                    x-init="setTimeout(() => show = false, 4000)"
                    class="mb-6 px-4 py-3 rounded-lg bg-green-100 border border-green-400 text-green-800 text-sm font-semibold shadow"
                >
                    {{ session('success') }}
                </div>
            @endif

            @if ($trips->isEmpty())
                <div class="text-center text-gray-600 dark:text-gray-400 mt-10">
                    <p class="text-lg">You haven't logged any trips yet.</p>
                    <a href="{{ route('trip.create') }}" class="mt-4 inline-block bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded shadow">
                        + Log a New Trip
                    </a>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($trips as $trip)
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
                            @if ($trip->image_path)
                                <img src="{{ asset('storage/' . $trip->image_path) }}" alt="{{ $trip->title }}" class="w-full h-48 object-cover">
                            @else
                                <div class="w-full h-48 bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-gray-500">
                                    <span>No Image</span>
                                </div>
                            @endif

                            <div class="p-4">
                                <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-1">
                                    {{ $trip->title }}
                                </h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-1"><strong>Date:</strong> {{ $trip->date }}</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-3"><strong>Location:</strong> {{ $trip->location }}</p>
                                <a href="{{ route('trips.show', $trip->id) }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-3 py-1 rounded">
                                    View Details
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection