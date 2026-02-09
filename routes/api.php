<?php

use App\Http\Controllers\ScreenController;
use App\Http\Resources\ScreenResource;
use App\Models\Screen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::get('/screens', [ScreenController::class, 'index'])->name('api.screens');
Route::get('/screens/{user:slug}', [ScreenController::class, 'user'])->name('api.screens.user');
Route::get('/screen/{screen:slug}', [ScreenController::class, 'show'])->name('api.screen');
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
