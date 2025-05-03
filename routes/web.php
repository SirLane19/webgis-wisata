<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DestinationController;
use App\Http\Controllers\ExploreController;
use App\Models\Destination;
use App\Models\Category;

// Halaman Landing Page
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Halaman Eksplorasi (Publik)
Route::get('/explore', [ExploreController::class, 'index'])->name('explore');

// Dashboard (khusus admin)
Route::middleware(['auth', 'verified', 'isAdmin'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard', [
            'totalDestinations' => Destination::count(),
            'totalCategories' => Category::count(),
        ]);
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
