<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {

    Route::post('/login', [AuthController::class, 'login']);
});

Route::resource('establecimientos', \App\Http\Controllers\Api\EstablecimientoController::class)->except(['create', 'edit']);

Route::middleware('auth:api')->group(function () {});




Route::resource('categorias', \App\Http\Controllers\Api\CategoriaController::class);

 # localhost:8000/api/auth/
