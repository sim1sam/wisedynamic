<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

Route::get('/', [HomeController::class, 'index'])->name('home');

// Auth pages (simple static views for now)
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');
