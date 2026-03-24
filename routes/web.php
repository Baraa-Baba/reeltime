<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;

use App\Models\HeroBanner;

Route::get('/', function () {
    $heroBanners = HeroBanner::orderBy('position')->get();
    return view('index', compact('heroBanners'));
})->name('home');

Route::get('/about', function () {
    return view('pages.about');
})->name('about');

Route::get('/bookings', function () {
    return view('pages.bookings');
})->name('bookings');

Route::get('/profile', function () {
    return view('pages.profile');
})->name('profile');

Route::get('/search', function () {
    return view('pages.search');
})->name('search');

Route::get('/trivia', function () {
    return view('pages.trivia');
})->name('trivia');
Route::get('/admin', [AdminController::class, 'index'])->name('admin');