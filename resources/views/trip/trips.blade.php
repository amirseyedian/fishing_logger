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
    <div x-data="{ view: 'grid' }" class="py-10">
        <div x-data="{
        view: '{{ request()->query('view', 'grid') }}',
        setView(newView) {
            this.view = newView;
            const url = new URL(window.location);
            url.searchParams.set('view', newView);
            url.searchParams.delete('page'); // Reset pagination to page 1
            window.location = url.toString();
        }
    }" class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                    class="mb-6 px-4 py-3 rounded-lg bg-green-100 border border-green-400 text-green-800 text-sm font-semibold shadow">
                    {{ session('success') }}
                </div>
            @endif

            @if ($trips->isEmpty())
                <div class="text-center text-gray-600 dark:text-gray-400 mt-10">
                    <p class="text-lg">You haven't logged any trips yet.</p>
                    <a href="{{ route('trips.create') }}"
                        class="mt-4 inline-block bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded shadow">
                        + Log a New Trip
                    </a>
                </div>
            @else
                {{-- View Toggle Buttons --}}
                                <div class="mb-4 flex justify-end">
                    <button @click="setView('grid')" :class="{ 'bg-blue-600 text-white': view === 'grid' }"
                        class="px-4 py-2 text-sm rounded-l border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 hover:bg-gray-100 dark:hover:bg-gray-700">
                        Grid View
                    </button>
                    <button @click="setView('list')" :class="{ 'bg-blue-600 text-white': view === 'list' }"
                        class="px-4 py-2 text-sm rounded-r border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 hover:bg-gray-100 dark:hover:bg-gray-700">
                        List View
                    </button>
                </div>

                {{-- Grid View --}}
                <div x-show="view === 'grid'" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($trips as $trip)
                        <a href="{{ route('trips.show', $trip->id) }}" class="block transform transition hover:scale-[1.01]">
                            <div
                                class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden hover:ring-2 hover:ring-blue-500 transition">
                                @php
                                    $firstImage = $trip->images->first();
                                @endphp

                                @if ($firstImage)
                                    <img src="{{ asset('storage/' . $firstImage->image_path) }}" alt="{{ $trip->title }}"
                                        class="w-full h-48 object-cover">
                                @else
                                    <div
                                        class="w-full h-48 bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-gray-500">
                                        <span>No Image Available</span>
                                    </div>
                                @endif

                                <div class="p-4">
                                    <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-1">
                                        {{ $trip->title }}
                                    </h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">
                                        <strong>Date:</strong> {{ \Carbon\Carbon::parse($trip->date)->format('F j, Y') }}
                                    </p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        <strong>Location:</strong> {{ $trip->location }}
                                    </p>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>

                {{-- List View --}}
                <div x-show="view === 'list'">
    @foreach ($trips as $index => $trip)
        <a href="{{ route('trips.show', $trip->id) }}"
            class="block p-4 bg-white dark:bg-gray-800 shadow-sm rounded-md hover:ring-2 hover:ring-blue-500 transition">
            <div>
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">
                    {{ $trip->title }}
                </h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                    <strong>Date:</strong> {{ \Carbon\Carbon::parse($trip->date)->format('F j, Y') }}
                    &nbsp;&nbsp;
                    <strong>Location:</strong> {{ $trip->location }}
                </p>
                @if($trip->notes)
                    <p class="text-sm text-gray-500 dark:text-gray-300 mt-1">
                        {{ Str::limit($trip->notes, 120) }}
                    </p>
                @endif
            </div>
        </a>
        @if (!$loop->last)
            <hr class="my-4 border-gray-300 dark:border-gray-700">
        @endif
    @endforeach
</div>
            @endif
        </div>
    </div>
@endsection