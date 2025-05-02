<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DestinationController;
use App\Http\Controllers\ExploreController;

// Halaman Landing Page
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Halaman Eksplorasi (Publik)
Route::get('/explore', [ExploreController::class, 'index'])->name('explore');

// Dashboard (Hanya untuk user login & verified)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

// Profile & Destinasi (login required)
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('destinations', DestinationController::class);
});

// Route Auth bawaan Laravel Breeze
require __DIR__.'/auth.php';
