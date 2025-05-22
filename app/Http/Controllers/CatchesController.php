<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Catches;
use App\Models\Trip;

class CatchesController extends Controller
{
    // Store new catch
    public function store(Request $request, Trip $trip)
    {
        $validated = $request->validate([
            'length' => 'nullable|numeric',
            'quantity' => 'required|integer|min:1',
            'bait' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $validated['trip_id'] = $trip->id;

        Catches::create($validated);

        return redirect()->route('trip.show', $trip->id)->with('success', 'Catch added.');
    }

    // Edit catch
    public function edit(Catches $catch)
    {
        return view('catches.edit', compact('catch'));
    }

    // Update catch
    public function update(Request $request, Catches $catch)
    {
        $validated = $request->validate([
            'length' => 'nullable|numeric',
            'quantity' => 'required|integer|min:1',
            'bait' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $catch->update($validated);

        return redirect()->route('trip.show', $catch->trip_id)->with('success', 'Catch updated.');
    }

    // Delete catch
    public function destroy(Catches $catch)
    {
        $tripId = $catch->trip_id;
        $catch->delete();

        return redirect()->route('trip.show', $tripId)->with('success', 'Catch deleted.');
    }
}