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
    /**
     * Show the form to create a new trip.
     */
    public function create()
    {
        return view('trip.AddTrip');
    }

    /**
     * Store a newly created trip in storage.
     */
    public function store(Request $request)
    {
        // Validate trip & image data
        $validated = $request->validate([
            'title'        => 'required|string|max:255',
            'location'     => 'required|string|max:255',
            'date'         => 'required|date',
            'notes'        => 'nullable|string',
            'weather_info' => 'nullable|json',
            'image_path'   => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'caption'      => 'nullable|string|max:255',

            // Catches
            'catches'                => 'nullable|array',
            'catches.*.species'      => 'required_with:catches|string|max:255',
            'catches.*.weight'       => 'nullable|numeric',
            'catches.*.length'       => 'nullable|numeric',
            'catches.*.notes'        => 'nullable|string',
        ]);


        $trip = Trip::create([
            'user_id'      => Auth::id(),
            'title'        => $validated['title'],
            'location'     => $validated['location'],
            'latitude'     => $validated['latitude'],
            'longitude'    => $validated['longitude'],
            'date'         => $validated['date'],
            'notes'        => $validated['notes'] ?? null,
            'weather_info' => $validated['weather_info'] ?? null,
        ]);

        // Save image if uploaded
        if ($request->hasFile('image_path')) {
            $path = $request->file('image_path')->store('trip_images', 'public');

            TripImage::create([
                'trip_id'    => $trip->id,
                'image_path' => $path,
                'caption'    => $validated['caption'] ?? null,
            ]);
        }

        // Save catches if any
        if (!empty($validated['catches'])) {
            foreach ($validated['catches'] as $catchData) {
                Catches::create([
                    'trip_id' => $trip->id,
                    'species' => $catchData['species'],
                    'weight'  => $catchData['weight'] ?? null,
                    'length'  => $catchData['length'] ?? null,
                    'notes'   => $catchData['notes'] ?? null,
                ]);
            }
        }

        return redirect()
            ->route('dashboard')
            ->with('success', 'Trip and catches saved successfully!');
    }
}