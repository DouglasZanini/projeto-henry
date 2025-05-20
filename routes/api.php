<?php

use App\Http\Controllers\RegiaoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// ...existing code...

// Regiao routes
Route::get('/regiao', [RegiaoController::class, 'index']);
Route::post('/regiao', [RegiaoController::class, 'store']);
Route::get('/regiao/{id}', [RegiaoController::class, 'show']);
Route::put('/regiao/{id}', [RegiaoController::class, 'update']);
Route::delete('/regiao/{id}', [RegiaoController::class, 'destroy']);