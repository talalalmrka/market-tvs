<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\ScreenController;
use App\Http\Controllers\StyleController;
use App\Models\Post;
use Database\Seeders\PostSeeder;
use Database\Seeders\SettingSeeder;
use Illuminate\Support\Facades\Route;

//font style
Route::get('/style.css', [StyleController::class, 'index'])->name('style');

// Home
Route::livewire('/', 'site::home')->name('home');

// Screens
Route::group(['prefix' => 'screens'], function () {
    Route::get('/', [ScreenController::class, 'index'])->name('screens');
    Route::get('/user/{user:slug}', [ScreenController::class, 'user'])->name('screens.user');
    Route::get('/{screen:slug}', [ScreenController::class, 'show'])->name('screen');
});

// Screens
Route::group(['prefix' => 'u'], function () {
    Route::get('/{user:slug}', [ScreenController::class, 'user'])->name('user');
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

Route::group(['prefix' => 'config', 'middleware' => ['role:admin']], function () {
    Route::get('/', function () {
        return response()->json(config()->all());
    });
    Route::get('/{path}', function ($path) {
        $value = config($path);
        if (is_null($value)) {
            return response()->json([
                'error' => "Config key [{$path}] not found.",
            ], 404);
        }

        return response()->json([
            $path => $value,
        ]);
    });
});

Route::group(['prefix' => 'setting-seeder', 'middleware' => ['role:admin']], function () {
    Route::get('/', function () {
        $data = SettingSeeder::defaultSettings();

        return response()->json($data);
    });
});

Route::get('test-slug/{slug}', function (string $slug) {
    dd(Post::withSlug($slug));
});

Route::get('test-home/', function () {
    dd(PostSeeder::createHome());
});
