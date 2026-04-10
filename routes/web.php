<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SearchController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/about', function () {
    return view('pages.about');
})->name('about');

Route::get('/bookings', [BookingController::class, 'index'])->name('bookings');

Route::get('/search', [SearchController::class, 'index'])->name('search');

Route::get('/trivia', function () {
    $games = \App\Models\Game::all();
    return view('pages.trivia', compact('games'));
})->name('trivia');

// Admin Area
Route::middleware(['check.admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin');
    Route::delete('admin/movies/{movie}',[AdminController::class, 'destroyMovie'])->name('admin.movies.destroy');
    Route::delete('/admin/games/{game}', [AdminController::class, 'destroyGame'])->name('admin.games.destroy');
    Route::delete('/admin/bookings/{booking}', [AdminController::class, 'destroyBooking'])->name('admin.bookings.destroy');
    Route::delete('/admin/users/{user}', [AdminController::class, 'destroyUser'])->name('admin.users.destroy');

});

// Protected routes
Route::middleware(['check.auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');

    Route::post('/auth/logout', [AuthController::class, 'logout'])->name('auth.logout');
});

// Authentication routes (AJAX) - Guests only
Route::middleware(['guest'])->group(function () {
    Route::post('/auth/login', [AuthController::class, 'login'])->name('auth.login');
    Route::post('/auth/register', [AuthController::class, 'register'])->name('auth.register');
});

// Email verification routes
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verifyEmail'])
    ->middleware(['signed', 'throttle:6,1'])
    ->name('verification.verify');

Route::post('/email/verification-notification', [AuthController::class, 'resendVerificationEmail'])
    ->middleware(['auth', 'throttle:6,1'])
    ->name('verification.send');

Route::get('/auth/user', [AuthController::class, 'currentUser'])->name('auth.user');
