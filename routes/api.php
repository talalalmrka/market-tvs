<?php

use App\Http\Controllers\ScreenApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::group(['prefix' => 'screens'], function () {
    Route::get('/', [ScreenApiController::class, 'index'])->name('api.screens');
    Route::get('/user/{user:slug}', [ScreenApiController::class, 'user'])->name('api.screens.user');
    Route::get('/{screen:slug}', [ScreenApiController::class, 'show'])->name('api.screen');
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
