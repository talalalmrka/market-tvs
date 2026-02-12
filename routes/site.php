<?php

use App\Events\MessageSent;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ScreenController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::livewire('/', 'site::home')->name('home');

// Screens
Route::group(['prefix' => 'screens'], function () {
    Route::get('/', [ScreenController::class, 'index'])->name('screens');
    Route::get('/user/{user:slug}', [ScreenController::class, 'user'])->name('screens.user');
    Route::get('/{screen:slug}', [ScreenController::class, 'show'])->name('screen');
});

// Chat
Route::group(['prefix' => 'chat'], function () {
    Route::get('/', [ChatController::class, 'index'])->name('chat');
    Route::post('/send', [ChatController::class, 'send'])->name('chat.send');
});

/*
Route::livewire('/screens/{user:slug}', 'site::screens.index')->name('screens');
Route::livewire('/screen/{screen:slug}', 'site::screens.show')->name('screen');
Route::get('/fullscreen', function () {
    return view('fullscreen');
})->name('fullscreen');
*/

Route::get('/welcome', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard-old', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard.old');

Route::get('/atts', function () {
    $args1 = ['class' => 'main-class'];
    $args2 = ['data-theme' => 'dark'];
    dd(atts($args1, $args2));
})->name('atts');
