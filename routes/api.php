<?php

use App\Http\Controllers\RegiaoController;
use App\Http\Controllers\DepartamentosController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// ...existing code...

// Regiao routes
Route::get('/regiao', [RegiaoController::class, 'index']);
Route::post('/regiao', [RegiaoController::class, 'store']);
Route::get('/regiao/{id}', [RegiaoController::class, 'show']);
Route::put('/regiao/{id}', [RegiaoController::class, 'update']);
Route::delete('/regiao/{id}', [RegiaoController::class, 'destroy']);

Route::get('/departamento', [DepartamentosController::class, 'index']);
Route::post('/departamento', [DepartamentosController::class, 'store']);
Route::get('/departamento/{id}', [DepartamentosController::class, 'show']);
Route::put('/departamento/{id}', [DepartamentosController::class, 'update']);
Route::delete('/departamento/{id}', [DepartamentosController::class, 'destroy']);