<?php
namespace App\Http\Controllers;

use App\Models\Trip;
use App\Models\TripImage;
use App\Models\Catches;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TripController extends Controller
{
    // Show all trips for the authenticated user
    public function index()
    {
        $trips = auth()->user()->trips()->latest()->get();
        return view('trip.trips', compact('trips'));
    }

    // Show form to create a new trip
    public function create()
    {
        return view('trip.create');
    }

    // Store a newly created trip
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'date' => 'required|date',
            'notes' => 'nullable|string',
            'weather_info.precipitation' => 'nullable|string|max:255',
            'weather_info.moon_phase' => 'nullable|string|max:255',
            'weather_info.wind_speed' => 'nullable|string|max:255',
            'weather_info.wind_direction' => 'nullable|string|max:255',
            'weather_info.air_temp' => 'nullable|string|max:255',

            // Updated for multiple images
            'images' => 'nullable|array',
            'images.*.file' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'images.*.caption' => 'nullable|string|max:255',

            'catches' => 'nullable|array',
            'catches.*.species' => 'required_with:catches|string|max:255',
            'catches.*.weight' => 'nullable|numeric',
            'catches.*.quantity' => 'nullable|numeric',
            'catches.*.water_temp' => 'nullable|numeric',
            'catches.*.bait' => 'nullable|string|max:255',
            'catches.*.depth' => 'nullable|string|max:255',
            'catches.*.length' => 'nullable|numeric',
            'catches.*.notes' => 'nullable|string',
        ]);

        $trip = Trip::create([
            'user_id' => Auth::id(),
            'title' => $validated['title'],
            'location' => $validated['location'],
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude'],
            'date' => $validated['date'],
            'notes' => $validated['notes'] ?? null,
            // Weather fields
            'precipitation' => $request->input('weather_info.precipitation'),
            'moon_phase' => $request->input('weather_info.moon_phase'),
            'wind_speed' => $request->input('weather_info.wind_speed'),
            'air_temp' => $request->input('air_temp'),
        ]);

        if ($request->hasFile('image_path')) {
            $path = $request->file('image_path')->store('trip_images', 'public');

            TripImage::create([
                'trip_id' => $trip->id,
                'image_path' => $path,
                'caption' => $validated['caption'] ?? null,
            ]);
        }

        if (!empty($validated['catches'])) {
            foreach ($validated['catches'] as $catchData) {
                Catches::create([
                    'trip_id' => $trip->id,
                    'species' => $catchData['species'],
                    'weight' => $catchData['weight'] ?? null,
                    'length' => $catchData['length'] ?? null,
                    'quantity' => $catchData['quantity'] ?? null,
                    'water_temp' => $catchData['water_temp'] ?? null,
                    'depth' => $catchData['depth'] ?? null,
                    'bait' => $catchData['bait'] ?? null,
                    'notes' => $catchData['notes'] ?? null,
                ]);
            }
        }
        if (!empty($validated['images'])) {
            foreach ($validated['images'] as $imageData) {
                if (isset($imageData['file']) && $imageData['file'] instanceof \Illuminate\Http\UploadedFile) {
                    $path = $imageData['file']->store('trip_images', 'public');

                    TripImage::create([
                        'trip_id' => $trip->id,
                        'image_path' => $path,
                        'caption' => $imageData['caption'] ?? null,
                    ]);
                }
            }
        }
        return redirect()->route('trips.index')->with('success', 'Trip successfully added!');
    }

    // Show a specific trip
    public function show($id)
    {
        $trip = auth()->user()->trips()->findOrFail($id);
        return view('trip.show', compact('trip'));
    }

    // Show form to edit a trip
    public function edit($id)
    {
        $trip = auth()->user()->trips()->findOrFail($id);
        return view('trip.edit', compact('trip'));
    }

    // Update a specific trip
    public function update(Request $request, $id)
    {
        $trip = auth()->user()->trips()->findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'date' => 'required|date',
            'notes' => 'nullable|string',
            'weather_info' => 'nullable|json',
            'air_temp' => 'nullable|numeric',
        ]);

        $trip->update($validated);

        return redirect()->route('trips.index')->with('success', 'Trip updated successfully.');
    }

    // Delete a specific trip
    public function destroy($id)
    {
        $trip = auth()->user()->trips()->findOrFail($id);

        // Optionally delete related catches and images
        Catches::where('trip_id', $trip->id)->delete();
        TripImage::where('trip_id', $trip->id)->delete();

        $trip->delete();

        return redirect()->route('trips.index')->with('success', 'Trip deleted successfully.');
    }
}
