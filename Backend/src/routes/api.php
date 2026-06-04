<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ArtistController;
use App\Http\Controllers\AlbumController;
use App\Http\Controllers\SongController;
use App\Http\Controllers\EstadisticaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ArcadeController;

// 1. Autenticación (Registro y Login)
Route::prefix('auth')->name('auth.')->group(function (){
    Route::post('/register', [AuthController::class, 'createUser'])->name('register');
    Route::post('/login', [AuthController::class, 'loginUser'])->name('login');
    Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout'])->name('logout');
});


Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('users', UserController::class)->except(['store']);
});

// 3. Recursos de Música (Artista, Álbum, Canción)
Route::apiResource('artists', ArtistController::class);
Route::apiResource('albums', AlbumController::class);
Route::apiResource('songs', SongController::class);

// 4. Estadísticas
Route::get('/estadisticas', [EstadisticaController::class, 'index']);
Route::get('/estadisticas/usuario/{id_usuario}', [EstadisticaController::class, 'porUsuario']);
Route::post('/estadisticas', [EstadisticaController::class, 'store']);

// 5. Arcade
Route::post('/arcade/guardar-partida/{id}', [ArcadeController::class, 'guardarPartida']);
Route::get('/arcade/ranking-puntos', [ArcadeController::class, 'obtenerRankingPuntos']);
Route::get('/arcade/ranking-canciones', [ArcadeController::class, 'obtenerRankingCanciones']);
Route::get('/arcade/record/{id}', [ArcadeController::class, 'obtenerRecordUsuario']);