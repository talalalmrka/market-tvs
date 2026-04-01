<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['guest'])->group(function () {
    Route::livewire('login', 'auth::login')->name('login');
    Route::livewire('register', 'auth::register')->name('register');
});
