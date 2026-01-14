<?php

use App\Http\Controllers\GuessImageController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/guess', GuessImageController::class);
