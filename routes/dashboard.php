<?php

// use App\Livewire\Dashboard\Home\Index as Home;
// use App\Livewire\Dashboard\Screens\Index as Screens;

use App\Http\Controllers\SidebarController;
use App\SidebarItem;
use Illuminate\Support\Facades\Route;

// SidebarItem::registerRoutes();
Route::middleware(['auth', 'verified'])->prefix('dashboard')->group(function () {

    // dashboard home
    Route::livewire('/', 'dashboard::home.index')->name('dashboard');

    // search
    Route::get('/search', [SidebarController::class, 'search'])->name('dashboard.search');

    // users
    Route::group(['prefix' => 'users', 'middleware' => ['can:manage_users']], function () {
        Route::livewire('/', 'dashboard::users.index')->name('dashboard.users');
    });

    // roles
    Route::group(['prefix' => 'roles', 'middleware' => ['can:manage_roles']], function () {
        Route::livewire('/', 'dashboard::roles.index')->name('dashboard.roles');
    });

    // permissions
    Route::group(['prefix' => 'permissions', 'middleware' => ['can:manage_permissions']], function () {
        Route::livewire('/', 'dashboard::permissions.index')->name('dashboard.permissions');
    });

    // screens
    Route::group(['prefix' => 'screens', 'middleware' => ['can:manage_screens']], function () {
        Route::livewire('/', 'dashboard::screens.index')->name('dashboard.screens');
        Route::livewire('/edit/{screen}', 'dashboard::screens.edit')->name('dashboard.screens.edit');
    });

    // media
    Route::group(['prefix' => 'media', 'middleware' => ['can:manage_media']], function () {
        Route::livewire('/', 'dashboard::media.index')->name('dashboard.media');
        Route::livewire('/old', 'dashboard::media-old.index')->name('dashboard.media-old');
    });

    // menus
    Route::group(['prefix' => 'menus', 'middleware' => ['can:manage_menus']], function () {
        Route::livewire('/', 'dashboard::menus.index')->name('dashboard.menus');
    });

    // ui
    Route::group(['prefix' => 'ui'], function () {
        register_ui_routes();
    });

    // translations
    Route::group(['prefix' => 'translations', 'middleware' => ['can:manage_settings']], function () {
        Route::livewire('/', 'dashboard::translations')->name('dashboard.translations');
    });

    // config
    Route::group(['prefix' => 'config', 'middleware' => ['can:manage_settings']], function () {
        Route::livewire('/{path}', 'dashboard::config.index')->name('dashboard.config');
    });

    // terminal
    Route::group(['prefix' => 'terminal', 'middleware' => ['can:manage_settings']], function () {
        Route::livewire('/', 'dashboard::terminal')->name('dashboard.terminal');
    });

    // settings
    Route::group(['prefix' => 'settings', 'middleware' => ['can:manage_settings']], function () {
        SidebarItem::registerSettingsRoutes();
        /*  Route::livewire('/', 'dashboard::settings.index')->name('dashboard.settings');
        Route::livewire('/general', 'dashboard::settings.app')->name('dashboard.settings.app');
        Route::livewire('/membership', 'dashboard::settings.membership')->name('dashboard.settings.membership');
        Route::livewire('/reading', 'dashboard::settings.reading')->name('dashboard.settings.reading');
        Route::livewire('/ads', 'dashboard::settings.ads')->name('dashboard.settings.ads');
        Route::livewire('/design', 'dashboard::settings.design')->name('dashboard.settings.design');
        Route::livewire('/fonts', 'dashboard::fonts.index')->name('dashboard.settings.fonts');
        Route::livewire('/typography', 'dashboard::settings.typography')->name('dashboard.settings.typography');
        Route::livewire('/mail', 'dashboard::settings.mail')->name('dashboard.settings.mail');
        Route::livewire('/auth', 'dashboard::settings.auth')->name('dashboard.settings.auth');
        Route::livewire('/broadcasting', 'dashboard::settings.broadcasting')->name('dashboard.settings.broadcasting');
        Route::livewire('/cache', 'dashboard::settings.cache')->name('dashboard.settings.cache');
        Route::livewire('/database', 'dashboard::settings.database')->name('dashboard.settings.database');
        Route::livewire('/filesystems', 'dashboard::settings.filesystems')->name('dashboard.settings.filesystems');
        Route::livewire('/env', 'dashboard::settings.env')->name('dashboard.settings.env');
        Route::livewire('/import', 'dashboard::settings.import')->name('dashboard.settings.import'); */
        Route::livewire('/{path}', 'dashboard::settings.page')->name('dashboard.settings.page');
    });

    // crud
    Route::group(['prefix' => 'crud', 'middleware' => ['role:admin']], function () {
        Route::livewire('/', 'dashboard::crud.index')->name('dashboard.crud');
        Route::livewire('/{table}', 'dashboard::crud.item')->name('dashboard.crud.item');
    });
});
