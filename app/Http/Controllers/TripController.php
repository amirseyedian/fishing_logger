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
        $sortOrder = $request->query('sort', 'newest'); // Default to 'newest'

        $trips = auth()->user()
            ->trips()
            ->when($sortOrder === 'oldest', function ($query) {
                $query->orderBy('date', 'asc');
            })
            ->when($sortOrder === 'newest', function ($query) {
                $query->orderBy('date', 'desc');
            })
            ->paginate(50)
            ->appends([
                'view' => $view,
                'sort' => $sortOrder,
            ]);

        return view('trip.trips', compact('trips', 'view'));
    }
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
            'date' => 'required|date',
            'notes' => 'nullable|string',
            'weather_info.precipitation' => 'nullable|numeric',
            'weather_info.air_temp' => 'nullable|numeric',
            'weather_info.wind_speed' => 'nullable|numeric',
            'weather_info.wind_direction' => 'nullable|string|max:255',
            'weather_info.moon_phase' => 'nullable|string|max:255',

            'existing_images' => 'nullable|array',
            'existing_images.*' => 'integer|exists:trip_images,id',
            'new_images.*' => 'file|image|max:5120',

            // New catches validation
            'catches' => 'nullable|array',
            'catches.*.id' => 'nullable|integer|exists:catches,id',
            'catches.*.species' => 'nullable|string|max:255',
            'catches.*.length' => 'nullable|numeric',
            'catches.*.weight' => 'nullable|numeric',
            'catches.*.quantity' => 'nullable|integer|min:1',
            'catches.*.depth' => 'nullable|numeric',
            'catches.*.water_temp' => 'nullable|numeric',
            'catches.*.bait' => 'nullable|string|max:255',
            'catches.*.notes' => 'nullable|string',
        ]);

        // Update basic trip info
        $trip->title = $validated['title'];
        $trip->location = $validated['location'];
        $trip->date = $validated['date'];
        $trip->notes = $validated['notes'] ?? null;

        $trip->precipitation = $request->input('weather_info.precipitation');
        $trip->air_temp = $request->input('weather_info.air_temp');
        $trip->wind_speed = $request->input('weather_info.wind_speed');
        $trip->wind_direction = $request->input('weather_info.wind_direction');
        $trip->moon_phase = $request->input('weather_info.moon_phase');

        $trip->save();

        // Handle images as before
        $existingImages = $request->input('existing_images', []);
        $imagesToDelete = $trip->images()->whereNotIn('id', $existingImages)->get();
        foreach ($imagesToDelete as $image) {
            Storage::disk('public')->delete($image->image_path);
            $image->delete();
        }
        if ($request->hasFile('new_images')) {
            foreach ($request->file('new_images') as $file) {
                $path = $file->store('trip_images', 'public');
                $trip->images()->create(['image_path' => $path]);
            }
        }

        // Handle catches updates
        $submittedCatches = $request->input('catches', []);

        // Get current catch IDs for this trip
        $existingCatchIds = $trip->catches()->pluck('id')->toArray();

        $submittedCatchIds = collect($submittedCatches)->pluck('id')->filter()->all();

        // Delete catches removed by the user
        $catchesToDelete = array_diff($existingCatchIds, $submittedCatchIds);
        if (!empty($catchesToDelete)) {
            $trip->catches()->whereIn('id', $catchesToDelete)->delete();
        }

        // Loop through submitted catches to update or create
        foreach ($submittedCatches as $catchData) {
            // If catch has id, update
            if (!empty($catchData['id'])) {
                $catch = $trip->catches()->find($catchData['id']);
                if ($catch) {
                    $catch->update([
                        'species' => $catchData['species'] ?? null,
                        'length' => $catchData['length'] ?? null,
                        'weight' => $catchData['weight'] ?? null,
                        'quantity' => $catchData['quantity'] ?? 1,
                        'depth' => $catchData['depth'] ?? null,
                        'water_temp' => $catchData['water_temp'] ?? null,
                        'bait' => $catchData['bait'] ?? null,
                        'notes' => $catchData['notes'] ?? null,
                    ]);
                }
            } else {
                // Create new catch only if some data exists (e.g. species)
                if (!empty($catchData['species'])) {
                    $trip->catches()->create([
                        'species' => $catchData['species'],
                        'length' => $catchData['length'] ?? null,
                        'weight' => $catchData['weight'] ?? null,
                        'quantity' => $catchData['quantity'] ?? 1,
                        'depth' => $catchData['depth'] ?? null,
                        'water_temp' => $catchData['water_temp'] ?? null,
                        'bait' => $catchData['bait'] ?? null,
                        'notes' => $catchData['notes'] ?? null,
                    ]);
                }
            }
        }

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
