<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TripController;
use App\Http\Controllers\CatchesController;
use App\Http\Controllers\TripImageController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return view('welcome');
});

// Dashboard for authenticated + verified users
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Profile routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Trip, Catch, and Image routes
Route::middleware('auth')->group(function () {
    // Custom trip routes (if you want different naming than resource default)
    Route::resource('trips', TripController::class);


    Route::get('/trips/{trip}', [TripController::class, 'show'])->name('trips.show'); // singular
    Route::get('/trip/create', [TripController::class, 'create'])->name('trip.create'); //
    Route::post('/trips', [TripController::class, 'store'])->name('trips.store'); // Store trip

    // Catches & Images (nested under a trip)
    Route::post('/trips/{trip}/catches', [CatchesController::class, 'store'])->name('catches.store');
    Route::post('/trips/{trip}/images', [TripImageController::class, 'store'])->name('images.store');
    Route::post('/upload-temp-image', [TripController::class, 'uploadTempImage'])->name('trips.uploadImage');

    // Optional RESTful routes: edit, update, destroy
    Route::get('/trips/{trip}/edit', [TripController::class, 'edit'])->name('trips.edit');
    Route::delete('/trips/{trip}', [TripController::class, 'destroy'])->name('trips.destroy');
});

require __DIR__ . '/auth.php';