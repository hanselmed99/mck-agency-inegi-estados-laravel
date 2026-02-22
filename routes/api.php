<?php

use App\Http\Controllers\EstadoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Endpoint para DataTables
Route::get('/estados', [EstadoController::class, 'listar'])->name('api.estados.listar');

// Endpoint para la modal
Route::get('/estados/{id}', [EstadoController::class, 'show'])->name('api.estados.show');
