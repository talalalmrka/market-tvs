<?php

use App\Http\Controllers\ApiController;
use App\Http\Controllers\ConfigController;
use App\Http\Controllers\MenuApiController;
use App\Http\Controllers\ScreenApiController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\SidebarController;
use App\Http\Controllers\TranslationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// dashboard
Route::group(['prefix' => 'dashboard'], function () {
    Route::get('/search', [SidebarController::class, 'search'])->name('api.dashboard.search');
});
// screens
Route::group(['prefix' => 'users'], function () {
    // Route::get('/', [ScreenApiController::class, 'index'])->name('api.screens');
    // Route::get('/user/{user:slug}', [ScreenApiController::class, 'user'])->name('api.screens.user');
    // Route::get('/{screen:slug}', [ScreenApiController::class, 'show'])->name('api.screen');
});

// config
Route::group(['prefix' => 'config'], function () {
    Route::get("/", [ConfigController::class, 'index'])->name('api.config');
    Route::get("/all", [ConfigController::class, 'all'])->name('api.config.all');
    Route::get("/sidebar-items", [ConfigController::class, 'sidebarItems'])->name('api.config.sidebar-items');
    Route::get("/sidebar", [ConfigController::class, 'sidebar'])->name('api.config.sidebar');
    Route::get("/files", [ConfigController::class, 'files'])->name('api.config.files');
    Route::get("/paths", [ConfigController::class, 'paths'])->name('api.config.paths');
    Route::get("/category-options", [ConfigController::class, 'categoryOptions'])->name('api.config.category-options');
    Route::get("/file/{path}", [ConfigController::class, 'file'])->name('api.config.file');
    Route::get("/items/{path}", [ConfigController::class, 'items'])->name('api.config.items');
    Route::get("/raw/{path}", [ConfigController::class, 'raw'])->name('api.config.raw');
    Route::get("/flat/{path}", [ConfigController::class, 'flat'])->name('api.config.flat');
    Route::get("/rules/{path}", [ConfigController::class, 'rules'])->name('api.config.rules');
    Route::get("/options/{path}", [ConfigController::class, 'options'])->name('api.config.options');
    Route::get("/{path}", [ConfigController::class, 'path'])->name('api.config.path');
});

// screens
Route::group(['prefix' => 'screens', 'middleware' => ['can:manage_screens']], function () {
    Route::get('/', [ScreenApiController::class, 'index'])->name('api.screens');
    Route::get('/user/{user:slug}', [ScreenApiController::class, 'user'])->name('api.screens.user');
    Route::get('/{screen:slug}', [ScreenApiController::class, 'show'])->name('api.screen');
});

Route::group(['prefix' => 'menus', 'middleware' => ['can:manage_menus']], function () {
    Route::get('/', [MenuApiController::class, 'index'])->name('api.menus');
    Route::get('/{menu}', [MenuApiController::class, 'show'])->name('api.menu');
    Route::get('/{menu}/items', [MenuApiController::class, 'items'])->name('api.menu.items');
});

/* Route::get('/screens', function (Request $request) {
    return ScreenResource::collection(Screen::all());
}); */
Route::get('/transitions', function (Request $request) {
    return [
        'options' => slide_transition_options(),
        'values' => slide_transition_values(),
        'transitions' => slide_transitions(),
    ];
});

Route::get('ui', function () {
    // dd(ui_pages());
    return response()->json(ui_pages());
})->name('api.ui');

// screens
Route::group(['prefix' => 'translations', 'middleware' => ['can:manage_settings']], function () {
    Route::get('/', [TranslationController::class, 'index'])->name('api.translations');
    Route::get('/all', [TranslationController::class, 'all'])->name('api.translations.all');
    Route::get('/{locale}', [TranslationController::class, 'show'])->name('api.translation');
    Route::get('/{locale}/words', [TranslationController::class, 'words'])->name('api.translation.words');
});

// screens
Route::group(['prefix' => 'sidebar'], function () {
    Route::get('/', [SidebarController::class, 'index'])->name('api.sidebar');
    Route::get('/flat', [SidebarController::class, 'flat'])->name('api.flat');
    Route::get('/labels', [SidebarController::class, 'labels'])->name('api.labels');
    Route::get('/search', [SidebarController::class, 'search'])->name('api.sidebar.search');
    Route::get('/render', [SidebarController::class, 'render'])->name('api.sidebar.render');
    Route::get('/routes', [SidebarController::class, 'routes'])->name('api.sidebar.routes');
    Route::get('/settings', [SidebarController::class, 'settings']);
    Route::get('/settings/flat', [SidebarController::class, 'settingsFlat']);
    Route::get('/settings/routes', [SidebarController::class, 'settingsRoutes']);

    Route::get('/render/{index}', [SidebarController::class, 'renderItem'])->name('api.sidebar.render.item');
    Route::get('/{index}', [SidebarController::class, 'show'])->name('api.sidebar.show');
});

// settings
Route::group(['prefix' => 'settings'], function () {
    Route::get('/', [SettingController::class, 'index']);
    Route::get('/seed', [SettingController::class, 'seedData']);
    Route::get('/defaults', [SettingController::class, 'defaults']);
    Route::get('/defaults/{key}', [SettingController::class, 'defaultsItem']);
    Route::get('/{setting:key}', [SettingController::class, 'show'])->name('api.settings.show');
    Route::get('/{setting:key}/media', [SettingController::class, 'media']);
    // Route::get('/{key}', [SettingController::class, 'key'])->name('api.settings.key');
    Route::get('/type/{key}', [SettingController::class, 'type'])->name('api.settings.type');
    Route::get('/collections/{key}', [SettingController::class, 'collections'])->name('api.settings.collections');
    Route::get('/previews/{key}', [SettingController::class, 'previews'])->name('api.settings.previews');
});

// routes
Route::group(['prefix' => 'routes'], function () {
    Route::get('/{name?}', [ApiController::class, 'routes'])->name('api.routes');
});

// translate
Route::group(['prefix' => 'translate'], function () {
    Route::get('/{text}', [ApiController::class, 'translate']);
});
