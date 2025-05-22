<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Trip;
use App\Models\Catches;

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

        return view('dashboard', [
            'totalTrips' => $totalTrips,
            'totalCatches' => $totalCatches,
            'bestCatch' => $bestCatch,
            'recentTrips' => $trips->take(3),
            'highlightCatches' => Catches::whereIn('trip_id', $trips->pluck('id'))->latest()->take(6)->get(),
            'weatherForecast' => [], // Placeholder for now
        ]);
    }
}