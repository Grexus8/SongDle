<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ArtistController;
use App\Http\Controllers\AlbumController;
use App\Http\Controllers\SongController;
use App\Http\Controllers\EstadisticaController;

// 1. Autenticación
Route::prefix('auth')->name('auth.')->group(function (){
    Route::post('/register', [AuthController::class, 'createUser'])->name('register');
    Route::post('/login', [AuthController::class, 'loginUser'])->name('login');
});

//Route Artista
Route::apiResource('artists', ArtistController::class);

//Route Album
Route::apiResource('albums', AlbumController::class);

//Route Song
Route::apiResource('songs', SongController::class);

//Route Estadisticas
Route::apiResource('estadisticas', EstadisticaController::class);

