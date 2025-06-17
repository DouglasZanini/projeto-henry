<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DepartamentosController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\VendasController;

Route::get('/', function () {
    return view('welcome');
});

//teste provisório
Route::get('/dashboard/vendas', [VendasController::class, 'index'])->name('vendas.index');
Route::post('/dashboard/vendas', [VendasController::class, 'store'])->name('vendas.store');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

//Route::get('/dashboard', function () {
//    return view('dashboard');
//})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('departamentos', DepartamentosController::class);
});

require __DIR__.'/auth.php';
