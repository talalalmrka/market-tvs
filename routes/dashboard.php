<?php

// use App\Livewire\Dashboard\Home\Index as Home;
// use App\Livewire\Dashboard\Screens\Index as Screens;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'dashboard', 'middleware' => ['auth', 'verified']], function () {
    //dashboard home
    // Route::view('/', 'dashboard')->name('dashboard');
    Route::livewire('/', 'dashboard::home.index')->name('dashboard');

    //users
    Route::group(['prefix' => 'users', 'middleware' => ['can:manage_users']], function () {
        Route::livewire('/', 'dashboard::users.index')->name('dashboard.users');
    });

    //roles
    Route::group(['prefix' => 'roles', 'middleware' => ['can:manage_roles']], function () {
        Route::livewire('/', 'dashboard::roles.index')->name('dashboard.roles');
    });

    //permissions
    Route::group(['prefix' => 'permissions', 'middleware' => ['can:manage_permissions']], function () {
        Route::livewire('/', 'dashboard::permissions.index')->name('dashboard.permissions');
    });
    //screens
    Route::group(['prefix' => 'screens', 'middleware' => ['can:manage_screens']], function () {
        Route::livewire('/', 'dashboard::screens.index')->name('dashboard.screens');
        // Route::livewire('/scrs', 'dashboard::scrs.index')->name('dashboard.scrs');
        Route::livewire('/edit/{screen}', 'dashboard::screens.edit')->name('dashboard.screens.edit');
        // Route::livewire('/images', 'dashboard::screens.images')->name('dashboard.screens.images');
    });

    //media
    Route::group(['prefix' => 'media', 'middleware' => ['can:manage_media']], function () {
        Route::livewire('/', 'dashboard::media.index')->name('dashboard.media');
    });

    //ui
    Route::group(['prefix' => 'ui'], function () {
        Route::livewire('colors', 'dashboard::ui.colors')->name('dashboard.ui.colors');
        Route::livewire('backgrounds', 'dashboard::ui.backgrounds')->name('dashboard.ui.backgrounds');
        Route::livewire('buttons', 'dashboard::ui.buttons')->name('dashboard.ui.buttons');
        Route::livewire('modal', 'dashboard::ui.modal')->name('dashboard.ui.modal');
        Route::livewire('sortable', 'dashboard::ui.sortable')->name('dashboard.ui.sortable');
        Route::livewire('toast', 'dashboard::ui.toast')->name('dashboard.ui.toast');
        Route::livewire('progress', 'dashboard::ui.progress')->name('dashboard.ui.progress');
    });
});
