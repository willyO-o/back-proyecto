<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CategoriaController;
use App\Http\Controllers\Api\EstablecimientoController;
use App\Http\Controllers\Api\ServicioController;


Route::prefix('auth')->group(function () {

    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'registrar']);
});


Route::middleware('auth:api')->group(function () {

    Route::resource('establecimientos', EstablecimientoController::class)->except(['create', 'edit']);

    Route::resource('categorias', CategoriaController::class);
    Route::resource('servicios', ServicioController::class)->except(['create', 'edit','index']);
});

Route::prefix('public')->group(function () {
    Route::get('/categorias', [CategoriaController::class, 'indexPublic']);
    Route::get('/establecimientos', [EstablecimientoController::class , 'indexPublic']);
    Route::get('/establecimientos/{id}', [EstablecimientoController::class , 'indexPublicID']);
});






 # localhost:8000/api/auth/
