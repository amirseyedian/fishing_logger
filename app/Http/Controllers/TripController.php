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
    public function index(Request $request)
    {
        $view = $request->query('view', 'grid');

        $trips = auth()->user()
            ->trips()
            ->latest()
            ->paginate(50)
            ->withQueryString();

        return view('trip.trips', compact('trips', 'view'));
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
            'action' => 'required|in:hot,medium,slow,none',

            // Updated for multiple images
            'images' => 'nullable|array',
            'images.*.file' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'images.*.caption' => 'nullable|string|max:255',

            'catches' => 'nullable|array',
            'catches.*.species' => 'nullable|string|max:255',
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
            'precipitation' => $request->input('weather_info.precipitation'),
            'moon_phase' => $request->input('weather_info.moon_phase'),
            'wind_speed' => $request->input('weather_info.wind_speed'),
            'wind_direction' => $request->input('weather_info.wind_direction'),
            'air_temp' => $validated['weather_info']['air_temp'] ?? null,
            'water_temperature' => $request->input('weather_info.water_temperature'),
            'action' => $request->input('action'),
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
                    'species' => $catchData['species'] ?? null,
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
        if ($request->has('uploaded_images') && is_array($request->input('uploaded_images'))) {
            foreach ($request->input('uploaded_images') as $tempPath) {
                // Skip if null or not a string
                if (!$tempPath || !is_string($tempPath)) {
                    continue;
                }

                $newPath = str_replace('temp_trip_images/', 'trip_images/', $tempPath);

                if (Storage::disk('public')->exists($tempPath)) {
                    Storage::disk('public')->move($tempPath, $newPath);

                    TripImage::create([
                        'trip_id' => $trip->id,
                        'image_path' => $newPath,
                        'caption' => null,
                    ]);
                }
            }
        }
        return redirect()->route('trips.index')->with('success', 'Trip successfully added!');
    }
    public function uploadTempImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:5120', // 5MB
        ]);

        $path = $request->file('image')->store('temp_trip_images', 'public');

        return response()->json(['path' => $path, 'url' => Storage::url($path)]);
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
            'action' => 'required|in:hot,medium,slow,none',
        ]);

        $trip->update($validated);

        return redirect()->route('trips.index')->with('success', 'Trip updated successfully.');
    }

    // Delete a specific trip
    public function destroy($id)
    {
        $trip = auth()->user()->trips()->findOrFail($id);

        Catches::where('trip_id', $trip->id)->delete();

        $tripImages = TripImage::where('trip_id', $trip->id)->get();

        foreach ($tripImages as $image) {
            if ($image->image_path && \Storage::disk('public')->exists($image->image_path)) {
                \Storage::disk('public')->delete($image->image_path);
            }
        }

        TripImage::where('trip_id', $trip->id)->delete();

        $trip->delete();

        return redirect()->route('trips.index')->with('success', 'Trip deleted successfully.');
    }
}
