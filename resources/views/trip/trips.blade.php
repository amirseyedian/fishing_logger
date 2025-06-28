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
                            class="px-4 py-2 text-sm border-t border-b border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 hover:bg-gray-100 dark:hover:bg-gray-700">
                            List View
                        </button>
                        <button @click="setView('slim')" :class="{ 'bg-blue-600 text-white': view === 'slim' }"
                            class="px-4 py-2 text-sm rounded-r border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 hover:bg-gray-100 dark:hover:bg-gray-700">
                            Slim View
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
                                        <p class="text-sm mt-2">
                                        <strong class="text-gray-600 dark:text-gray-400">Action:</strong>
                                        @switch($trip->action)
                                            @case('hot')
                                                <span class="inline-block px-2 py-1 text-xs bg-red-600 text-white rounded-full font-semibold">Hot</span>
                                                @break
                                            @case('medium')
                                                <span class="inline-block px-2 py-1 text-xs bg-yellow-100 text-yellow-700 rounded-full font-medium">Medium</span>
                                                @break
                                            @case('slow')
                                                <span class="inline-block px-2 py-1 text-xs bg-blue-100 text-blue-700 rounded-full font-medium">Slow</span>
                                                @break
                                            @default
                                                <span class="inline-block px-2 py-1 text-xs bg-gray-100 text-gray-600 rounded-full font-medium">None</span>
                                        @endswitch
                                    </p>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                    {{-- Slim View --}}
                    <div x-show="view === 'slim'" class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm text-left">
                            <thead class="bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 uppercase">
                                <tr>
                                    <th class="px-4 py-2">Title</th>
                                    <th class="px-4 py-2">Date</th>
                                    <th class="px-4 py-2">Location</th>
                                    <th class="px-4 py-2">Moon Phase</th>
                                    <th class="px-4 py-2">Wind Speed</th>
                                    <th class="px-4 py-2">Action</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($trips as $trip)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 cursor-pointer"
                                        onclick="window.location='{{ route('trips.show', $trip->id) }}'">
                                        <td class="px-4 py-2 text-gray-900 dark:text-white font-medium">
                                            {{ $trip->title }}
                                        </td>
                                        <td class="px-4 py-2 text-gray-600 dark:text-gray-300">
                                            {{ \Carbon\Carbon::parse($trip->date)->format('Y-m-d') }}
                                        </td>
                                        <td class="px-4 py-2 text-gray-600 dark:text-gray-300">
                                            {{ $trip->location }}
                                        </td>
                                        <td class="px-4 py-2 text-gray-600 dark:text-gray-300">
                                            {{ $trip->moon_phase ?? 'N/A' }}
                                        </td>
                                        <td class="px-4 py-2 text-gray-600 dark:text-gray-300">
                                            {{ is_numeric($trip->wind_speed) ? $trip->wind_speed . ' km/h' : 'N/A' }}
                                        </td>
                                        <td class="px-4 py-2 text-gray-600 dark:text-gray-300">
                                        @switch($trip->action)
                                            @case('hot')
                                                <span class="bg-red-600 text-white px-2 py-0.5 rounded-full text-xs font-semibold">Hot</span>
                                                @break
                                            @case('medium')
                                                <span class="bg-yellow-100 text-yellow-700 px-2 py-0.5 rounded-full text-xs font-medium">Medium</span>
                                                @break
                                            @case('slow')
                                                <span class="bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full text-xs font-medium">Slow</span>
                                                @break
                                            @default
                                                <span class="bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full text-xs font-medium">None</span>
                                        @endswitch
                                    </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
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
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                        <strong>Moon Phase:</strong> {{ $trip->moon_phase ?? 'N/A' }}
                                        &nbsp;&nbsp;
                                        <strong>Wind Speed:</strong>
                                        {{ is_numeric($trip->wind_speed) ? $trip->wind_speed . ' km/h' : 'N/A' }}
                                    </p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                    <strong>Action:</strong>
                                    @switch($trip->action)
                                        @case('hot')
                                            <span class="inline-block bg-red-600 text-white px-2 py-0.5 rounded-full text-xs font-semibold">Hot</span>
                                            @break
                                        @case('medium')
                                            <span class="inline-block bg-yellow-100 text-yellow-700 px-2 py-0.5 rounded-full text-xs font-medium">Medium</span>
                                            @break
                                        @case('slow')
                                            <span class="inline-block bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full text-xs font-medium">Slow</span>
                                            @break
                                        @default
                                            <span class="inline-block bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full text-xs font-medium">None</span>
                                    @endswitch
                                </p>
                                </div>
                            </a>
                            @if (!$loop->last)
                                <hr class="my-4 border-gray-300 dark:border-gray-700">
                            @endif
                        @endforeach
                    </div>
                    <div class="mt-6">
                        {{ $trips->onEachSide(1)->links('pagination::tailwind') }}
                    </div>
                @endif
            </div>
        </div>
@endsection