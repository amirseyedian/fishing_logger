<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TripController;
use App\Http\Controllers\CatchController;
use App\Http\Controllers\TripImageController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::middleware(['auth'])->group(function () {
    // Explicit route for trip creation form
    Route::get('/trip/create', [TripController::class, 'create'])->name('trip.create');
    Route::post('/trip', [TripController::class, 'store'])->name('trip.store');

    // Trip resource routes
    Route::resource('trips', TripController::class);
    Route::post('trips/{trip}/catches', [CatchesController::class, 'store'])->name('catches.store');
    Route::post('trips/{trip}/images', [TripImageController::class, 'store'])->name('images.store');

    // Catch and image storage routes
    Route::post('trips/{trip}/catches', [CatchesController::class, 'store'])->name('catches.store');
    Route::post('trips/{trip}/images', [TripImageController::class, 'store'])->name('images.store');
});