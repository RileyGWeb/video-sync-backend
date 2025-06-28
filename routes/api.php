<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Route::get('/ping', function () {
//     return response()->json(['pong' => true]);
// });

Route::get('/ping', fn () => ['pong' => true]); // No auth

Route::middleware(['twitch.jwt'])->group(function () {
    Route::get('/secure', fn () => ['secure' => true]); // JWT required
});
