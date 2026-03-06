<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
})->name('home');

Route::get('/pages/about', function () {
    return view('pages.about');
})->name('about');

Route::get('/pages/bookings', function () {
    return view('pages.bookings');
})->name('bookings');

Route::get('/pages/profile', function () {
    return view('pages.profile'); // use 'pages.Profile' if filename has capital P
})->name('profile');

Route::get('/pages/search', function () {
    return view('pages.search');
})->name('search');

Route::get('/pages/trivia', function () {
    return view('pages.trivia');
})->name('trivia');