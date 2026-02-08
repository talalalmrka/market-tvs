<?php

use Illuminate\Support\Facades\Route;

Route::livewire('/', 'site::home')->name('home');
Route::livewire('/screens/{user:slug}', 'site::screens.index')->name('screens');
Route::livewire('/screen/{screen:slug}', 'site::screens.show')->name('screen');
Route::get('/welcome', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard-old', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard.old');
