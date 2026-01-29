<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard-old', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard.old');

require __DIR__ . '/dashboard.php';
require __DIR__ . '/settings.php';
