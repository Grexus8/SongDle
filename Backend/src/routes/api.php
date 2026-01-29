<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// Imports controladores
use App\Http\Controllers\ArtistController;

// 1. Autenticación
Route::prefix('auth')->name('auth.')->group(function (){
    Route::post('/register', [AuthController::class, 'createUser'])->name('register');
    Route::post('/login', [AuthController::class, 'loginUser'])->name('login');
});

//Route Artista
Route::apiResource('artists', ArtistController::class);