<?php

use App\Livewire\PlayerOverlay;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/overlay', PlayerOverlay::class);
