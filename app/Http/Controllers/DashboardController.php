<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Trip;
use App\Models\Catches;
use Illuminate\Support\Facades\Http;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $trips = Trip::withCount('catches')
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        $totalTrips = $trips->count();
        $totalCatches = $trips->sum('catches_count');
        $bestCatch = Catches::whereIn('trip_id', $trips->pluck('id'))->max('length');

        // Adelaide, Australia coordinates
        $latitude = -34.9285;
        $longitude = 138.6007;

        $weatherData = Http::get("https://api.open-meteo.com/v1/forecast", [
            'latitude' => $latitude,
            'longitude' => $longitude,
            'current' => 'temperature_2m,wind_speed_10m',
            'timezone' => 'auto'
        ]);

        $weatherForecast = [];

        if ($weatherData->successful()) {
            $data = $weatherData->json();
            $weatherForecast[] = [
                'day' => now()->format('l'),
                'temp' => $data['current']['temperature_2m'] ?? 'N/A',
                'description' => 'Wind ' . ($data['current']['wind_speed_10m'] ?? 'N/A') . ' km/h',
                'icon' => '01d' // static icon for now, Open-Meteo doesn’t provide icons
            ];
        }

        return view('dashboard', [
            'totalTrips' => $totalTrips,
            'totalCatches' => $totalCatches,
            'bestCatch' => $bestCatch,
            'recentTrips' => $trips->take(3),
            'highlightCatches' => Catches::whereIn('trip_id', $trips->pluck('id'))->latest()->take(6)->get(),
            'weatherForecast' => $weatherForecast,
        ]);
    }
}