<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// 1. Autenticación
Route::prefix('auth')->name('auth.')->group(function (){
    Route::post('/register', [AuthController::class, 'createUser'])->name('register');
    Route::post('/login', [AuthController::class, 'loginUser'])->name('login');
});

